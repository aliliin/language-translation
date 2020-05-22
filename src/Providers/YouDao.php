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

        $ret = false;
        $i = 0;
        while (false === $ret) {
            if ($i > 1) {
                break;
            }
            if ($i > 0) {
                sleep(1);
            }
            $ret = self::callOnce(self::TRANSLATION_INFO_URL, $args, 'post', false, 2000, array());
            ++$i;
        }

        return $ret;
    }

    private static function callOnce($url, $args = null, $method = 'get', $withCookie = false, $timeout = CURL_TIMEOUT, $headers = array())
    {
        $ch = curl_init();
        if ('post' == $method) {
            $data = self::convert($args);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POST, 1);
        } else {
            $data = self::convert($args);
            if ($data) {
                $url = self::spliceUrl($data, self::TRANSLATION_INFO_URL);
            }
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($withCookie) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
        }
        $r = curl_exec($ch);

        curl_close($ch);

        return $r;
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

        return empty($languageArray[\strtolower(self::PROVIDER_NAME)]['language']['from']) ? 'auto' : $from;
    }

    private static function getLanguageTo(array $languageArray)
    {
        $to = $languageArray[\strtolower(self::PROVIDER_NAME)]['language']['to'];

        return empty($languageArray[\strtolower(self::PROVIDER_NAME)]['language']['to']) ? 'auto' : $to;
    }

    private static function spliceUrl($data, $url = self::TRANSLATION_INFO_URL)
    {
        if ($data) {
            if (stripos($url, '?') > 0) {
                $url .= "&$data";
            } else {
                $url .= "?$data";
            }
        }

        return $url;
    }

    private static function convert(&$args)
    {
        $data = '';
        if (is_array($args)) {
            foreach ($args as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $data .= $key.'['.$k.']='.rawurlencode($v).'&';
                    }
                } else {
                    $data .= "$key=".rawurlencode($val).'&';
                }
            }

            return trim($data, '&');
        }

        return $args;
    }

    /**
     * @return string
     */
    public function getProviderName()
    {
        return static::PROVIDER_NAME;
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
