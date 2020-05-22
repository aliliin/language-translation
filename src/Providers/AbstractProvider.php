<?php

/*
 * This file is part of the aliliin/language-translation.
 *
 * (c) aliliin <PhperAli@Gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Aliliin\LanguageTranslation\Providers;

abstract class AbstractProvider
{
    const GLOBAL_SUCCESS_CODE = 200;

    const GLOBAL_SUCCESS_MSG = 'OK';

    /**
     * @var array
     */
    protected $config;

    /**
     * Base constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @param $fromLanguage
     *
     * @return \Finecho\Logistics\Order
     */
    abstract protected function translation($fromLanguage);
}
