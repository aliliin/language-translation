<?php


namespace Aliliin\LanguageTranslation\Exceptions;


class ProvidersErrorException extends Exception
{
    /**
     * @var array
     */
    public $raw = [];

    /**
     * ProvidersErrorException constructor.
     *
     * @param string $message
     * @param int $code
     * @param array $raw
     */
    public function __construct($message, $code, array $raw = [])
    {
        parent::__construct($message, intval($code));

        $this->raw = $raw;
    }
}