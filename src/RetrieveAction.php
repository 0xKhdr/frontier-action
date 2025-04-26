<?php

namespace Frontier\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class RetrieveAction extends EloquentAction
{
    public function handle(array $columns = ['*'], array $options = []): Collection|LengthAwarePaginator
    {
        $perPage = Arr::get($options, 'per_page');

        return $perPage
            ? $this->model->query()->select($columns)->paginate($perPage)
            : $this->model->query()->select($columns)->get();
    }
}
