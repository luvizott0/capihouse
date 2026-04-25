<?php

namespace App\Livewire\App\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class OnlineSidebar extends Component
{
    public bool $mobile = false;

    /**
     * @return array<int, int>
     */
    public function getOnlineUserIds(): array
    {
        return DB::table('sessions')
            ->whereNotNull('user_id')
            ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
            ->pluck('user_id')
            ->all();
    }

    public function render(): View
    {
        $onlineUserIds = $this->getOnlineUserIds();

        $siteUsers = User::query()
            ->select(['id', 'name', 'username'])
            ->orderBy('name')
            ->get();

        return view('livewire.app.users.online-sidebar', [
            'onlineUserIds' => $onlineUserIds,
            'siteUsers' => $siteUsers,
        ]);
    }
}
