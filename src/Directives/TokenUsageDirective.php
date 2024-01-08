<?php

namespace Cloudstudio\TokenUsage\Directives;

use Illuminate\Support\Facades\Blade;
use Cloudstudio\TokenUsage\Services\TokenLimitService;
use Illuminate\Support\Facades\Config;

class TokenUsageDirective
{
    public static function register()
    {
        Blade::directive('hasTokenUsage', function ($expression) {
            [$modelName, $period] = explode(',', str_replace(['(', ')', ' ', "'"], '', $expression));

            // Obtener el nombre de la clase completa del modelo usando el mapeo
            $modelClass = Config::get("token-usage.model_mappings.{$modelName}");

            if (!$modelClass) {
                throw new \Exception("Model mapping for '{$modelName}' not found.");
            }

            // Retorna una expresión PHP completa que se evaluará en la vista
            return "<?php if (resolve(Cloudstudio\\TokenUsage\\Services\\TokenLimitService::class)->hasTokensAvailable(new {$modelClass}, '{$period}')): ?>";
        });

        Blade::directive('endHasTokenUsage', function () {
            return "<?php endif; ?>";
        });
    }
}
