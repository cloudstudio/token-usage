<?php

namespace Cloudstudio\TokenUsage\Models;

use Illuminate\Database\Eloquent\Model;
use Cloudstudio\TokenUsage\Scopes\UserScope;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class TokenUsage extends Model
{
    use HasUuids;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'token_usage';

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'model_type', 'model_id', 'tokens_used', 'user_id'
    ];

    /**
     * Get the owning model of the token usage.
     */
    public function model()
    {
        return $this->morphTo();
    }
}
