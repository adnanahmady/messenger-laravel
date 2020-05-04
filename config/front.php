<?php

return [
    'verify' => [
        /**
         * verifing user route address
         */
        'user' => implode('/', [
            env('FRONT_BASE_URI'),
            env('FRONT_VERIFY_USER')
        ]),
    ],
];