<?php

declare(strict_types=1);

namespace Frontier\Actions\Eloquent;

use Frontier\Actions\EloquentAction;
use Illuminate\Database\Eloquent\Model;

/**
 * Update an existing record or create a new one.
 */
class UpdateOrCreateAction extends EloquentAction
{
    /**
     * Update or create a record.
     *
     * @param  array<string, mixed>  $conditions  The attributes to match
     * @param  array<string, mixed>  $values  The values to update/create
     * @return Model The model
     */
    public function handle(array $conditions, array $values): Model
    {
        return $this->model->query()->updateOrCreate($conditions, $values);
    }
}
