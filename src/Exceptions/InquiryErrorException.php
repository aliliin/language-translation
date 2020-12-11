<?php

/*
 * This file is part of the aliliin/language-translation.
 *
 * (c) aliliin <PhperAli@Gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Aliliin\LanguageTranslation\Exceptions;

/**
 * Class InquiryErrorException.
 *
 * @author Aliliin <PhperAli@Gmail.com>
 */
class InquiryErrorException extends Exception
{
    /**
     * @var array
     */
    public $raw = [];

    /**
     * InquiryErrorException constructor.
     *
     * @param string $message
     * @param int    $code
     */
    public function __construct($message, $code, array $raw = [])
    {
        parent::__construct($message, intval($code));

        $this->raw = $raw;
    }
}
