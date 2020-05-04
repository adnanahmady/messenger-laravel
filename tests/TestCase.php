<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function assertEventIsBroadcasting($eventClassName, $channelName = '', $limit = 30)
    {
        $split = explode("\n", file_get_contents(storage_path('logs/laravel.log')));
        $logfile = implode("\n", array_splice($split, $limit * -1));

        $broadcast = "[" . Carbon::now() . "] testing.INFO: Broadcasting [" . $eventClassName;
        $channel = '] on channels [' . $channelName . ']';
        $this->assertStringContainsString(
            $broadcast . ($channelName ? $channel : ''),
            $logfile
        );
    }
}
