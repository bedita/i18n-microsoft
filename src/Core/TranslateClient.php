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
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => (string)Hash::get($this->options, 'auth_key'),
            'Ocp-Apim-Subscription-Region' => (string)Hash::get($this->options, 'location'),
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
        $headers = $this->headers;
        $body = json_encode([['Text' => $text]]);
        $headers['Content-Length'] = strlen($body);
        $headers = array_map(
            function ($key, $val) {
                return sprintf('%s: %s', $key, $val);
            },
            array_keys($headers),
            array_values($headers),
        );
        $url = sprintf('%s&from=%s&to=%s', $this->endpoint, $from, $to);

        return $this->apiCall($url, $body, $headers);
    }

    /**
     * Perform api call to obtain translation
     *
     * @param string $url The url to call
     * @param string $body The json body to pass to api call
     * @param array $headers The headers
     * @return string The translation, if any
     * @codeCoverageIgnore
     */
    public function apiCall(string $url, string $body, array $headers): string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);
        curl_close($ch);

        return (string)Hash::get(
            json_decode($result, true),
            '0.translations.0.text',
        );
    }
}
