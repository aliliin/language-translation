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

use Aliliin\LanguageTranslation\Exceptions\HttpException;
use Aliliin\LanguageTranslation\Exceptions\InquiryErrorException;
use Aliliin\LanguageTranslation\Exceptions\ProvidersErrorException;
use Aliliin\LanguageTranslation\Interfaces\YouDaoConfigurationConstant;
use Aliliin\LanguageTranslation\Traits\HasHttpRequest;

class YouDao extends AbstractProvider implements YouDaoConfigurationConstant
{
    use HasHttpRequest;

    public function translation($fromLanguage)
    {
        $appKey = self::getProviderAppKey($this->config);
        $secKey = self::getProviderSecKey($this->config);
        $salt = self::create_guid();
        $args = array(
            'q' => $fromLanguage,
            'appKey' => $appKey,
            'salt' => $salt,
            'from' => self::getLanguageFrom($this->config),
            'to' => self::getLanguageTo($this->config),
            'signType' => self::TRANSLATION_SIGN_TYPE,
        );
        $curtime = strtotime('now');
        $args['curtime'] = $curtime;
        $signStr = $appKey.self::truncate($fromLanguage).$salt.$curtime.$secKey;
        $args['sign'] = hash('sha256', $signStr);

        $result = $this->post(self::TRANSLATION_INFO_URL, $args);

        if (isset($result['errorCode']) && 0 != $result['errorCode']) {
            throw new ProvidersErrorException('error', $result['errorCode'], $result);
        }

        return $result['translation'];
    }

    private static function truncate($q)
    {
        $len = self::abslength($q);

        return $len <= 20 ? $q : (mb_substr($q, 0, 10).$len.mb_substr($q, $len - 10, $len));
    }

    private static function abslength($str)
    {
        if (empty($str)) {
            return 0;
        }
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, 'utf-8');
        } else {
            preg_match_all('/./u', $str, $ar);

            return count($ar[0]);
        }
    }

    private static function getLanguageFrom(array $languageArray)
    {
        $from = $languageArray[\strtolower(self::PROVIDER_NAME)]['language']['from'];

        return empty($from) ? self::TRANSLATION_FORM : $from;
    }

    private static function getLanguageTo(array $languageArray)
    {
        $to = $languageArray[\strtolower(self::PROVIDER_NAME)]['language']['to'];

        return empty($languageArray[\strtolower(self::PROVIDER_NAME)]['language']['to']) ? self::TRANSLATION_TO : $to;
    }

    /**
     * @param array $configArray
     *
     * @return string
     */
    private static function getProviderAppKey(array $configArray)
    {
        return $configArray[\strtolower(self::PROVIDER_NAME)]['provider']['app_key'];
    }

    /**
     * @param array $configArray
     *
     * @return string
     */
    private static function getProviderSecKey(array $configArray)
    {
        return $configArray[\strtolower(self::PROVIDER_NAME)]['provider']['sec_key'];
    }

    private static function create_guid()
    {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(' ', $microTime);
        $dec_hex = dechex($a_dec * 1000000);
        $sec_hex = dechex($a_sec);
        self::ensure_length($dec_hex, 5);
        self::ensure_length($sec_hex, 6);

        $guid = '';
        $guid .= $dec_hex;
        $guid .= self::create_guid_section(3);
        $guid .= '-';
        $guid .= self::create_guid_section(4);
        $guid .= '-';
        $guid .= self::create_guid_section(4);
        $guid .= '-';
        $guid .= self::create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= self::create_guid_section(6);

        return $guid;
    }

    private static function create_guid_section($characters)
    {
        $return = '';
        for ($i = 0; $i < $characters; ++$i) {
            $return .= dechex(mt_rand(0, 15));
        }

        return $return;
    }

    private static function ensure_length(&$string, $length)
    {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, '0');
        } elseif ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }

    /**
     * @param     $url
     * @param     $params
     * @param     $headers
     * @param int $SUCCESS_STATUS
     *
     * @return array
     *
     * @throws HttpException
     * @throws InquiryErrorException
     */
    protected function sendRequestPost($url, $params, $headers, $SUCCESS_STATUS = self::GLOBAL_SUCCESS_CODE)
    {
        try {
            $result = $this->post($url, $params, $headers);
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }

        if (!\is_array($result)) {
            $result = \json_decode($result, true);
        }

        if (isset($result['returnCode']) && $SUCCESS_STATUS != $result['returnCode']) {
            throw new InquiryErrorException($result['message'], $result['returnCode'], $result);
        }

        return $result;
    }
}
