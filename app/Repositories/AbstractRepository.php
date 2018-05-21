<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;

abstract class AbstractRepository extends BaseRepository
{
    /**
     * @param array $where
     * @param array $columns
     * @return mixed
     * @throws \Prettus\Repository\Exceptions\RepositoryException
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
