# 🎬 Abdal SpotPlayer PHP

**Language**: [English](README.md) | [فارسی](README.fa.md)

[![License](https://img.shields.io/badge/license-GPL--2.0--or--later-blue.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-blue.svg)](https://php.net)
[![Packagist](https://img.shields.io/packagist/v/abdal/spotplayer-php.svg)](https://packagist.org/packages/abdal/spotplayer-php)

A professional PHP API client for **SpotPlayer** Secure Media Player service with Digital Rights Management (DRM) support. This package provides a clean and easy-to-use interface for managing licenses, creating test licenses, and editing existing licenses through the SpotPlayer API.

## 🎯 Why This Software Was Created

SpotPlayer is a specialized Secure Media Player focused on **Digital Rights Management (DRM)** and preventing **Piracy** and **Unauthorized Access**. This PHP package was created to simplify the integration of SpotPlayer API into PHP applications, allowing developers to:

- **Easily manage licenses** programmatically without manual dashboard operations
- **Automate license creation** for e-commerce and course platforms
- **Integrate DRM protection** into existing PHP applications
- **Support both OOP and static method** usage patterns for flexibility

Whether you're building an e-learning platform, video streaming service, or any application that requires protected media content, this package provides a robust and developer-friendly solution that helps users solve the problem of managing protected digital content licenses efficiently.

## ✨ Features and Capabilities

- ✅ **Dual Usage Patterns**: Support for both OOP instance-based and static method calls
- ✅ **PHP 7+ Compatible**: Works with PHP 7.0 and higher versions
- ✅ **GuzzleHttp Integration**: Professional HTTP client for reliable API communication
- ✅ **License Management**: Create, edit, and manage SpotPlayer licenses
- ✅ **Test License Support**: Easy creation of test licenses for development
- ✅ **Comprehensive Validation**: Built-in validation for required fields
- ✅ **Error Handling**: Detailed error messages and exception handling
- ✅ **Type Safety**: Proper type hints and documentation
- ✅ **PSR-4 Autoloading**: Standard Composer autoloading support
- ✅ **Full API Coverage**: Complete support for all SpotPlayer license features including watermarks, device limits, and access controls

## 📦 Requirements

- PHP >= 7.0
- Composer
- GuzzleHttp (automatically installed via Composer)
- SpotPlayer API key (obtain from your SpotPlayer dashboard)

## 🚀 Installation

Install the package via Composer:

```bash
composer require abdal/spotplayer-php
```

Or add it to your `composer.json`:

```json
{
    "require": {
        "abdal/spotplayer-php": "^1.0.0"
    }
}
```

Then run:

```bash
composer install
```

## 📖 How to Use the Software

### Method 1: Using OOP Instance

```php
<?php

require_once 'vendor/autoload.php';

use Abdal\SpotPlayer\SpotPlayer;

// Initialize with your API key
$spotPlayer = new SpotPlayer('YOUR_API_KEY_HERE');

// Create a simple license
$licenseData = [
    'course' => ['5d2ee35bcddc092a304ae5eb'],
    'name' => 'customer-name',
    'watermark' => [
        'texts' => [
            ['text' => '09022223301']
        ]
    ]
];

$result = $spotPlayer->createLicense($licenseData);

echo "License ID: " . $result['_id'] . "\n";
echo "License Key: " . $result['key'] . "\n";
echo "License URL: " . $result['url'] . "\n";
```

### Method 2: Using Static Methods

```php
<?php

require_once 'vendor/autoload.php';

use Abdal\SpotPlayer\SpotPlayer;

// Set API key once
SpotPlayer::setStaticApiKey('YOUR_API_KEY_HERE');

// Use static methods
$licenseData = [
    'course' => ['5d2ee35bcddc092a304ae5eb'],
    'name' => 'customer-name',
    'watermark' => [
        'texts' => [
            ['text' => '09022223301']
        ]
    ]
];

$result = SpotPlayer::createLicense($licenseData);
```

### Creating a Test License

```php
$testLicenseData = [
    'test' => true,
    'course' => ['5d2ee35bcddc092a304ae5eb'],
    'name' => 'test-customer',
    'watermark' => [
        'texts' => [
            ['text' => '09022223301']
        ]
    ]
];

$result = $spotPlayer->createLicense($testLicenseData);
```

### Creating a Full-Featured License

```php
$fullLicenseData = [
    'test' => false,
    'course' => ['5d2ee35bcddc092a304ae5eb', '5d2ee35bcddc092a304ae5ec'],
    'offline' => 30,
    'name' => 'premium-customer',
    'payload' => 'order-12345',
    'data' => [
        'confs' => 0,
        'limit' => [
            '5d2ee35bcddc092a304ae5eb' => '0-',
            '5d2ee35bcddc092a304ae5ec' => '1,4-6,10-'
        ]
    ],
    'watermark' => [
        'position' => 511,
        'reposition' => 15,
        'margin' => 40,
        'texts' => [
            [
                'text' => '09022223301',
                'repeat' => 10,
                'font' => 1,
                'weight' => 1,
                'color' => 2164260863,
                'size' => 50,
                'stroke' => ['color' => 2164260863, 'size' => 1]
            ]
        ]
    ],
    'device' => [
        'p0' => 1,  // All Devices
        'p1' => 1,  // Windows
        'p2' => 0,  // MacOS
        'p3' => 0,  // Ubuntu
        'p4' => 0,  // Android
        'p5' => 0,  // iOS
        'p6' => 0   // WebApp
    ]
];

$result = $spotPlayer->createLicense($fullLicenseData);
```

### Editing an Existing License

```php
$licenseId = '5dcab540796f5d4d48a6570f';

$editData = [
    'name' => 'updated-customer',
    'data' => [
        'limit' => [
            '5d2ee35bcddc092a304ae5eb' => '0-'
        ]
    ],
    'device' => [
        'p1' => 1  // Update only Windows device limit
    ]
];

$result = $spotPlayer->editLicense($licenseId, $editData);
```

### Error Handling

```php
try {
    $result = $spotPlayer->createLicense($licenseData);
} catch (\InvalidArgumentException $e) {
    // Validation errors (missing required fields, etc.)
    echo "Validation Error: " . $e->getMessage();
} catch (\RuntimeException $e) {
    // API request failures
    echo "API Error: " . $e->getMessage();
}
```

## 🔧 API Methods

### `createLicense(array $licenseData, string|null $apiKey = null): array`

Creates a new license in SpotPlayer.

**Required Fields:**
- `course` (array): Array of course IDs
- `name` (string): Customer name
- `watermark.texts` (array): Array of watermark text objects
  - `watermark.texts[].text` (string): Watermark text (required)

**Optional Fields:**
- `test` (bool): Set to `true` for test licenses
- `offline` (int): Offline access days (0-365)
- `payload` (string): Custom payload data
- `data` (array): License data including limits and configurations
- `watermark` (array): Full watermark configuration
- `device` (array): Device access limits

**Returns:** Array containing `_id`, `key`, and `url`

### `editLicense(string $licenseId, array $licenseData, string|null $apiKey = null): array`

Edits an existing license. Only provided fields will be updated.

**Parameters:**
- `$licenseId` (string): The license ID to edit
- `$licenseData` (array): Fields to update
- `$apiKey` (string|null): Optional API key

**Returns:** Updated license data

## 🐛 Reporting Issues

If you encounter any issues or have configuration problems, please reach out via email at **Prof.Shafiei@Gmail.com**. You can also report issues on [GitLab](https://gitlab.com/ebrasha) or [GitHub](https://github.com/ebrasha/abdal-spotplayer-php).

## ❤️ Donation

If you find this project helpful and would like to support further development, please consider making a donation:
- [Donate Here](https://alphajet.ir/abdal-donation)

## 🤵 Programmer

Handcrafted with Passion by **Ebrahim Shafiei (EbraSha)**

- **E-Mail**: [Prof.Shafiei@Gmail.com](mailto:Prof.Shafiei@Gmail.com)
- **GitHub**: [@ebrasha](https://github.com/ebrasha)
- **Twitter/X**: [@ProfShafiei](https://x.com/ProfShafiei)
- **LinkedIn**: [profshafiei](https://www.linkedin.com/in/profshafiei/)
- **Telegram**: [@ProfShafiei](https://t.me/ProfShafiei)

## 📜 License

This project is licensed under the **GPL-2.0-or-later** License.

---

**Note**: This package is an independent PHP client for SpotPlayer API and is not officially affiliated with SpotPlayer. Make sure you have a valid SpotPlayer account and API key before using this package.
