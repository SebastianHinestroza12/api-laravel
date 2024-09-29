<?php

namespace App\Providers;

use App\JsonApi\Mixins\JsonApiQueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use ReflectionException;

class QueryMacroServiceProvider extends ServiceProvider
{
    public function register(): void {}

    /**
     * @throws ReflectionException
     */
    public function boot(): void
    {
        Builder::mixin(new JsonApiQueryBuilder);
    }
}
