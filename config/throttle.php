<?php

return [
    'limit' => [
        'tries' => env('THROTTLE_TRIES_LIMIT', 5),
        'penalty' => env('THROTTLE_PENALTY_SECONDS', 7200),
    ],

];
