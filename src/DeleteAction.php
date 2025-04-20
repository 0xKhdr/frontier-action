<?php

namespace Frontier\Actions;

class DeleteAction extends EloquentAction
{
    public function handle(array $conditions): int
    {
        return $this->model->where($conditions)->delete();
    }
}