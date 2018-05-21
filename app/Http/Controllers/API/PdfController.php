<?php

namespace App\Http\Controllers\API;

use App\Structs\PdfData;
use Illuminate\Http\JsonResponse;
use App\Repositories\PdfRepository;
use App\Factories\PdfFactory;
use App\Services\Pdf\PdfDataGetter;
use App\Exceptions\PdfException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PdfController extends AbstractApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $where = ['user_id' => $this->user->id];
        $data = $this->repository->findWhere($where);

        return $this->response->success('', $data);
    }

    /**
     * Store newly created PDFs to the storage
     *
     * @param  PdfFactory $pdfFactory
     * @param  PdfDataGetter $pdfDataGetter
     * @return JsonResponse
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function storeAllTypes(
        PdfFactory $pdfFactory,
        PdfDataGetter $pdfDataGetter
    ): JsonResponse {
        $this->request->validate([
            'text_short' => 'filled',
            'text_full' => 'filled',
            'text_advanced' => 'filled',
        ]);
        $customTexts = $this->request->only([
            'text_short',
            'text_full',
            'text_advanced',
        ]);

        $pdfDataArray = $pdfDataGetter->get($this->user, $customTexts);

        try {
            $pdfFactory->createAll($this->user->id, $pdfDataArray);
        } catch (PdfException $e) {
            return $this->response->error(
                $e->getMessage(),
                null,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->response->success('PDFs were successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function show(int $id): JsonResponse
    {
        $where = [
            'id' => $id,
            'user_id' => $this->user->id,
        ];
        $data = $this->repository->findOneWhereOrFail($where);

        return $this->response->success('', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PdfFactory $pdfFactory
     * @param  int $id
     * @return JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(PdfFactory $pdfFactory, int $id): JsonResponse
    {
        $where = [
            'id' => $id,
            'user_id' => $this->user->id,
        ];
        $pdf = $this->repository->findOneWhereOrFail($where);

        $this->request->validate([
            'text' => 'required',
        ]);
        $text = $this->request->input('text');

        $pdfData = new PdfData;
        $pdfData->email = $this->user->email;
        $pdfData->name = $this->user->name;
        $pdfData->text = $text;

        try {
            $pdfFactory->create($pdf->type, $this->user->id, $pdfData);
        } catch (PdfException $e) {
            return $this->response->error(
                $e->getMessage(),
                null,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->response->success('The resource was successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return JsonResponse
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function destroy(int $id): JsonResponse
    {
        $where = [
            'id' => $id,
            'user_id' => $this->user->id,
        ];
        $pdf = $this->repository->findOneWhereOrFail($where);

        Storage::delete($pdf->filename);
        $this->repository->delete($id);

        return $this->response->success('The resource was successfully removed.');
    }

    /**
     * @return string
     */
    protected function getRepository(): string
    {
        return PdfRepository::class;
    }
}
