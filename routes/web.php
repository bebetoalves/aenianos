<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShowLinkController;
use App\Http\Controllers\ShowPostController;
use App\Http\Controllers\ShowProjectController;
use App\Http\Livewire\ListPosts;
use App\Http\Livewire\ListProjects;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('posts', ListPosts::class)->name('posts');
Route::get('posts/{post}', ShowPostController::class)->name('posts.show');

Route::get('projects', ListProjects::class)->name('projects');
Route::get('projects/{project}', ShowProjectController::class)->name('projects.show');

Route::get('links/{link}', ShowLinkController::class)->name('links.show');

Route::get('faq', FaqController::class)->name('faq');
Route::get('about', AboutController::class)->name('about');
