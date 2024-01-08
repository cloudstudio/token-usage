
# Token-Usage Laravel Package

Token-Usage is a Laravel package that provides advanced token management functionalities for Laravel applications. It offers features for tracking token usage, setting token limits based on different time periods, and integrating these checks easily into Laravel applications. This package is ideal for developers looking to efficiently manage and monitor token consumption in their Laravel projects.

The main idea is to track and manage token usage for AI services. For example, if a SaaS platform charges $10 for 1000 tokens, this package can efficiently monitor and manage the token consumption. It is perfect to work with OpenAI, [ollama](https://packagist.org/packages/cloudstudio/ollama-laravel) for enhanced functionality, etc.


## Installation

```bash
composer require cloudstudio/token-usage
```

## Configuration

Publish the package configuration file:

```bash
php artisan vendor:publish --tag="token-usage-config"
```

This will publish a config file with the following structure:

```php
return [
    'model_mappings' => [
        'user' => \App\Models\User::class,
        // Add more model mappings here
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
                // Define more model limits here
            ],
        ],
        'premium' => [
            // Define premium plan limits...
        ],
    ],
];
```

Additionally, publish the package's migrations:

```bash
php artisan vendor:publish --tag="token-usage-migrations"
```

These migrations will create tables in your database to store token usage data. The migrations are compatible with UUIDs. If your application doesn't use UUIDs, you can modify the migration files accordingly.

## User Setup

For the package to work correctly, your `users` table needs to have a `plan` field. You can add this field by creating a new migration or creating a new accesor in your `User` model. For example, to create a new migration, run the following command:

```bash
php artisan make:migration add_plan_to_users_table
```

If you prefer using a model accesor, add the following method to your `User` model:

```php
public function getPlanAttribute()
{
    return 'basic';
}
```

In the migration file, add the `plan` field, like so:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('plan')->default('basic');
});
```

This field is used to determine the token limit plan associated with each user. This setup is optional but recommended for full functionality.

## Usage

### Basic Usage

First, remember to add the `HasTokenUsage` trait to your model. This trait can be used with any model.

```php
namespace App\Models;

use Cloudstudio\TokenUsage\Traits\TokenUsageTrait;

class User extends Authenticatable
{
    use TokenUsageTrait;
    // Other model methods and properties
}
```

With this setup, you are ready to work with tokens. Here are some examples:

```php
// Access a model record
$project = Project::first();

// Add tokens
$project->addTokens(3);

// Remove tokens
$project->removeTokens(3);

// Reset tokens
$project->resetTokens();

// Show token records
$tokensToday = $project->dailyTokens();
$tokensThisWeek = $project->weeklyTokens();
$tokensThisMonth = $project->monthlyTokens();
$tokensThisYear = $project->yearlyTokens();

// Show token usage sum
$tokensUsedToday = $project->dailyTokens()->sum("tokens_used");
```

For blade directives, middleware and form you can use 2 parameters.

1º Parameter: The name of the model. (video, user).
2º Parameter: The name of the plan. (daily, weekly, monthly, yearly).

This information is stored in the config file.

All records have user_id assigned, so if you use directly user as model, you can get all records of the user for all models, so you can know how many tokens have been used by the user, globally.

### Blade Directive

Easily check for token availability in Blade templates:

```php
@hasTokenUsage('video', 'daily')
    I have tokens
@endHasTokenUsage
```

### Route Middleware

Protect routes using token usage middleware:

```php
Route::get('/test-token-usage', function () {
    return response()->json(['message' => 'You have tokens available!']);
})->middleware('check-token-usage:video,daily');
```

### Form Request Validation

Validate token usage in form requests:

```php
$request->validate([
    'field' => [new \Cloudstudio\TokenUsage\Rules\TokenUsageRule('video', 'daily')],
]);
```

## Testing

Run tests using Pest PHP:

```bash
pest
```

## Changelog, Contributing, and Security

- [Changelog](CHANGELOG.md)
- [Contributing](CONTRIBUTING.md)

## Credits

- [Toni Soriano](https://github.com/cloudstudio)

## License

This package is open-sourced software licensed under the [MIT License](LICENSE.md).
