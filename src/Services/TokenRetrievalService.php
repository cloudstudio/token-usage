<?php

namespace Cloudstudio\TokenUsage\Services;

use Cloudstudio\TokenUsage\Models\TokenUsage;
use Carbon\Carbon;

class TokenRetrievalService
{
    protected $tokenUsageModel;

    /**
     * Constructor for the TokenRetrievalService.
     * 
     * @param TokenUsage $tokenUsage The TokenUsage model instance.
     */
    public function __construct(TokenUsage $tokenUsage)
    {
        $this->tokenUsageModel = $tokenUsage;
    }

    /**
     * Retrieve tokens used for a given model on the current day.
     * 
     * @param string $modelType The model type.
     * @param int|null $modelId The model ID (optional).
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function dailyTokens($modelType, $modelId = null)
    {
        return $this->buildQuery($modelType, $modelId)
            ->whereDate('created_at', Carbon::today())
            ->get();
    }

    /**
     * Retrieve tokens used for a given model in the current week.
     * 
     * @param string $modelType The model type.
     * @param int|null $modelId The model ID (optional).
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function weeklyTokens($modelType, $modelId = null)
    {
        return $this->buildQuery($modelType, $modelId)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->get();
    }

    /**
     * Retrieve tokens used for a given model in the current month.
     * 
     * @param string $modelType The model type.
     * @param int|null $modelId The model ID (optional).
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function monthlyTokens($modelType, $modelId = null)
    {
        return $this->buildQuery($modelType, $modelId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->get();
    }

    /**
     * Retrieve tokens used for a given model in the current year.
     * 
     * @param string $modelType The model type.
     * @param int|null $modelId The model ID (optional).
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function yearlyTokens($modelType, $modelId = null)
    {
        return $this->buildQuery($modelType, $modelId)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();
    }

    /**
     * Retrieve global tokens used by a user on the current day.
     * 
     * @param int $userId The user ID.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function globalDailyTokens($userId)
    {
        return $this->buildGlobalQuery($userId)
            ->whereDate('created_at', Carbon::today())
            ->get();
    }

    /**
     * Retrieve global tokens used by a user in the current week.
     * 
     * @param int $userId The user ID.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function globalWeeklyTokens($userId)
    {
        return $this->buildGlobalQuery($userId)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->get();
    }

    /**
     * Retrieve global tokens used by a user in the current month.
     * 
     * @param int $userId The user ID.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function globalMonthlyTokens($userId)
    {
        return $this->buildGlobalQuery($userId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->get();
    }

    /**
     * Retrieve global tokens used by a user in the current year.
     * 
     * @param int $userId The user ID.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function globalYearlyTokens($userId)
    {
        return $this->buildGlobalQuery($userId)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();
    }

    /**
     * Build the query based on the model type and model ID.
     * 
     * @param string $modelType The model type.
     * @param int|null $modelId The model ID (optional).
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildQuery($modelType, $modelId = null)
    {
        $query = $this->tokenUsageModel->where('model_type', $modelType);
        $query = $query->where('user_id', auth()->user()->id);

        if ($modelId !== null) {
            $query->where('model_id', $modelId);
        }

        return $query;
    }

    /**
     * Build the query for global token usage based on the user ID.
     * 
     * @param int $userId The user ID.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildGlobalQuery($userId)
    {
        return $this->tokenUsageModel->where('user_id', $userId);
    }
}
