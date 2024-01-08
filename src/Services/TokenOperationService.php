<?php

namespace Cloudstudio\TokenUsage\Services;

use Cloudstudio\TokenUsage\Models\TokenUsage;

/**
 * Class TokenOperationService
 *
 * @package Cloudstudio\TokenUsage\Services
 */
class TokenOperationService
{
    protected $tokenUsageModel;

    /**
     * TokenOperationService constructor.
     *
     * @param TokenUsage $tokenUsage
     */
    public function __construct(TokenUsage $tokenUsage)
    {
        $this->tokenUsageModel = $tokenUsage;
    }

    /**
     * Add tokens to the model.
     *
     * @param $model
     * @param int $tokens
     */
    public function addTokens($model, int $tokens)
    {
        $this->tokenUsageModel->create([
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'tokens_used' => $tokens,
            'user_id' => $model->user_id
        ]);
    }

    /**
     * Remove tokens from the model.
     *
     * @param $model
     * @param int $tokens
     */
    public function removeTokens($model, int $tokens)
    {
        $this->tokenUsageModel->create([
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'tokens_used' => -$tokens,
            'user_id' => $model->user_id
        ]);
    }

    /**
     * Reset tokens for the model.
     *
     * @param $model
     */
    public function resetTokens($model)
    {
        $this->tokenUsageModel->where('model_type', get_class($model))
            ->where('model_id', $model->getKey())
            ->where('user_id', $model->user_id)
            ->delete();
    }
}
