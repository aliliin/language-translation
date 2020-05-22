<?php

/*
 * This file is part of the aliliin/language-translation.
 *
 * (c) aliliin <PhperAli@Gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Aliliin\LanguageTranslation\Interfaces;

interface YouDaoConfigurationConstant
{
    const PROVIDER_NAME = 'youdao';

    const TRANSLATION_INFO_URL = 'https://openapi.youdao.com/api';

    const TRANSLATION_FORM = 'auto'; // 源语言

    const TRANSLATION_TO = 'auto';     //目标语言

    const  TRANSLATION_SIGN_TYPE = 'v3'; // 签名类型

    const TRANSLATION_EXT = false;

    const TRANSLATION_VOICE = false; //0为女声，1为男声。默认为女声

    const TRANSLATION_STRICT = false; // 是否严格按照指定from和to进行翻译：true/false	false	如果为false，则会自动中译英，英译中。默认为false
}
