<?php

namespace Cloudstudio\TokenUsage\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Cloudstudio\TokenUsage\TokenUsage
 */
class TokenUsage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Cloudstudio\TokenUsage\TokenUsage::class;
    }
}
