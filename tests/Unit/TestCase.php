<?php

namespace Tests\Unit;

use Illuminate\Cache\ArrayStore;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Bus;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        Bus::fake();
        Event::fake();

        if (class_exists(Redis::class)) {
            Redis::shouldReceive('connection')->andReturnSelf();
        }

        Cache::extend('array', fn () => Cache::repository(new ArrayStore));
        config(['cache.default' => 'array']);
    }
}
