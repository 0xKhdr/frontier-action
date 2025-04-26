<?php

namespace Frontier\Actions;

class ExistsAction extends EloquentAction
{
    public function handle(array $conditions): bool
    {
        return $this->model->query()->where($conditions)->exists();
    }
}
