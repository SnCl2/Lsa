<?php

use App\Models\Work;

test('work model has fillable valuation fields', function () {
    $work = new Work([
        'actual_value' => '1250000.50',
        'realised_value' => '1100000.00',
        'fair_market_value' => '1200000.25',
    ]);

    expect($work->actual_value)->toBe('1250000.50')
        ->and($work->realised_value)->toBe('1100000.00')
        ->and($work->fair_market_value)->toBe('1200000.25');
});

test('work model casts valuation fields correctly', function () {
    $work = new Work();
    
    // Test casts are set
    expect($work->hasCast('actual_value', 'decimal'))->toBeTrue()
        ->and($work->hasCast('realised_value', 'decimal'))->toBeTrue()
        ->and($work->hasCast('fair_market_value', 'decimal'))->toBeTrue();
});
