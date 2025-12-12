<?php

declare(strict_types=1);

namespace Frontier\Actions;

use Illuminate\Database\Eloquent\Model;

/**
 * Base action class for Eloquent model operations.
 *
 * Extend this class when your action works with a specific Eloquent model.
 * Set the $model property in your constructor.
 */
abstract class EloquentAction extends BaseAction
{
    /** @var Model The Eloquent model instance */
    protected Model $model;
}
