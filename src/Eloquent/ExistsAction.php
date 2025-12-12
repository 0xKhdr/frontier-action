<?php

declare(strict_types=1);

namespace Frontier\Actions\Eloquent;

use Frontier\Actions\EloquentAction;

/**
 * Check if records exist matching given conditions.
 */
class ExistsAction extends EloquentAction
{
    /**
     * Check if records exist.
     *
     * @param  array<string, mixed>  $conditions  The where conditions
     * @return bool True if records exist
     */
    public function handle(array $conditions): bool
    {
        return $this->model->query()->where($conditions)->exists();
    }
}
