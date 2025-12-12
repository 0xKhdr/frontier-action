<?php

declare(strict_types=1);

namespace Frontier\Actions\Eloquent;

use Frontier\Actions\EloquentAction;
use Illuminate\Database\Eloquent\Model;

/**
 * Find the first record matching conditions.
 */
class FindAction extends EloquentAction
{
    /**
     * Find the first record matching conditions.
     *
     * @param  array<string, mixed>  $conditions  The where conditions
     * @return Model|null The model or null if not found
     */
    public function handle(array $conditions): ?Model
    {
        return $this->model->query()->where($conditions)->first();
    }
}
