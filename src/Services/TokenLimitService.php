<?php

namespace Cloudstudio\TokenUsage\Services;

use Illuminate\Support\Facades\Config;

class TokenLimitService
{
    /**
     * Check if the model has tokens available for the given period.
     *
     * @param mixed $model
     * @param string $period
     * @return bool
     */
    public function hasTokensAvailable($model, string $period): bool
    {
        $modelName = $this->getModelName($model);
        $plan = $this->getUserPlan();
        $config = $this->getModelConfig($modelName, $plan);

        if (!$config) {
            return true; // No specific limit defined for this model under the plan
        }

        return $this->checkTokenLimit($model, $config, $period);
    }

    /**
     * Get the model name from the model instance.
     *
     * @param mixed $model
     * @return string|null
     */
    protected function getModelName($model): ?string
    {
        return array_search(get_class($model), Config::get('token-usage.model_mappings', []));
    }

    /**
     * Get the plan of the authenticated user or the default plan.
     *
     * @return string
     */
    protected function getUserPlan(): string
    {
        return auth()->user()->plan ?? Config::get('token-usage.default_plan', 'basic');
    }

    /**
     * Get the configuration for a model under a specific plan.
     *
     * @param string|null $modelName
     * @param string $plan
     * @return array|null
     */
    protected function getModelConfig(?string $modelName, string $plan): ?array
    {
        return Config::get("token-usage.plans.$plan.model_limits.$modelName");
    }

    /**
     * Check if the tokens used are within the limit for a specific period.
     *
     * @param mixed $model
     * @param array $config
     * @param string $period
     * @return bool
     */
    protected function checkTokenLimit($model, array $config, string $period): bool
    {
        $limit = $config[$period] ?? 0;
        $tokensUsed = $model->{'get' . ucfirst($period) . 'Tokens'}()->sum('tokens_used');

        return $tokensUsed < $limit;
    }

    /**
     * Convert model name to its corresponding class.
     *
     * @param string $modelName
     * @return string|null
     */
    public function getModelClass(string $modelName): ?string
    {
        return Config::get('token-usage.model_mappings')[$modelName] ?? null;
    }
}
