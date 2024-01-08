<?php

namespace Cloudstudio\TokenUsage\Traits;

use Cloudstudio\TokenUsage\Services\TokenOperationService;
use Cloudstudio\TokenUsage\Services\TokenRetrievalService;

trait TokenUsageTrait
{
    /**
     * Add tokens to the model.
     *
     * @param int $tokens
     */
    public function addTokens(int $tokens)
    {
        app(TokenOperationService::class)->addTokens($this, $tokens);
    }

    /**
     * Remove tokens from the model.
     *
     * @param int $tokens
     */
    public function removeTokens(int $tokens)
    {
        app(TokenOperationService::class)->removeTokens($this, $tokens);
    }

    /**
     * Reset tokens for the model.
     */
    public function resetTokens()
    {
        app(TokenOperationService::class)->resetTokens($this);
    }

    /**
     * Get daily tokens for the model (static context).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getDailyTokens()
    {
        return app(TokenRetrievalService::class)->dailyTokens(static::class);
    }

    /**
     * Get weekly tokens for the model (static context).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getWeeklyTokens()
    {
        return app(TokenRetrievalService::class)->weeklyTokens(static::class);
    }

    /**
     * Get monthly tokens for the model (static context).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getMonthlyTokens()
    {
        return app(TokenRetrievalService::class)->monthlyTokens(static::class);
    }

    /**
     * Get yearly tokens for the model (static context).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getYearlyTokens()
    {
        return app(TokenRetrievalService::class)->yearlyTokens(static::class);
    }

    /**
     * Get daily tokens for this model instance.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function dailyTokens()
    {
        return app(TokenRetrievalService::class)->dailyTokens(get_class($this), $this->getKey());
    }

    /**
     * Get weekly tokens for this model instance.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function weeklyTokens()
    {
        return app(TokenRetrievalService::class)->weeklyTokens(get_class($this), $this->getKey());
    }

    /**
     * Get monthly tokens for this model instance.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function monthlyTokens()
    {
        return app(TokenRetrievalService::class)->monthlyTokens(get_class($this), $this->getKey());
    }

    /**
     * Get yearly tokens for this model instance.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function yearlyTokens()
    {
        return app(TokenRetrievalService::class)->yearlyTokens(get_class($this), $this->getKey());
    }
}
