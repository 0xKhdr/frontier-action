<?php

namespace Frontier\Actions;

class CountAction extends EloquentAction
{
    public function handle(array $conditions): int
    {
        return $this->model->query()->where($conditions)->count();
    }
}
