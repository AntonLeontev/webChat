<?php

use Illuminate\Support\Facades\Log;

if (! function_exists('td')) {
    function td(mixed $text, mixed $context = null)
    {
        if (gettype($text) === 'string') {
            Log::channel('telegram')->debug($text, [$context]);

            return;
        }

        if (is_null($context)) {
            Log::channel('telegram')->debug('', [$text]);

            return;
        }

        Log::channel('telegram')->debug('', [$text, $context]);
    }
}
