<?php

it('correctly defines model mappings', function () {
    $mappings = config('token-usage.model_mappings');
    expect($mappings)->toBeArray();
    expect($mappings)->toHaveKey('user');
    expect($mappings['user'])->toEqual(\App\Models\User::class);
});

it('correctly defines plans', function () {
    $plans = config('token-usage.plans');
    expect($plans)->toBeArray();
    expect($plans)->toHaveKey('basic');
    expect($plans['basic']['model_limits']['user'])->toBeArray();
    expect($plans['basic']['model_limits']['user']['daily'])->toEqual(1);
});
