<?php

use Illuminate\Support\Facades\Blade;

test('emoji picker renders target and custom label', function () {
    $html = Blade::render('<x-forms.emoji-picker target="emoji" button-label="Selecionar" />');
    expect($html)
        ->toContain('data-emoji-picker="true"')
        ->toContain('data-emoji-target="emoji"')
        ->toContain('Selecionar');
});
