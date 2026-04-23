<?php

use Illuminate\Support\Facades\Blade;

test('emoji picker renders emoji label by default', function () {
    $emoji = "\u{1F642}";

    $html = Blade::render('<x-forms.emoji-picker target="emoji" />');

    expect($html)
        ->toContain('x-text="label"')
        ->toContain($emoji);
});

test('emoji picker renders target and custom label', function () {
    $html = Blade::render('<x-forms.emoji-picker target="emoji" button-label="Selecionar" />');

    expect($html)
        ->toContain('data-emoji-picker="true"')
        ->toContain('data-emoji-target="emoji"')
        ->toContain('Selecionar');
});
