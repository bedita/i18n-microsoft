<?php
declare(strict_types=1);

/**
 * BEdita, API-first content management framework
 * Copyright 2023 Atlas Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */
namespace BEdita\I18n\Microsoft\Test\Core;

use BEdita\I18n\Microsoft\Core\TranslateClient;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;

/**
 * {@see \BEdita\I18n\Microsoft\Core\TranslateClient} Test Case
 */
#[CoversClass(TranslateClient::class)]
#[CoversMethod(TranslateClient::class, '__construct')]
#[CoversMethod(TranslateClient::class, 'translate')]
class TranslateClientTest extends TestCase
{
    /**
     * Test constructor.
     *
     * @return void
     */
    public function testConstructor(): void
    {
        $client = new class (['auth_key' => 'test-auth-key', 'location' => 'test-region']) extends TranslateClient
        {
            /**
             * Get headers
             *
             * @return array
             */
            public function getHeaders(): array
            {
                return $this->headers;
            }
        };
        $expected = [
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => 'test-auth-key',
            'Ocp-Apim-Subscription-Region' => 'test-region',
        ];
        $actual = $client->getHeaders();
        static::assertSame($expected, $actual);
    }

    /**
     * Test `translate` method.
     *
     * @return void
     */
    public function testTranslate(): void
    {
        $client = new class (['auth_key' => 'test-auth-key', 'location' => 'test-region']) extends TranslateClient
        {
            /**
             * Get headers
             *
             * @return array
             */
            public function getHeaders(): array
            {
                return $this->headers;
            }

            /**
             * @inheritDoc
             */
            public function apiCall(string $url, string $body, array $headers): string
            {
                $content = json_decode($body, true);
                $text = $content[0]['Text'];

                return sprintf('translation of "%s" from en to it', $text);
            }
        };
        $expected = 'translation of "Hello world!" from en to it';
        $actual = $client->translate('Hello world!', 'en', 'it');
        static::assertSame($expected, $actual);
    }
}
