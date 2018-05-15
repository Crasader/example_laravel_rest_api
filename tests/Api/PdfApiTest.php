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

    private const API_PREFIX = '/api/';
    private const ROUTE_PATH = 'pdfs/';
    private const PDF_TABLE_NAME = 'pdfs';
    private const DEFAULT_USER_ID = 0;
    private const PDF_TYPES_NUMBER = 3;
    private const WRONG_PDF_ID = 123;
    private const WRONG_PDF_TYPE = 4;
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

        $response = $this->get($this->getPath());

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
        $endpoint = $this->getPath() . 'all';
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
            $this->assertDatabaseHas('pdfs', [
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
        $endpoint = $this->getPath() . 'all';
        $data = [
            'text_short' => 'test_short_text',
            'text_full' => 'test_full_text',
        ];

        $response = $this->post($endpoint, $data);

        $missingPdfType = 'advanced';
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => "PdfData instance doesn't exist for the '$missingPdfType' pdf type.",
            ])
        ;

        $this->clearStorage();
    }

    public function testShow_Correct()
    {
        $testRecord = factory($this->getModel())->create([
            'type' => PdfTypes::ADVANCED,
        ]);

        $endpoint = $this->getPath() . $testRecord->id;

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
        $endpoint = $this->getPath() . self::WRONG_PDF_ID;

        $response = $this->get($endpoint);

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'message' => 'No query results for model [App\\Pdf].',
            ])
        ;
    }

    public function testUpdate_Correct()
    {
        $textBeforeUpdate = 'before update';
        $testRecord = factory($this->getModel())->create([
            'custom_text' => $textBeforeUpdate,
        ]);

        $this->assertDatabaseHas(self::PDF_TABLE_NAME, [
            'id' => $testRecord->id,
            'custom_text' => $textBeforeUpdate,
        ]);

        $expectedText = 'after update';
        $endpoint = $this->getPath() . $testRecord->id;
        $data = ['text' => $expectedText];

        $response = $this->put($endpoint, $data);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'The resource was successfully updated.',
            ])
        ;

        $this->assertDatabaseHas(self::PDF_TABLE_NAME, [
            'id' => $testRecord->id,
            'custom_text' => $expectedText,
        ]);

        $this->clearStorage();
    }

    public function testUpdate_ModelNotFoundException()
    {
        $endpoint = $this->getPath() . self::WRONG_PDF_ID;
        $data = ['text' => ''];

        $response = $this->put($endpoint, $data);

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'message' => 'No query results for model [App\\Pdf].',
            ])
        ;
    }

    public function testUpdate_PdfException()
    {
        $testRecord = factory($this->getModel())->create([
            'type' => self::WRONG_PDF_TYPE,
        ]);

        $endpoint = $this->getPath() . $testRecord->id;
        $data = ['text' => ''];

        $response = $this->put($endpoint, $data);

        $expectedMessage = sprintf(
            "The requested pdf type with '%d' id doesn't exist.",
            self::WRONG_PDF_TYPE
        );
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => $expectedMessage,
            ])
        ;
    }

    public function testDestroy_Correct()
    {
        $filenameForRemove = 'short_0.pdf';
        $testRecord = factory($this->getModel())->create(['filename' => $filenameForRemove]);

        // copy a dummy file to the base storage folder
        $dummyFile = 'short_example.pdf';
        $dummyFileContent = Storage::disk('dummy-files')->get($dummyFile);
        Storage::put($filenameForRemove, $dummyFileContent);

        $this->assertDatabaseHas(self::PDF_TABLE_NAME, ['id' => $testRecord->id]);

        $endpoint = $this->getPath() . $testRecord->id;

        $response = $this->delete($endpoint);

        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'The resource was successfully removed.',
            ])
        ;

        $this->assertDatabaseMissing(self::PDF_TABLE_NAME, ['id' => $testRecord->id]);
        Storage::assertMissing($filenameForRemove);
    }

    public function testDestroy_ModelNotFoundException()
    {
        $endpoint = $this->getPath() . self::WRONG_PDF_ID;

        $response = $this->delete($endpoint);

        $response
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'message' => 'No query results for model [App\\Pdf].',
            ])
        ;
    }

    private function getPath(): string
    {
        return self::API_PREFIX . self::ROUTE_PATH;
    }

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