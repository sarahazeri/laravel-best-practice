<?php

namespace Tests\Feature;

use Illuminate\Cache\ArrayStore;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (class_exists(Redis::class)) {
            Redis::shouldReceive('connection')->andReturnSelf();
        }

        Cache::extend('array', fn () => Cache::repository(new ArrayStore));
        config(['cache.default' => 'array']);
    }
}
