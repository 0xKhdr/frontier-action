<?php

declare(strict_types=1);

namespace Frontier\Actions\Eloquent;

use Frontier\Actions\EloquentAction;

/**
 * Count records matching given conditions.
 */
class CountAction extends EloquentAction
{
    /**
     * Count records matching conditions.
     *
     * @param  array<string, mixed>  $conditions  The where conditions
     * @return int The count of matching records
     */
    public function handle(array $conditions = []): int
    {
        return $this->model->query()->where($conditions)->count();
    }
}
