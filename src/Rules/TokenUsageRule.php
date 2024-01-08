<?php

namespace Cloudstudio\TokenUsage\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Cloudstudio\TokenUsage\Services\TokenLimitService;

class TokenUsageRule implements ValidationRule
{
    protected $tokenLimitService;
    protected $modelName;
    protected $period;

    /**
     * TokenUsageRule constructor.
     *
     * @param $modelName
     * @param $period
     */
    public function __construct($modelName, $period)
    {
        $this->tokenLimitService = resolve(TokenLimitService::class);
        $this->modelName = $modelName;
        $this->period = $period;
    }

    /**
     * Validate the given value.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $modelClass = $this->tokenLimitService->getModelClass($this->modelName);

        if (!$modelClass) {
            $fail("Invalid model name: $this->modelName.");
            return;
        }

        $modelInstance = new $modelClass;

        if (!$this->tokenLimitService->hasTokensAvailable($modelInstance, $this->period)) {
            $fail("The $this->period token limit has been reached for $this->modelName.");
        }
    }
}
