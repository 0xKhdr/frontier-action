<?php

declare(strict_types=1);

namespace Frontier\Actions\Eloquent;

use Frontier\Actions\EloquentAction;
use Illuminate\Database\Eloquent\Model;

/**
 * Create a new model record.
 */
class CreateAction extends EloquentAction
{
    /**
     * Create a new record with the given attributes.
     *
     * @param  array<string, mixed>  $values  The attributes to create
     * @return Model The created model
     */
    public function handle(array $values): Model
    {
        return $this->model->query()->create($values);
    }
}
