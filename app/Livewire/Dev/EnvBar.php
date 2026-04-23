<?php

namespace App\Livewire\Dev;

use Livewire\Attributes\Computed;
use Livewire\Component;

class EnvBar extends Component
{
    #[Computed]
    public function branch(): string
    {
        return trim(shell_exec('git branch --show-current') ?: 'unknown');
    }

    #[Computed]
    public function tag(): string
    {
        return trim(shell_exec('git describe --tags --abbrev=0') ?: '');
    }

    public function render(): string
    {
        return <<<'blade'
            <div class="flex items-center gap-x-4">
                <span>Branch: {{ $this->branch() }}</span>

                @if($this->tag())
                    <span>{{ $this->tag() }}</span>
                @endif
            </div>
        blade;
    }
}
