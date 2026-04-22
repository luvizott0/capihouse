<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ViewErrorBag;

test('comment field renders max counter at bottom by default', function () {
    $html = Blade::render('<x-forms.comment-field max="500" />', ['errors' => new ViewErrorBag]);

    expect($html)
        ->toContain('ml-auto text-end text-xs font-light')
        ->not->toContain('shrink-0 pt-1 text-end text-xs font-light');
});

test('comment field renders max counter on the side when requested', function () {
    $html = Blade::render('<x-forms.comment-field max="500" counter-position="side" />', ['errors' => new ViewErrorBag]);

    expect($html)
        ->toContain('shrink-0 pt-1 text-end text-xs font-light')
        ->not->toContain('ml-auto text-end text-xs font-light');
});
