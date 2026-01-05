<?php

/**
 **********************************************************************
 * -------------------------------------------------------------------
 * Project Name : Abdal SpotPlayer PHP
 * File Name    : SpotPlayer.php
 * Author       : Ebrahim Shafiei (EbraSha)
 * Email        : Prof.Shafiei@Gmail.com
 * Created On   : 2026-01-05 16:32:41
 * Description  : Main class for SpotPlayer API client with support for both instance and static methods
 * -------------------------------------------------------------------
 *
 * "Coding is an engaging and beloved hobby for me. I passionately and insatiably pursue knowledge in cybersecurity and programming."
 * – Ebrahim Shafiei
 *
 **********************************************************************
 */

namespace Abdal\SpotPlayer;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * Class SpotPlayer
 * 
 * PHP API client for SpotPlayer Secure Media Player service
 * Supports both OOP instance-based and static method usage
 * 
 * @package Abdal\SpotPlayer
 */
class SpotPlayer
{
    /**
     * API Base URL
     */
    const BASE_URL = 'https://panel.spotplayer.ir';

    /**
     * API Key for authentication
     * 
     * @var string
     */
    private $apiKey;

    /**
     * HTTP Client instance
     * 
     * @var Client
     */
    private $client;

    /**
     * Static instance for static method calls
     * 
     * @var self
     */
    private static $staticInstance = null;

    /**
     * SpotPlayer constructor
     * 
     * @param string $apiKey API key from SpotPlayer dashboard
     */
    public function __construct($apiKey = null)
    {
        if ($apiKey !== null) {
            $this->apiKey = $apiKey;
        }

        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'timeout' => 30.0,
            'verify' => true
        ]);
    }

    /**
     * Set API key for instance
     * 
     * @param string $apiKey API key from SpotPlayer dashboard
     * @return self
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Get API key
     * 
     * @return string|null
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Get or create static instance
     * 
     * @param string|null $apiKey API key for static instance
     * @return self
     */
    private static function getStaticInstance($apiKey = null)
    {
        if (self::$staticInstance === null) {
            self::$staticInstance = new self($apiKey);
        } elseif ($apiKey !== null) {
            self::$staticInstance->setApiKey($apiKey);
        }

        if (self::$staticInstance->apiKey === null && $apiKey === null) {
            throw new \RuntimeException('API key must be set before using static methods. Use SpotPlayer::setApiKey() or pass it to the method.');
        }

        return self::$staticInstance;
    }

    /**
     * Set API key for static instance
     * 
     * @param string $apiKey API key from SpotPlayer dashboard
     * @return void
     */
    public static function setStaticApiKey($apiKey)
    {
        self::getStaticInstance($apiKey);
    }

    /**
     * Create a new license via API
     * 
     * @param array $licenseData License data array
     * @param string|null $apiKey Optional API key (for static calls)
     * @return array Response data containing _id, key, and url
     * @throws \InvalidArgumentException If required fields are missing
     * @throws \RuntimeException If API request fails
     */
    public function createLicense(array $licenseData, $apiKey = null)
    {
        $apiKey = $apiKey ?? $this->apiKey;

        if ($apiKey === null) {
            throw new \RuntimeException('API key is required. Set it via constructor, setApiKey(), or pass as parameter.');
        }

        // Validate required fields
        $this->validateLicenseData($licenseData, true);

        // Prepare request headers
        $headers = [
            '$API' => $apiKey,
            '$LEVEL' => '-1',
            'Content-Type' => 'application/json'
        ];

        try {
            $response = $this->client->post('/license/edit/', [
                'headers' => $headers,
                'json' => $licenseData
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Invalid JSON response from API: ' . json_last_error_msg());
            }

            return $responseData;
        } catch (RequestException $e) {
            $errorMessage = 'API request failed';
            if ($e->hasResponse()) {
                $errorMessage .= ': ' . $e->getResponse()->getBody()->getContents();
            }
            throw new \RuntimeException($errorMessage, $e->getCode(), $e);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('HTTP request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Edit an existing license via API
     * 
     * @param string $licenseId License ID to edit
     * @param array $licenseData License data to update
     * @param string|null $apiKey Optional API key (for static calls)
     * @return array Response data
     * @throws \InvalidArgumentException If license ID is invalid
     * @throws \RuntimeException If API request fails
     */
    public function editLicense($licenseId, array $licenseData, $apiKey = null)
    {
        $apiKey = $apiKey ?? $this->apiKey;

        if ($apiKey === null) {
            throw new \RuntimeException('API key is required. Set it via constructor, setApiKey(), or pass as parameter.');
        }

        if (empty($licenseId) || !is_string($licenseId)) {
            throw new \InvalidArgumentException('License ID is required and must be a string.');
        }

        // Prepare request headers
        $headers = [
            '$API' => $apiKey,
            '$LEVEL' => '-1',
            'Content-Type' => 'application/json'
        ];

        try {
            $response = $this->client->post('/license/edit/' . $licenseId, [
                'headers' => $headers,
                'json' => $licenseData
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException('Invalid JSON response from API: ' . json_last_error_msg());
            }

            return $responseData;
        } catch (RequestException $e) {
            $errorMessage = 'API request failed';
            if ($e->hasResponse()) {
                $errorMessage .= ': ' . $e->getResponse()->getBody()->getContents();
            }
            throw new \RuntimeException($errorMessage, $e->getCode(), $e);
        } catch (GuzzleException $e) {
            throw new \RuntimeException('HTTP request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Validate license data for creation
     * 
     * @param array $licenseData License data to validate
     * @param bool $isCreate Whether this is for creation (stricter validation)
     * @return void
     * @throws \InvalidArgumentException If validation fails
     */
    private function validateLicenseData(array $licenseData, $isCreate = false)
    {
        if ($isCreate) {
            // Required fields for creation: course, name, watermark.texts.text
            if (!isset($licenseData['course']) || !is_array($licenseData['course']) || empty($licenseData['course'])) {
                throw new \InvalidArgumentException('Field "course" is required and must be a non-empty array.');
            }

            if (!isset($licenseData['name']) || empty(trim($licenseData['name']))) {
                throw new \InvalidArgumentException('Field "name" is required and must be a non-empty string.');
            }

            if (!isset($licenseData['watermark']['texts']) || !is_array($licenseData['watermark']['texts']) || empty($licenseData['watermark']['texts'])) {
                throw new \InvalidArgumentException('Field "watermark.texts" is required and must be a non-empty array.');
            }

            // Validate watermark texts
            foreach ($licenseData['watermark']['texts'] as $index => $textItem) {
                if (!isset($textItem['text']) || empty(trim($textItem['text']))) {
                    throw new \InvalidArgumentException("Field \"watermark.texts[{$index}].text\" is required and must be a non-empty string.");
                }
            }
        }
    }

    /**
     * Create a new license via API (Static method)
     * 
     * @param array $licenseData License data array
     * @param string|null $apiKey Optional API key
     * @return array Response data containing _id, key, and url
     * @throws \InvalidArgumentException If required fields are missing
     * @throws \RuntimeException If API request fails
     */
    public static function createLicenseStatic(array $licenseData, $apiKey = null)
    {
        $instance = self::getStaticInstance($apiKey);
        return $instance->createLicense($licenseData, $apiKey);
    }

    /**
     * Edit an existing license via API (Static method)
     * 
     * @param string $licenseId License ID to edit
     * @param array $licenseData License data to update
     * @param string|null $apiKey Optional API key
     * @return array Response data
     * @throws \InvalidArgumentException If license ID is invalid
     * @throws \RuntimeException If API request fails
     */
    public static function editLicenseStatic($licenseId, array $licenseData, $apiKey = null)
    {
        $instance = self::getStaticInstance($apiKey);
        return $instance->editLicense($licenseId, $licenseData, $apiKey);
    }

    /**
     * Magic method to support static calls to instance methods
     * 
     * @param string $method Method name
     * @param array $arguments Method arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        // Map static method names to instance methods
        $methodMap = [
            'createLicense' => 'createLicense',
            'editLicense' => 'editLicense'
        ];

        if (isset($methodMap[$method])) {
            $instance = self::getStaticInstance();
            return call_user_func_array([$instance, $methodMap[$method]], $arguments);
        }

        throw new \BadMethodCallException("Method {$method} does not exist or is not accessible statically.");
    }
}

