<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::middleware('auth:sanctum')->delete('logout', 'logout');
    Route::post('login', 'auth');
    Route::get('/user', 'currentUser')->middleware('auth:sanctum');
});

Route::controller(UserController::class)->prefix('users')->group(function() {
    Route::middleware('auth:sanctum')->get('', 'index');
    Route::post('', 'store');
});


Route::middleware('auth:sanctum')->controller(NoteController::class)->prefix('notes')->group(function () {
    Route::get('', 'index');
    Route::post('create', 'store');
    Route::post('add_collaborators/{note}', 'addCollaborators')->whereUuid('note');
    Route::get('collaborators/{note}', 'getAvailableCollaborators')->whereUuid('note');
    Route::delete('collaborators/{note}/{user}', 'destroyCollaborator')->whereUuid('note', 'user');
    Route::patch('update/{note}', 'update')->whereUuid('note');
    Route::get('{note}', 'show')->whereUuid('note');
    Route::delete('{note}/{tag}', 'deleteTag')->whereUuid('note', 'tag');
    Route::delete('{note}', 'destroy')->whereUuid('note');
});

Route::middleware('auth:sanctum')->controller(TagController::class)->prefix('tags')->group(function () {
    Route::get('', 'index');
    Route::post('create', 'store');
    Route::get('find/{tag}', 'show')->whereUuid('tag');
});
