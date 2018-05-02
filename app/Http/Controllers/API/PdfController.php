<?php

namespace App\Http\Controllers\API;

use App\Services\Pdf\PdfDeleter;
use App\Structs\PdfData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\PdfRepository;
use App\Services\Pdf\PdfFactory;
use App\Services\Pdf\PdfDataGetter;
use App\Exceptions\PdfException;
use Illuminate\Http\Response;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Services\Pdf\PdfUpdater;
use App\User;

class PdfController extends AbstractApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        dd($this->user);

        // TODO: get user from request using a received token
        $userId = 1;
        $where = ['user_id' => $userId];
        $data = $this->repository->findWhere($where);

        return $this->response->success('', $data);
    }

    /**
     * Store newly created pdfs to the storage
     *
     * @param  \Illuminate\Http\Request $request
     * @param  PdfFactory $pdfFactory
     * @param  PdfDataGetter $pdfDataGetter
     * @return JsonResponse
     */
    public function storeAllTypes(
        Request $request,
        PdfFactory $pdfFactory,
        PdfDataGetter $pdfDataGetter
    ) {
        // TODO: change it after login logic is finished
        $userId = 1;
        $user = new User();
        $user->name = 'John Doe';
        $user->email = 'john.doe@email.com';

        $customTexts = $request->only([
            'text_short',
            'text_full',
            'text_advanced',
        ]);

        $pdfDataArray = $pdfDataGetter->get($user, $customTexts);

        try {
            $pdfFactory->createAll($userId, $pdfDataArray);
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
        // TODO: get user from request using a received token
        $userId = 1;
        $where = [
            'id' => $id,
            'user_id' => $userId
        ];
        $data = $this->repository->findWhere($where);

        return $this->response->success('', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PdfUpdater $pdfUpdater
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(PdfUpdater $pdfUpdater, Request $request, $id)
    {
        $text = $request->input('text');
        $userId = 1;
        $user = new User;
        $user->name = 'John Doe';
        $user->email = 'john.doe@email.com';

        $pdfData = new PdfData;
        $pdfData->email = $user->email;
        $pdfData->name = $user->name;
        $pdfData->text = $text;

        try {
            $pdfUpdater->update($id, $userId, $pdfData);
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
        $userId = 1;

        try {
            $pdfDeleter->remove($userId, $id);
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
