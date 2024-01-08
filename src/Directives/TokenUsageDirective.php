<?php

namespace Cloudstudio\TokenUsage\Directives;

use Illuminate\Support\Facades\Blade;
use Cloudstudio\TokenUsage\Services\TokenLimitService;

class TokenUsageDirective
{
    public static function register()
    {
        Blade::directive('hasTokenUsage', function ($expression) {
            [$modelName, $period] = explode(',', str_replace(['(', ')', ' ', "'"], '', $expression));

            // Retorna una expresión PHP completa que se evaluará en la vista
            return "<?php if (resolve(Cloudstudio\\TokenUsage\\Services\\TokenLimitService::class)->hasTokensAvailable(new {$modelName}, '{$period}')): ?>";
        });

        Blade::directive('endHasTokenUsage', function () {
            return "<?php endif; ?>";
        });
    }
}
