<?php

declare(strict_types=1);

use App\Orchid\Screens;

use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', Screens\PlatformScreen::class)
    ->name('platform.main');

/* Video Management Screens */
Route::screen('videos', Screens\Videos\VideoListScreen::class)
    ->name('platform.videos')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Videos'), route('platform.videos')));

Route::screen('videos/{video}/edit', Screens\Videos\VideoEditScreen::class)
    ->name('platform.videos.edit')
    ->breadcrumbs(fn(Trail $trail, $video) => $trail
        ->parent('platform.videos')
        ->push($video->title, route('platform.videos.edit', $video)));


/* Media Management Screens */
Route::screen('media', Screens\Media\MediaListScreen::class)
    ->name('platform.media')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Media'), route('platform.media')));

Route::screen('media/{media}/edit', Screens\Media\MediaEditScreen::class)
    ->name('platform.media.edit')
    ->breadcrumbs(fn(Trail $trail, $media) => $trail
        ->parent('platform.media')
        ->push($media->title, route('platform.media.edit', $media)));

/* Channel Management Screens */
Route::screen('channels', Screens\Channels\ChannelListScreen::class)
    ->name('platform.channels')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Channels'), route('platform.channels')));

Route::screen('channels/{channel}/edit', Screens\Channels\ChannelEditScreen::class)
    ->name('platform.channels.edit')
    ->breadcrumbs(fn(Trail $trail, $channel) => $trail
        ->parent('platform.channels')
        ->push($channel->name, route('platform.channels.edit', $channel)));
/* User Management Screens */

// Platform > Profile
Route::screen('profile', Screens\User\UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', Screens\User\UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn(Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', Screens\User\UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', Screens\User\UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', Screens\Role\RoleListScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn(Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', Screens\Role\RoleListScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', Screens\Role\RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));
