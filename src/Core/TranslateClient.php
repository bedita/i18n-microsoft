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
namespace BEdita\I18n\Microsoft\Core;

use Cake\Utility\Hash;

class TranslateClient
{
    /**
     * API endpoint.
     *
     * @var string
     */
    protected string $endpoint = 'https://api.cognitive.microsofttranslator.com/translate?api-version=3.0';

    /**
     * The headers.
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * The options.
     *
     * @var array
     */
    protected array $options = [];

    /**
     * Constructor.
     *
     * @param array $options The options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
        $this->headers = [
            'Content-type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => (string)Hash::get($this->options, 'auth_key'),
        ];
    }

    /**
     * Translate a text from a language to another using Microsoft translator API.
     *
     * @param string $text The text to translate
     * @param string $from The source language
     * @param string $to The target language
     * @return string The translated text
     */
    public function translate(string $text, string $from, string $to): string
    {
        $content = json_encode([['Text' => $text]]);
        $headers = $this->headers;
        $headers['X-ClientTraceId'] = com_create_guid();
        $headers['Content-length'] = strlen($content);
        $options = [
            'http' => [
                'header' => implode("\r\n", array_map(
                    fn ($key, $value) => sprintf('%s: %s', $key, $value),
                    array_keys($headers),
                    array_values($headers)
                )),
                'method' => 'POST',
                'content' => $content,
            ],
        ];
        $translation = (string)file_get_contents(
            sprintf('%s&from=%s&to=%s', $this->endpoint, $from, $to),
            false,
            stream_context_create($options)
        );

        return json_encode(json_decode($translation), JSON_UNESCAPED_UNICODE);
    }
}