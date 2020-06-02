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


```

## 获取翻译结果
```
$res = $translation->translation('高兴');
```	
示例：
```
[
    "happy",
]
```

## 参数说明
```
 translation(string $language) 
```
> -` $language ` - 需要翻译的词语



## License

MIT
