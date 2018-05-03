<?php

namespace App\Http\Controllers\API;

use App\Services\Pdf\PdfDeleter;
use App\Structs\PdfData;
use Illuminate\Http\JsonResponse;
use App\Repositories\PdfRepository;
use App\Services\Pdf\PdfFactory;
use App\Services\Pdf\PdfDataGetter;
use App\Exceptions\PdfException;
use Illuminate\Http\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Services\Pdf\PdfUpdater;

class PdfController extends AbstractApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $where = ['user_id' => $this->user->id];
        $data = $this->repository->findWhere($where);

        return $this->response->success('', $data);
    }

    /**
     * Store newly created pdfs to the storage
     *
     * @param  PdfFactory $pdfFactory
     * @param  PdfDataGetter $pdfDataGetter
     * @return JsonResponse
     */
    public function storeAllTypes(
        PdfFactory $pdfFactory,
        PdfDataGetter $pdfDataGetter
    ) {
        $customTexts = $this->request->only([
            'text_short',
            'text_full',
            'text_advanced',
        ]);

        $pdfDataArray = $pdfDataGetter->get($this->user, $customTexts);

        try {
            $pdfFactory->createAll($this->user->id, $pdfDataArray);
        } catch (PdfException | ValidatorException $e) {
            return $this->response->error(
                $e->getMessage(),
                null,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->response->success('Pdfs were successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $where = [
            'id' => $id,
            'user_id' => $this->user->id,
        ];
        $data = $this->repository->findWhere($where);

        return $this->response->success('', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PdfUpdater $pdfUpdater
     * @param  int $id
     * @return JsonResponse
     */
    public function update(PdfUpdater $pdfUpdater, $id)
    {
        $text = $this->request->input('text');

        $pdfData = new PdfData;
        $pdfData->email = $this->user->email;
        $pdfData->name = $this->user->name;
        $pdfData->text = $text;

        try {
            $pdfUpdater->update($id, $this->user->id, $pdfData);
        } catch (PdfException | ValidatorException $e) {
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
     */
    public function destroy(PdfDeleter $pdfDeleter, $id)
    {
        try {
            $pdfDeleter->remove($this->user->id, $id);
        } catch (PdfException $e) {
            return $this->response->error(
                $e->getMessage(),
                null,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->response->success('The resource was successfully removed.');
    }

    protected function getRepository() : string
    {
        return PdfRepository::class;
    }
}
