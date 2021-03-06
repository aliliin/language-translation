<?php

/*
 * This file is part of the aliliin/language-translation.
 *
 * (c) aliliin <PhperAli@Gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Aliliin\LanguageTranslation\Tests;

use Aliliin\LanguageTranslation\Translation;
use PHPUnit\Framework\TestCase;

class TranslationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testGetTranslation()
    {
        $config = [
            'provider' => 'youdao',
            'youdao' => [
                'provider' => [
                    'app_key' => 'xxxxxx',
                    'sec_key' => 'xxxxxx',
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

        $translation = \Mockery::mock(Translation::class, $config)
            ->shouldAllowMockingProtectedMethods();

        $translation->shouldReceive('translation')
            ->andReturn($response);

        $this->assertSame($response, $translation->translation('高兴'));
    }

    public function tearDown(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}
