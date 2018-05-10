<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Repository\Eloquent\BaseRepository;

abstract class AbstractRepository extends BaseRepository
{
    /**
     * @param array $where
     * @param array $columns
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneWhereOrFail(array $where, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();

        $this->applyConditions($where);

        $model = $this->model->firstOrFail($columns);

        $this->resetModel();

        return $this->parserResult($model);
    }
}
