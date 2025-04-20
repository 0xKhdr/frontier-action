<?php

namespace Frontier\Actions;

class UpdateAction extends EloquentAction
{
    public function handle(array $conditions, array $values): int
    {
        return $this->model->where($conditions)->update($values);
    }
}