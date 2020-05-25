<h1 align="center"> language-translation </h1>

<p align="center"> A Natural language translation SD..</p>

[![Build Status](https://travis-ci.com/aliliin/language-translation.svg?branch=master)](https://travis-ci.com/aliliin/language-translation)
![StyleCI build status](https://github.styleci.io/repos/266182741/shield)
[![Latest Stable Version](https://poser.pugx.org/aliliin/language-translation/v)](//packagist.org/packages/aliliin/language-translation)
[![Latest Unstable Version](https://poser.pugx.org/aliliin/language-translation/v/unstable)](//packagist.org/packages/aliliin/language-translation)
[![Total Downloads](https://poser.pugx.org/aliliin/language-translation/downloads)](//packagist.org/packages/aliliin/language-translation)
[![License](https://poser.pugx.org/aliliin/language-translation/license)](//packagist.org/packages/aliliin/language-translation)


## 介绍
 
 目前已支持有道平台
 
 * [有道智云](https://ai.youdao.com/gw.s)


## 安装

```shell
$ composer require aliliin/language-translation dev-master  -vvv
```

## 使用

```php	
require_once __DIR__ . '/vendor/autoload.php';

use Aliliin\LanguageTranslation\Translation;

$config = [
    'provider' => 'youdao',
    'youdao' => [
        'provider' => [
            'app_key' => 'xxxxxxx',
            'sec_key' => 'xxxxxxxx',
        ],
        'language' => [ 
            'from' => 'zh-CHS',
            'to' => 'en',
        ]
    ],
];

$translation = new Translation($config);
$res = $translation->translation('高兴');

```	

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/aliliin/language-translation/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/aliliin/language-translation/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT
