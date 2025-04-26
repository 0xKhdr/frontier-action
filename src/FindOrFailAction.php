<?php

namespace Frontier\Actions;

use Illuminate\Database\Eloquent\Model;

class FindOrFailAction extends EloquentAction
{
    public function handle(array $conditions): Model
    {
        return $this->model->query()->where($conditions)->firstOrFail();
    }
}
