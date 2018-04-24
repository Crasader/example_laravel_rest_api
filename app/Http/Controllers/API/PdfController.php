<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\AbstractApiController;
use App\Repositories\PdfRepository;
use App\Services\Pdf\PdfFactory;

class PdfController extends AbstractApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // TODO: get user from request using a received token
        $userId = 1;
        $where = ['user_id' => $userId];
        $data = $this->repository->findWhere($where);

        return $this->response->success('', $data);
    }

    /**
     * Store newly created pdfs to the storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  PdfFactory $pdfFactory
     * @return \Illuminate\Http\Response
     */
    public function storeAllTypes(Request $request, PdfFactory $pdfFactory)
    {
        // TODO: change it after login logic is finished
        $userId = 1;
        $customTexts = $request->only([
            'text_short',
            'text_full',
            'text_advanced',
        ]);

        try {
            $pdfFactory->createAll($userId, $customTexts);
        } catch (Exception $e) {
            return $this->response->error('something went wrong...');
        }

        return $this->response->success('Pdfs were successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $text = 'test custom text';
        $link = 'test link';
        $values = [
            'custom_text' => $text,
            'link' => $link,
        ];
        $data = $this->repository->update($values, $id);

        return $this->response->success('The resource was successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userId = 1;
        $where = [
            'id' => $id,
            'user_id' => $userId
        ];
        $data = $this->repository->deleteWhere($where);

        return $this->response->success('The resource was successfully removed.');
    }

    protected function getRepository() : string
    {
        return PdfRepository::class;
    }
}
