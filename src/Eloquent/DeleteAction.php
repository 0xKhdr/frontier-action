<?php

declare(strict_types=1);

namespace Frontier\Actions\Eloquent;

use Frontier\Actions\EloquentAction;

/**
 * Delete records matching given conditions.
 */
class DeleteAction extends EloquentAction
{
    /**
     * Delete records matching conditions.
     *
     * @param  array<string, mixed>  $conditions  The where conditions
     * @return int Number of deleted rows
     */
    public function handle(array $conditions): int
    {
        return $this->model->query()->where($conditions)->delete();
    }
}
