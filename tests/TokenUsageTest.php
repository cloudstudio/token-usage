<?php

use Mockery as m;

it('adds tokens correctly', function () {
    $project = m::mock('Project');
    $project->shouldReceive('addTokens')->with(3)->once();
    $project->shouldReceive('getAttribute')->with('tokens')->andReturn(3);

    $project->addTokens(3);
    expect($project->getAttribute('tokens'))->toBe(3);
});

it('removes tokens correctly', function () {
    $project = m::mock('Project');
    $project->shouldReceive('removeTokens')->with(3)->once();
    $project->shouldReceive('getAttribute')->with('tokens')->andReturn(2);

    $project->removeTokens(3);
    expect($project->getAttribute('tokens'))->toBe(2);
});

it('resets tokens correctly', function () {
    $project = m::mock('Project');
    $project->shouldReceive('resetTokens')->once();
    $project->shouldReceive('getAttribute')->with('tokens')->andReturn(0);

    $project->resetTokens();
    expect($project->getAttribute('tokens'))->toBe(0);
});
