<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Docs;



Route::get('docs', [Docs::class, 'how_to_install'])->name('docs');
Route::get('docs/install', [Docs::class, 'how_to_install'])->name('docs.install');
Route::get('docs/settings', [Docs::class, 'settings'])->name('docs.settings');
Route::get('docs/live-users', [Docs::class, 'live_users'])->name('docs.live.users');
Route::get('docs/domains', [Docs::class, 'domains'])->name('docs.domains');
Route::get("docs/heatmaps", [Docs::class, 'heatmaps'])->name('docs.heatmaps');
Route::get("docs/recordings", [Docs::class, 'recordings'])->name('docs.recordings');
Route::get("docs/user", [Docs::class, 'user'])->name('docs.user');
Route::get("docs/package", [Docs::class, 'package'])->name('docs.package');
Route::get("docs/transaction", [Docs::class, 'transaction'])->name('docs.transaction');
Route::get("docs/update", [Docs::class, 'update'])->name('docs.update');


?>