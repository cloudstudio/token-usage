<?php

namespace Cloudstudio\TokenUsage\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cloudstudio\TokenUsage\Services\TokenLimitService;
use Cloudstudio\TokenUsage\Services\TokenRetrievalService;
use Illuminate\Support\Facades\Config;

class CheckTokenUsage
{
    protected $tokenLimitService;
    protected $tokenRetrievalService;

    public function __construct(TokenLimitService $tokenLimitService, TokenRetrievalService $tokenRetrievalService)
    {
        $this->tokenLimitService = $tokenLimitService;
        $this->tokenRetrievalService = $tokenRetrievalService;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $modelName
     * @param string $period
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $modelName, string $period)
    {
        $userId = $request->user()->id;
        $userPlan = $request->user()->plan ?? Config::get('token-usage.default_plan', 'basic');

        // Comprobación de límites globales de usuario
        if ($this->hasUserExceededGlobalTokenLimit($userId, $period, $userPlan)) {
            return response()->json(['error' => 'Global token limit for user reached'], 429);
        }

        // Comprobaciones existentes...
        $modelClass = $this->tokenLimitService->getModelClass($modelName);
        if (!$modelClass) {
            return response()->json(['error' => 'Invalid model name'], 400);
        }

        $modelInstance = new $modelClass;

        if (!$this->tokenLimitService->hasTokensAvailable($modelInstance, $period)) {
            return response()->json(['error' => 'Token limit reached for this period'], 429);
        }

        return $next($request);
    }

    /**
     * Check if the user has exceeded the global token limit for the given period.
     *
     * @param int $userId
     * @param string $period
     * @return bool
     */
    protected function hasUserExceededGlobalTokenLimit($userId, string $period, string $plan): bool
    {
        $userGlobalLimits = Config::get("token-usage.plans.$plan.model_limits.user.$period");

        if (!$userGlobalLimits) {
            return false;
        }

        $tokensUsed = $this->tokenRetrievalService->{"global" . ucfirst($period) . "Tokens"}($userId)->sum('tokens_used');

        return $tokensUsed >= $userGlobalLimits;
    }
}
