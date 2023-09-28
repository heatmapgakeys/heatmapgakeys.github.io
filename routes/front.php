<?php
use App\Http\Controllers\Front;


Route::any('/policy/privacy', [Front::class,'policy_privacy'])->name('policy-privacy');
Route::any('/policy/terms', [Front::class,'policy_terms'])->name('policy-terms');
Route::any('/policy/refund', [Front::class,'policy_refund'])->name('policy-refund');
Route::any('/policy/gdpr', [Front::class,'policy_gdpr'])->name('policy-gdpr');
Route::any('/pricing', [Front::class,'pricing_plan'])->name('pricing-plan');
Route::any('/accept-cookie', [Front::class,'accept_cookie'])->name('accept-cookie');

Route::any('/blog', [Front::class,'blog_list'])->name('list-blog');
Route::get('/blog/create', [Front::class,'create_blog'])->middleware(['auth'])->name('create-blog');
Route::post('/blog/save', [Front::class,'save_blog'])->middleware(['auth'])->name('save-blog');
Route::get('/blog/update/{blog_id}', [Front::class,'update_blog'])->middleware(['auth'])->name('update-blog');
Route::post('/blog/delete', [Front::class,'delete_blog'])->middleware(['auth'])->name('delete-blog');
Route::get('/blog/dashboard', [Front::class,'dashboard'])->middleware(['auth'])->name('dashboard-blog');
Route::post('/blog/comment/reply', [Front::class,'comment_reply'])->middleware(['auth'])->name('reply-comment');
Route::post('/blog/comment/hide', [Front::class,'comment_hide'])->middleware(['auth'])->name('hide-comment');
Route::post('/blog/comment/seen', [Front::class,'comment_seen'])->middleware(['auth'])->name('seen-comment');

//These routes needed to be at the bottom
Route::get('/blog/comment/{comment_id}', [Front::class,'comment_single'])->name('single-comment');
Route::get('/blog/{blog_slug}', [Front::class,'blog_single'])->name('single-blog');
