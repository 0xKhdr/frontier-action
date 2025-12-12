<?php

declare(strict_types=1);

namespace Frontier\Actions\Eloquent;

use Frontier\Actions\EloquentAction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Retrieve all records or paginated results.
 */
class RetrieveAction extends EloquentAction
{
    /**
     * Retrieve records, optionally paginated.
     *
     * @param  array<int, string>  $columns  Columns to select
     * @param  array<string, mixed>  $options  Options including 'per_page' for pagination
     */
    public function handle(array $columns = ['*'], array $options = []): Collection|LengthAwarePaginator
    {
        $perPage = Arr::get($options, 'per_page');

        return $perPage
            ? $this->model->query()->select($columns)->paginate($perPage)
            : $this->model->query()->select($columns)->get();
    }
}
