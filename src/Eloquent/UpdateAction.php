<?php

declare(strict_types=1);

namespace Frontier\Actions\Eloquent;

use Frontier\Actions\EloquentAction;

/**
 * Update records matching given conditions.
 */
class UpdateAction extends EloquentAction
{
    /**
     * Update records matching conditions.
     *
     * @param  array<string, mixed>  $conditions  The where conditions
     * @param  array<string, mixed>  $values  The values to update
     * @return int Number of affected rows
     */
    public function handle(array $conditions, array $values): int
    {
        return $this->model->query()->where($conditions)->update($values);
    }
}
