<?php

/*
 * This file is part of the aliliin/language-translation.
 *
 * (c) aliliin <PhperAli@Gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Aliliin\LanguageTranslation\Tests\Providers;

use Aliliin\LanguageTranslation\Providers\YouDao;
use PHPUnit\Framework\TestCase;
use  Mockery;

class YouDaoTest extends TestCase
{
    public function testGetTranslation()
    {
        $config = [
            'provider' => 'youdao',
            'youdao' => [
                'provider' => [
                    'app_key' => 'XXXXXXXX',
                    'sec_key' => 'XXXXXXXX',
                ],
                'language' => [
                    'from' => 'auto',
                    'to' => 'en',
                ],
            ],
        ];

        $response = [
            'happy',
        ];

        $youdao = Mockery::mock(YouDao::class.'[translation]', [$config])
            ->shouldAllowMockingProtectedMethods();

        $youdao->shouldReceive('translation')
            ->andReturn($response);

        $this->assertSame($response, $youdao->translation('高兴'));
    }
}
