<?php

namespace Cloudstudio\TokenUsage\Models;

use Illuminate\Database\Eloquent\Model;
use Cloudstudio\TokenUsage\Scopes\UserScope;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TokenUsage extends Model
{
    use HasUuids;

    protected $table = 'token_usage';

    protected $fillable = [
        'model_type', 'model_id', 'tokens_used', 'user_id'
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
