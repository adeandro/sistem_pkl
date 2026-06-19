<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'board')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/admin/teachers', \App\Livewire\Admin\TeacherManager::class)->name('admin.teachers');
    Route::get('/admin/print-letter', \App\Livewire\Admin\PrintLetter::class)->name('admin.print-letter');
    Route::get('/admin/export', function() {
        return Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PlacementsExport, 'Data_PKL.xlsx');
    })->name('admin.export');
});

require __DIR__.'/settings.php';
