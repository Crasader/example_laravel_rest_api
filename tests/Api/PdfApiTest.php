<?php

namespace Tests\Api;

use App\Constants\PdfTypes;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Pdf;

class PdfApiTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    private const PDF_ROUTE_PREFIX = 'api/pdfs/';
    private const PDF_TABLE_NAME = 'pdfs';
    private const DEFAULT_USER_ID = 0;
    private const PDF_TYPES_NUMBER = 3;
    private const WRONG_PDF_ID = 123;
    private const WRONG_PDF_TYPE = 4;
    private const MODEL_NOT_FOUND_EXCEPTION_MESSAGE = 'Resource not found.';
    private const PDF_DATA_NOT_EXIST_EXCEPTION_MESSAGE = <<<MSG
PdfData instance doesn't exist for the '%s' pdf type.
MSG;
    private const PDF_TYPE_NOT_EXIST_EXCEPTION_MESSAGE = <<<MSG
The requested pdf type with '%d' id doesn't exist.
MSG;
    private const PDF_ITEM_JSON_STRUCTURE = [
        'id',
        'user_id',
        'type',
        'custom_text',
        'filename',
        'link',
        'created_at',
        'updated_at',
    ];
    private const EXPECTED_FILES_IN_STORAGE = [
        'short_0.pdf',
        'full_0.pdf',
        'advanced_0.pdf',
    ];

    public function setUp()
    {
        parent::setUp();
    }

    public function testIndex()
    {
        factory($this->getModel(), self::PDF_TYPES_NUMBER)->create();

        $response = $this->get(self::PDF_ROUTE_PREFIX);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => ['*' => self::PDF_ITEM_JSON_STRUCTURE],
                'message',
            ])
            ->assertJsonCount(self::PDF_TYPES_NUMBER, 'data');
        ;
    }

    public function testStoreAllTypes_Correct()
    {
        $endpoint = self::PDF_ROUTE_PREFIX . 'all';
        $data = [
            'text_short' => 'test_short_text',
            'text_full' => 'test_full_text',
            'text_advanced' => 'test_advanced_text',
        ];
        $response = $this->post($endpoint , $data);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'PDFs were successfully created.'])
        ;

        $expectedTypesInDb = [PdfTypes::SHORT, PdfTypes::FULL, PdfTypes::ADVANCED];
        foreach ($expectedTypesInDb as $type) {
            $this->assertDatabaseHas(self::PDF_TABLE_NAME, [
                'user_id' => self::DEFAULT_USER_ID,
                'type' => $type,
            ]);
        }

        foreach (self::EXPECTED_FILES_IN_STORAGE as $filename) {
            Storage::assertExists($filename);
        }

        $this->clearStorage();
    }

    public function testStoreAllTypes_PdfException()
    {
        $endpoint = self::PDF_ROUTE_PREFIX . 'all';
        $data = [
            'text_short' => 'test_short_text',
            'text_full' => 'test_full_text',
        ];
        $response = $this->post($endpoint, $data);

        $message = sprintf(
            self::PDF_DATA_NOT_EXIST_EXCEPTION_MESSAGE,
            'advanced'
        );
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['message' => $message])
        ;

        $this->clearStorage();
    }

    public function testStoreAllTypes_ValidationException()
    {
        $endpoint = self::PDF_ROUTE_PREFIX . 'all';
        $data = [
            'text_short' => '',
            'text_full' => '',
            'text_advanced' => '',
        ];
        $response = $this->post($endpoint, $data);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['message' => 'The given data is invalid.'])
            ->assertJsonStructure([
                'errors' => [
                    'text_short',
                    'text_full',
                    'text_advanced',
                ]
            ])
        ;
    }

    public function testShow_Correct()
    {
        $testRecord = factory($this->getModel())->create([
            'type' => PdfTypes::ADVANCED,
        ]);

        $endpoint = self::PDF_ROUTE_PREFIX . $testRecord->id;
        $response = $this->get($endpoint);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => self::PDF_ITEM_JSON_STRUCTURE,
                'message',
            ])
            ->assertJson([
                'data' => [
                    'user_id' => self::DEFAULT_USER_ID,
                    'type' => PdfTypes::ADVANCED,
                ],
            ])
        ;
    }

    public function testShow_ModelNotFoundException()
    {
        $endpoint = self::PDF_ROUTE_PREFIX . self::WRONG_PDF_ID;
        $response = $this->get($endpoint);

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => self::MODEL_NOT_FOUND_EXCEPTION_MESSAGE])
        ;
    }

    public function testUpdate_Correct()
    {
        $textBeforeUpdate = 'before update';
        $testRecord = factory($this->getModel())
            ->create(['custom_text' => $textBeforeUpdate])
        ;

        $this->assertDatabaseHas(self::PDF_TABLE_NAME, [
            'id' => $testRecord->id,
            'custom_text' => $textBeforeUpdate,
        ]);

        $expectedText = 'after update';
        $endpoint = self::PDF_ROUTE_PREFIX . $testRecord->id;
        $data = ['text' => $expectedText];
        $response = $this->put($endpoint, $data);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'The resource was successfully updated.'])
        ;

        $this->assertDatabaseHas(self::PDF_TABLE_NAME, [
            'id' => $testRecord->id,
            'custom_text' => $expectedText,
        ]);

        $this->clearStorage();
    }

    public function testUpdate_ModelNotFoundException()
    {
        $endpoint = self::PDF_ROUTE_PREFIX . self::WRONG_PDF_ID;
        $data = ['text' => ''];
        $response = $this->put($endpoint, $data);

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => self::MODEL_NOT_FOUND_EXCEPTION_MESSAGE])
        ;
    }

    public function testUpdate_PdfException()
    {
        $testRecord = factory($this->getModel())
            ->create(['type' => self::WRONG_PDF_TYPE])
        ;

        $endpoint = self::PDF_ROUTE_PREFIX . $testRecord->id;
        $data = ['text' => 'test_text'];
        $response = $this->put($endpoint, $data);

        $expectedMessage = sprintf(
            self::PDF_TYPE_NOT_EXIST_EXCEPTION_MESSAGE,
            self::WRONG_PDF_TYPE
        );
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['message' => $expectedMessage])
        ;
    }

    public function testUpdate_ValidationException()
    {
        $testRecord = factory($this->getModel())->create();

        $endpoint = self::PDF_ROUTE_PREFIX . $testRecord->id;
        $data = ['text' => ''];
        $response = $this->put($endpoint, $data);

        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson(['message' => 'The given data is invalid.'])
            ->assertJsonStructure([
                'errors' => ['text'],
            ])
        ;
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testDestroy_Correct()
    {
        $filenameForRemove = 'short_0.pdf';
        $testRecord = factory($this->getModel())
            ->create(['filename' => $filenameForRemove])
        ;

        $this->copyDummyFileToBaseStorageFolder($filenameForRemove);

        $this->assertDatabaseHas(self::PDF_TABLE_NAME, ['id' => $testRecord->id]);

        $endpoint = self::PDF_ROUTE_PREFIX . $testRecord->id;
        $response = $this->delete($endpoint);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(['message' => 'The resource was successfully removed.'])
        ;

        $this->assertDatabaseMissing(self::PDF_TABLE_NAME, ['id' => $testRecord->id]);
        Storage::assertMissing($filenameForRemove);
    }

    public function testDestroy_ModelNotFoundException()
    {
        $endpoint = self::PDF_ROUTE_PREFIX . self::WRONG_PDF_ID;
        $response = $this->delete($endpoint);

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => self::MODEL_NOT_FOUND_EXCEPTION_MESSAGE])
        ;
    }

    /**
     * @param string $targetFilename
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function copyDummyFileToBaseStorageFolder(string $targetFilename)
    {
        $dummyFileContent = Storage::disk('dummy-files')
            ->get('short_example.pdf')
        ;
        Storage::put($targetFilename, $dummyFileContent);
    }

    /**
     * @return string
     */
    private function getModel(): string
    {
        return Pdf::class;
    }

    private function clearStorage(): void
    {
        foreach (self::EXPECTED_FILES_IN_STORAGE as $filename) {
            if (Storage::exists($filename)) {
                Storage::delete($filename);
            }
        }
    }
}