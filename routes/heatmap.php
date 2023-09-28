<?php 
	use App\Http\Controllers\Home;
	use App\Http\Controllers\Heatmap\Domain;
	use App\Http\Controllers\Heatmap\Jscontroller;
	use App\Http\Controllers\Heatmap\Dashboard;
	use App\Http\Controllers\Heatmap\Cron;


	Route::post('generate/embed_code',[Domain::class,'generate_embed_code'])->middleware(['auth'])->name('generate-embed-code');
	Route::get('domain/list',[Domain::class,'index'])->middleware(['auth'])->name('domain-list');
	Route::post('delete/domain',[Domain::class,'delete_domain'])->middleware(['auth'])->name('delete-domain');
	Route::post('domain/get-embed-code',[Domain::class,'get_js_embed_code'])->middleware(['auth'])->name('get-js-embed-code');
	Route::post('domain/edit-domain',[Domain::class,'edit_domain'])->middleware(['auth'])->name('edit-domain');

	Route::any('analytics/get-domain-pages-list',[Domain::class,'domain_switch_action'])->middleware(['auth'])->name('get-domain-pages-list');
	Route::any('analytics/get-visit-url-information',[Domain::class,'get_visit_url_information'])->middleware(['auth'])->name('get-visit-url-information');
	Route::any('analytics/set-active-url-session',[Domain::class,'set_active_url_session'])->middleware(['auth'])->name('set-active-url-session');

	// zilani
	Route::post('analytics/rest-list',[Domain::class,'get_rest_visit_lists'])->middleware(['auth'])->name('get-rest-visit-lists');
	Route::post('analytics/video-download-link',[Domain::class,'get_video_download_link'])->middleware(['auth'])->name('get-video-download-link');
	Route::post('analytics/session-data',[Domain::class,'get_corresponding_url_session_data'])->middleware(['auth'])->name('get-corresponding-url-session-data');
	// zilani

	// Domain hit map show section
	Route::any("domain/heatmaps",[Domain::class,'domain_analytics'])->middleware(['auth'])->name("domain-analytics");
	Route::any('domain/heatmap-generator',[Domain::class,'get_screenshot_with_data'])->middleware(['auth'])->name('heatmap-generator');
	Route::get('domain/recordings',[Domain::class,'user_session_video'])->middleware(['auth'])->name('user-session-video');
	Route::post('domain/manager/set-active-domain-session',[Domain::class,'set_active_domain_session'])->middleware(['auth'])->name('set-active-domain-session');
	Route::post("domain/session/visit/url/delete",[Domain::class,'delete_session_visit_url'])->middleware(['auth'])->name("delete-session-visit-url");
	// JsController Route
	Route::post('analytics/get-ip',[Jscontroller::class,'get_ip'])->name('get-ip');
	Route::post('analytics/server-info',[Jscontroller::class,'server_info'])->name('server-info');
	Route::post('analytics/scroll-info',[Jscontroller::class,'scroll_info'])->name('scroll-info');
	Route::post('analytics/click-info',[Jscontroller::class,'click_info'])->name('click-info');
	Route::post('analytics/user_session_data',[Jscontroller::class,'user_session_data'])->name('user-session-data');
	Route::get('analytics/script-loader/{website_code}',[Jscontroller::class,'client'])->name('script-loader-function');
	Route::post('domain/session-delete',[Domain::class,'domain_session_data_delete'])->name('domain-session-data-delete');
	Route::post('domain/play-pause',[Domain::class,'play_pause_domain'])->name('play-pause-domain');

	Route::get('/dashboard/user', [Dashboard::class,'index'])->middleware(['auth'])->name('dashboard-user');
	Route::get('/dashboard', [Dashboard::class,'index'])->middleware(['auth'])->name('dashboard');
	Route::get('/dashboard/clear-cache', [Dashboard::class,'clear_cache'])->middleware(['auth'])->name('dashboard-clear-cache');
	
	Route::post('/dashboard/dashboard-data', [Dashboard::class,'get_dashboard_data'])->middleware(['auth'])->name('get-dashboard-data');
	Route::post('analytics/get-screenshot',[Jscontroller::class,'get_screenshot'])->name('get-screenshot');

	Route::get('cron/export-session-recordings',[Cron::class,'s3_export_sessionrecodings'])->name('export-session-recordings');
	Route::get('cron/export-domain-heatmaps',[Cron::class,'s3_export_heatmap_data'])->name('export-domain-heatmaps');
	Route::get('cron/domain-validity-check',[Cron::class,'domain_validity_check'])->name('domain-validity-check');

	Route::get('cron/domain-delete-action',[Cron::class,'domain_delete_action'])->name('domain-delete-action');
	Route::get('cron/user-delete-action',[Cron::class,'user_delete_action'])->name('user-delete-action');
	Route::get('cron/clean-system-logs',[Cron::class,'clean_system_logs'])->name('clean-system-logs');
	Route::get('cron/get-screenshot-for-domain',[Cron::class,'get_screenshot_for_domain'])->name('get-screenshot-for-domain');
	Route::any('cron/paypal/transaction/'.ENV('CRON_TOKEN'),[Cron::class,'get_paypal_subscriber_transaction'])->name('get-paypal-subscriber-transaction');
	Route::post('cron/language/sync/'.ENV('CRON_TOKEN'),[Cron::class,'sync_language'])->name('sync-language');
	Route::post('cron/language/delete/'.ENV('CRON_TOKEN'),[Cron::class,'delete_language'])->name('delete-language');


	Route::any('cron/subscribers/transaction',[Cron::class,'get_paypal_subscriber_transiction'])->name('get-paypal-subscriber-transiction');







 ?>