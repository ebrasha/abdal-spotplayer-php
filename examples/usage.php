<?php

/**
 **********************************************************************
 * -------------------------------------------------------------------
 * Project Name : Abdal SpotPlayer PHP
 * File Name    : usage.php
 * Author       : Ebrahim Shafiei (EbraSha)
 * Email        : Prof.Shafiei@Gmail.com
 * Created On   : 2026-01-05 16:32:41
 * Description  : Example usage file demonstrating both OOP instance and static method usage
 * -------------------------------------------------------------------
 *
 * "Coding is an engaging and beloved hobby for me. I passionately and insatiably pursue knowledge in cybersecurity and programming."
 * – Ebrahim Shafiei
 *
 **********************************************************************
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Abdal\SpotPlayer\SpotPlayer;

// Your API key from SpotPlayer dashboard
$apiKey = 'YhD5yX/9FQzVTg+c6YHQ7gCtZAs=';

// ============================================
// Method 1: Using OOP Instance
// ============================================

$spotPlayer = new SpotPlayer($apiKey);

// Example 1: Create a simple license (minimum required fields)
$licenseData = [
    'course' => ['5d2ee35bcddc092a304ae5eb'],
    'name' => 'customer',
    'watermark' => [
        'texts' => [
            ['text' => '09022223301']
        ]
    ]
];

try {
    $result = $spotPlayer->createLicense($licenseData);
    echo "License created successfully!\n";
    echo "License ID: " . $result['_id'] . "\n";
    echo "License Key: " . $result['key'] . "\n";
    echo "License URL: " . $result['url'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Example 2: Create a test license
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

try {
    $result = $spotPlayer->createLicense($testLicenseData);
    echo "Test license created successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Example 3: Create a full-featured license
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
            ],
            [
                'text' => '09022223301',
                'repeat' => 1,
                'font' => 1,
                'weight' => 1,
                'color' => 2164260863,
                'size' => 200,
                'stroke' => ['color' => 2164260863, 'size' => 1]
            ]
        ]
    ],
    'device' => [
        'p0' => 1,
        'p1' => 1,
        'p2' => 0,
        'p3' => 0,
        'p4' => 0,
        'p5' => 0,
        'p6' => 0
    ]
];

try {
    $result = $spotPlayer->createLicense($fullLicenseData);
    echo "Full-featured license created successfully!\n";
    $licenseId = $result['_id'];
    
    // Example 4: Edit the license
    $editData = [
        'name' => 'updated-customer',
        'data' => [
            'limit' => [
                '5d2ee35bcddc092a304ae5eb' => '0-'
            ]
        ],
        'device' => [
            'p1' => 1
        ]
    ];
    
    $editResult = $spotPlayer->editLicense($licenseId, $editData);
    echo "License edited successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// ============================================
// Method 2: Using Static Methods
// ============================================

// Option A: Set API key first, then use static methods
SpotPlayer::setStaticApiKey($apiKey);

try {
    // Using magic method __callStatic (calls createLicenseStatic internally)
    $result = SpotPlayer::createLicense($licenseData);
    echo "License created via static method (using __callStatic)!\n";
    echo "License ID: " . $result['_id'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Option B: Pass API key directly to static method
try {
    // Using explicit static method
    $result = SpotPlayer::createLicenseStatic($licenseData, $apiKey);
    echo "License created via explicit static method (createLicenseStatic)!\n";
    echo "License ID: " . $result['_id'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Option C: Pass API key directly using magic method
try {
    // Using magic method with API key parameter
    $result = SpotPlayer::createLicense($licenseData, $apiKey);
    echo "License created via static method with API key parameter!\n";
    echo "License ID: " . $result['_id'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Edit license using static method (magic method)
try {
    $licenseId = '5dcab540796f5d4d48a6570f';
    $editData = [
        'name' => 'updated-customer',
        'device' => ['p1' => 1]
    ];
    $result = SpotPlayer::editLicense($licenseId, $editData);
    echo "License edited via static method (using __callStatic)!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Edit license using explicit static method
try {
    $licenseId = '5dcab540796f5d4d48a6570f';
    $editData = [
        'name' => 'updated-customer-v2',
        'device' => ['p1' => 1, 'p2' => 1]
    ];
    $result = SpotPlayer::editLicenseStatic($licenseId, $editData, $apiKey);
    echo "License edited via explicit static method (editLicenseStatic)!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

