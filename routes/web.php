<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'board')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/admin/export', function() {
        return Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PlacementsExport, 'Data_PKL.xlsx');
    })->name('admin.export');
});

require __DIR__.'/settings.php';
