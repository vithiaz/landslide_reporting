<?php

use App\Http\Livewire\Homepage;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\TelegramUserPage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Homepage::class)->name('homepage');
Route::get('/telegram-user', TelegramUserPage::class)->name('telegram-user');