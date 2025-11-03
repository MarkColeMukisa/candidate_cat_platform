<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateController;

Route::get('/', function () { return redirect()->route('candidates.index'); });

Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
Route::get('/candidates/create', [CandidateController::class, 'create'])->name('candidates.create');
Route::post('/candidates', [CandidateController::class, 'store'])->name('candidates.store');
Route::get('/candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');
