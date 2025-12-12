<?php

declare(strict_types=1);

namespace Frontier\Actions\Eloquent;

use Frontier\Actions\EloquentAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Find the first record matching conditions or throw exception.
 */
class FindOrFailAction extends EloquentAction
{
    /**
     * Find the first record or throw ModelNotFoundException.
     *
     * @param  array<string, mixed>  $conditions  The where conditions
     * @return Model The model
     *
     * @throws ModelNotFoundException
     */
    public function handle(array $conditions): Model
    {
        return $this->model->query()->where($conditions)->firstOrFail();
    }
}
