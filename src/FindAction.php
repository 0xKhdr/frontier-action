<?php

namespace Frontier\Actions;

use Illuminate\Database\Eloquent\Model;

class FindAction extends EloquentAction
{
    public function handle(array $conditions): ?Model
    {
        return $this->model->query()->where($conditions)->first();
    }
}
