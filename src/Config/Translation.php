<?php

/*
 * This file is part of the aliliin/language-translation.
 *
 * (c) aliliin <PhperAli@Gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    'provider' => 'youdao',
    'youdao' => [
        'provider' => [
            'app_key' => env('LANGUAGE_APP_CODE'),
            'sec_key' => env('LANGUAGE_SEC_KEY'),
        ],
        'language' => [
            'from' => env('TRANSLATION_FORM'),
            'to' => env('TRANSLATION_TO'),
        ],
    ],
];
