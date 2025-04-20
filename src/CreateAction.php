<?php

namespace Frontier\Actions;

use Illuminate\Database\Eloquent\Model;

class CreateAction extends EloquentAction
{
    public function handle(array $values): Model
    {
        return $this->model->create($values);
    }
}