<?php

require_once __DIR__ .'/vendor/autoload.php';

$config = [
    'provider' => 'youdao',
    'youdao' => [
        'provider' => [
            'app_key' => '407add873c9e6db0',
            'sec_key' => 'XcIYHzLVfNY3jevpmxrFer1UJYqAEAwj',
        ],
        'language' => [
            'from' => 'auto',
            'to' => 'en',
        ],
    ],
];

$translation = new \Aliliin\LanguageTranslation\Translation($config);
var_dump($translation->translation('高兴'));die;