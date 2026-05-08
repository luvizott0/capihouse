<?php

use App\Livewire\App\Profile\Card;
use App\Livewire\App\Profile\ImageUploadCropper;
use App\Livewire\App\Profile\PersonalInfo;
use App\Models\Interest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('authenticated user can see own profile header with edit action', function () {
    $user = User::factory()->create([
        'name' => 'Lucas Oliveira',
        'username' => 'alukinha',
        'avatar_url' => 'https://example.com/avatar.jpg',
        'banner_url' => 'https://example.com/banner.jpg',
    ]);

    $post = Post::factory()->for($user)->create([
        'content' => 'Post do meu perfil',
    ]);
    $post->feeling()->create([
        'name' => 'Feliz',
        'emoji' => '🙂',
    ]);

    $this->actingAs($user)
        ->get(route('profile'))
        ->assertOk()
        ->assertSee('Lucas Oliveira')
        ->assertSee('@alukinha')
        ->assertSee('Editar banner')
        ->assertSee('Editar foto de perfil')
        ->assertSee('profile::open-banner-modal')
        ->assertSee('profile::open-avatar-modal')
        ->assertSee('https://example.com/banner.jpg')
        ->assertSee('https://example.com/avatar.jpg')
        ->assertSee('Post do meu perfil');
});

test('authenticated user can see another user profile without edit action', function () {
    $viewer = User::factory()->create();
    $profileOwner = User::factory()->create([
        'name' => 'Ana Souza',
        'username' => 'ana.souza',
    ]);

    $post = Post::factory()->for($profileOwner)->create([
        'content' => 'Conteudo da Ana',
    ]);
    $post->feeling()->create([
        'name' => 'Animada',
        'emoji' => '😄',
    ]);

    $this->actingAs($viewer)
        ->get(route('profile.show', $profileOwner->username))
        ->assertOk()
        ->assertSee('Ana Souza')
        ->assertSee('@ana.souza')
        ->assertDontSee('Editar banner')
        ->assertDontSee('Editar foto de perfil')
        ->assertDontSee('profile::open-banner-modal')
        ->assertDontSee('profile::open-avatar-modal')
        ->assertSee('Conteudo da Ana');
});

test('profile page shows user interests', function () {
    $viewer = User::factory()->create();
    $profileOwner = User::factory()->create();

    $interestA = Interest::factory()->create(['name' => 'capivaras']);
    $interestB = Interest::factory()->create(['name' => 'musica']);

    $profileOwner->interests()->attach([$interestA->id, $interestB->id]);

    $this->actingAs($viewer)
        ->get(route('profile.show', $profileOwner->username))
        ->assertOk()
        ->assertSee('#capivaras')
        ->assertSee('#musica');
});

test('owner sees interest input placeholder and add button in profile card', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile'))
        ->assertOk()
        ->assertSee('placeholder="interesse"', false)
        ->assertSee('Adicionar interesse')
        ->assertSee('wire:click="addInterest"', false);
});

test('owner can add and remove interests', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Livewire::test(PersonalInfo::class, [
        'user' => $user,
        'isOwner' => true,
    ]);

    $component
        ->set('interest', 'games')
        ->call('addInterest')
        ->assertHasNoErrors();

    $interestId = $user->refresh()->interests()->firstOrFail()->id;

    expect($user->interests()->where('name', 'games')->exists())->toBeTrue();

    $component
        ->call('removeInterest', $interestId)
        ->assertHasNoErrors();

    expect($user->refresh()->interests()->where('name', 'games')->exists())->toBeFalse();
});

test('owner can update banner and avatar directly from profile card component', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(ImageUploadCropper::class, [
        'user' => $user,
        'type' => 'banner',
    ])->assertHasNoErrors();

    Livewire::test(ImageUploadCropper::class, [
        'user' => $user,
        'type' => 'avatar',
    ])->assertHasNoErrors();
});

test('owner can edit bio and birth directly from personal info card', function () {
    $user = User::factory()->create([
        'bio' => 'Bio antiga',
        'birth' => '2000-01-01',
    ]);

    $this->actingAs($user);

    Livewire::test(PersonalInfo::class, [
        'user' => $user,
        'isOwner' => true,
    ])
        ->call('startEditingBio')
        ->set('bio', 'Nova bio')
        ->call('saveBio')
        ->assertHasNoErrors()
        ->call('startEditingBirth')
        ->set('birth', '2001-02-03')
        ->call('saveBirth')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->bio)->toBe('Nova bio');
    expect($user->birth?->format('Y-m-d'))->toBe('2001-02-03');
});

test('guests are redirected when trying to open user profile page', function () {
    $profileOwner = User::factory()->create();

    $this->get(route('profile.show', $profileOwner->username))
        ->assertRedirect(route('login'));
});

test('owner can update name and username via settings modal', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'username' => 'oldusername',
    ]);

    $this->actingAs($user);

    Livewire::test(Card::class, [
        'user' => $user,
        'isOwner' => true,
    ])
        ->set('settingsName', 'New Name')
        ->set('settingsUsername', 'newusername')
        ->call('saveSettings')
        ->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toBe('New Name');
    expect($user->username)->toBe('newusername');
});

test('owner can update password via settings modal', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Card::class, [
        'user' => $user,
        'isOwner' => true,
    ])
        ->set('settingsCurrentPassword', 'password')
        ->set('settingsPassword', 'NewPassword1!')
        ->set('settingsPasswordConfirmation', 'NewPassword1!')
        ->call('saveSettings')
        ->assertHasNoErrors();

    expect(Hash::check('NewPassword1!', $user->refresh()->password))->toBeTrue();
});

test('settings modal returns validation error for wrong current password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Card::class, [
        'user' => $user,
        'isOwner' => true,
    ])
        ->set('settingsCurrentPassword', 'wrongpassword')
        ->set('settingsPassword', 'NewPassword1!')
        ->set('settingsPasswordConfirmation', 'NewPassword1!')
        ->call('saveSettings')
        ->assertHasErrors(['settingsCurrentPassword']);
});

test('non-owner cannot save settings', function () {
    $owner = User::factory()->create(['name' => 'Real Owner']);
    $viewer = User::factory()->create();

    $this->actingAs($viewer);

    Livewire::test(Card::class, [
        'user' => $owner,
        'isOwner' => false,
    ])
        ->set('settingsName', 'Hacked Name')
        ->call('saveSettings');

    expect($owner->refresh()->name)->toBe('Real Owner');
});
