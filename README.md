Token-Usage Package

Token-Usage is a Laravel package that provides token management for your Laravel application. It includes functionalities for tracking token usage, setting token limits, and more. This package is perfect for developers looking to manage and monitor token usage in their Laravel applications.

Installation

composer require cloudstudio/token-usage

Configuration

php artisan vendor:publish --tag="token-usage-config"

Published config file:
return [
    'model_mappings' => [
        'user' => \App\Models\User::class,
    ],
    'plans' => [
        'basic' => [
            'model_limits' => [
                'user' => [
                    'daily' => 1,
                    'weekly' => 5,
                    'monthly' => 10,
                    'yearly' => 100,
                ],
            ],
        ],
        'premium' => [
            // ...
        ],
    ],
];

Usage

Basic Usage

use Cloudstudio\TokenUsage\Facades\TokenUsage;

// Add tokens
$project = Project::first();
$project = $project->dailyTokens(5);

// Remove tokens
$project = $project->dailyTokens(-3);

// Reset tokens
$project = $project->resetTokens();

Blade Directive

@hasTokenUsage('video', 'daily')
    I have tokens
@endHasTokenUsage

Route Middleware

Route::get('/test-token-usage', function () {
    return response()->json(['message' => 'You have tokens available!']);
})->middleware('check-token-usage:video,daily');

Form Request Validation

$request->validate([
    'field' => [new TokenUsageRule('Video', 'daily')],
]);

Testing

pest

Changelog, Contributing, and Security
