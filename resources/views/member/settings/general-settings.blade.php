@extends('layouts.auth')
@section('title',__('Settings'))
<link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">

@section('content')
    <div class="main-content container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{($is_member)?__('Integrations'):__('Settings')}} </h3>
                    <p class="text-subtitle text-muted">{{($is_member)?__('Integrate 3rd Party APIs'):__('Settings and API Integration')}}</p>
                </div>
            </div>
        </div>

        @if (session('save_agency_account_status')=='1')
            <div class="alert alert-success">
                <h4 class="alert-heading">{{__('Successful')}}</h4>
                <p> {{ __('Settings have been saved successfully.') }}</p>
            </div>
        @endif


        @if ($errors->any())
            <div class="alert alert-warning">
                <h4 class="alert-heading">{{__('Something Missing')}}</h4>
                <p> {{ __('Something is missing. Please check the the required inputs.') }}</p>
            </div>
        @endif
        @if (session('save_agency_account_minimun_one_required')=='1')
            <div class="alert alert-warning">
                <h4 class="alert-heading">{{__('No Data')}}</h4>
                <p> {{ __('You must enable at least one email account.') }}</p>
            </div>
        @endif


        <?php
        $xapp_name = $xdata->app_name ?? '';
        $email_settings = isset($xdata->email_settings) ? json_decode($xdata->email_settings) : [];

        $default_email = $email_settings->default ?? '';
        $sender_name = $email_settings->sender_name ??  $xapp_name;
        if(empty($sender_name)) $sender_name = config('app.name');

        $sender_email = $email_settings->sender_email ?? '';
        if(empty($sender_email)) $sender_email = 'no-reply@'.get_domain_only(url('/'));

        $upload_settings = isset($xdata->upload_settings) ? json_decode($xdata->upload_settings) : [];
        $upload_bot_image = $upload_settings->bot->image ?? config('app.upload.bot.image');
        $upload_bot_video = $upload_settings->bot->video ?? config('app.upload.bot.video');
        $upload_bot_audio = $upload_settings->bot->audio ?? config('app.upload.bot.audio');
        $upload_bot_file = $upload_settings->bot->file ?? config('app.upload.bot.file');
        ?>


        <section class="section">

            <form  class="form form-vertical" enctype="multipart/form-data" method="POST" action="{{ route('general-settings-action') }}">
                @csrf

                <?php
                $nav_items = [];
                if(!$is_member) array_push($nav_items, ['tab'=>true,'id'=>'general-tab','href'=>'#general','title'=>__('General'),'subtitle'=>__('Brand & Preference'),'icon'=>'fas fa-cog']);
                if(!$is_member){
                    array_push($nav_items, ['tab'=>true,'id'=>'email-tab','href'=>'#email','title'=>__('Email'),'subtitle'=>__('Integration'),'icon'=>'far fa-envelope']);
                    array_push($nav_items, ['tab'=>true,'id'=>'google-tab','href'=>'#google','title'=>__('GOOGLE API'),'subtitle'=>__('Google Api Key'),'icon'=>'fas fa-plug']);
                    array_push($nav_items, ['tab'=>true,'id'=>'ip-tab','href'=>'#ip','title'=>__('Ip '),'subtitle'=>__('Get Ip Info'),'icon'=>'fas fa-info']);
                    array_push($nav_items, ['tab'=>true,'id'=>'storage-tab','href'=>'#storage','title'=>__('S3 storage'),'subtitle'=>__('S3 storage Integration'),'icon'=>'fas fa-book']);
                     array_push($nav_items, ['tab'=>true,'id'=>'emailauto-tab','href'=>'#emailauto','title'=>__('Responder'),'subtitle'=>__('Integration'),'icon'=>'far fa-paper-plane']);
                     array_push($nav_items, ['tab'=>true,'id'=>'analytics-tab','href'=>'#analytics','title'=>__('Script'),'subtitle'=>__('Analytics Code'),'icon'=>'fas fa-code']);
                     array_push($nav_items, ['tab'=>true,'id'=>'cron-tab','href'=>'#cron','title'=>__('Cron'),'subtitle'=>__('Cron Commands'),'icon'=>'fas fa-tasks']);
                     array_push($nav_items, ['tab'=>false,'href'=>route('payment-settings',0),'title'=>__('Payment'),'subtitle'=>__('Integration'),'icon'=>'fas fa-credit-card']);
                     array_push($nav_items, ['tab'=>false,'href'=>route('agency-landing-editor'),'title'=>__('Landing'),'subtitle'=>__('Page Setup'),'icon'=>'fas fa-home']);
                   
                    
                    array_push($nav_items, ['tab'=>false,'href'=>route('languages.index'),'title'=>__('Language'),'subtitle'=>__('Multi-lingual Editor'),'icon'=>'fas fa-language']);
                    if(has_module_access($module_id_affiliate_system,$user_module_ids,$is_admin,$is_manager))
                    array_push($nav_items, ['tab'=>false,'href'=>route('affiliate-settings'),'title'=>__('Affiliate'),'subtitle'=>__('System Setup'),'icon'=>'fas fa-percent']);
                }
                ?>

                 <div class="row">
                    <div class="col-12 col-lg-2">

                        <div class="d-flex d-lg-none header-tabs align-items-stretch w-100 mb-5 mb-lg-0" id="myTab" >
                            <ul class="nav nav-tabs nav-stretch flex-nowrap w-100 h-100 myTab" role="tablist">
                                  @foreach($nav_items as $index=>$nav)
                                    <li class="nav-item flex-equal no-radius pt-1" role="presentation">
                                        <a class="nav-link d-flex flex-column text-nowrap flex-center w-100 px-2 px-lg-4 py-3 py-lg-4 text-center no-radius" href="{{$nav['href']??''}}" id="{{$nav['id']??''}}"  <?php if($nav['tab']) echo 'data-bs-toggle="tab" aria-selected="true" role="tab"'; if(isset($nav['target'])) echo 'target="'.$nav['target'].'"';?>>
                                            <span class="text-uppercase text-dark fw-bold fs-6 fs-lg-5"><i class="text-primary {{$nav['icon']??''}}"></i> {{$nav['title']}}</span>
                                            <span class="text-gray-500 fs-8 fs-lg-7 text-muted">{{$nav['subtitle']}}</span>
                                        </a>
                                    </li>
                                  @endforeach
                            </ul>
                        </div>

                        <div class="d-none d-lg-block" id="myTab2">
                            <ul class="nav nav-tabs myTab" role="tablist" aria-orientation="vertical">
                              @foreach($nav_items as $index=>$nav)
                               <li class="nav-item w-100 pt-1" role="presentation">
                                    <a class="nav-link d-flex flex-column text-nowrap flex-center w-100 ps-2 ps-lg-4 py-2 no-radius" href="{{$nav['href']??''}}" id="{{$nav['id']??''}}"  <?php if($nav['tab']) echo 'data-bs-toggle="tab" aria-selected="true" role="tab"'; if(isset($nav['target'])) echo 'target="'.$nav['target'].'"';?>>
                                        <span class="text-uppercase text-dark fw-bold fs-6 fs-lg-5"><i class="text-primary {{$nav['icon']??''}}"></i> {{$nav['title']}}</span>
                                        <span class="text-gray-500 fs-8 fs-lg-7 text-muted">{{$nav['subtitle']}}</span>
                                    </a>
                                </li>
                              @endforeach
                            </ul>
                        </div>

                    </div>

                    <div class="col-12 col-lg-10">
                        <div class="tab-content" id="myTabContent">
                            @if(!$is_member)
                                <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">
                                    <div class="card">
                                        <div class="row">
                                            @if($is_admin || ($is_agent && $agent_has_whitelabel))
                                            <div class="col-12 col-lg-8">
                                                <div class="card mb-4 no-shadow">
                                                    <div class="card-header pt-5">
                                                        <h4>{{__('Brand Settings')}} </h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-12 col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">{{ __("Company name") }} </label>
                                                                    <div class="input-group">

                                                                        <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                                                        <input name="company_name" value="{{config('settings.company_name') ?? ENV('APP_NAME')}}"  class="form-control" type="text">
                                                                    </div>
                                                                    @if ($errors->has('company_name'))
                                                                        <span class="text-danger"> {{ $errors->first('company_name') }} </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">{{ __("Company address") }} </label>
                                                                    <div class="input-group">

                                                                        <span class="input-group-text"><i class="fas fa-address-card"></i></span>
                                                                        <input name="company_address" value="{{config('settings.company_address') ?? old('company_address')}}"  class="form-control" type="text">
                                                                    </div>
                                                                    @if ($errors->has('company_address'))
                                                                        <span class="text-danger"> {{ $errors->first('company_address') }} </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">{{ __("Company Email") }} </label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                                        <input name="company_email" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo config('settings.company_email') ?? old('company_email'); ?>"  class="form-control" type="text">
                                                                    </div>
                                                                    @if ($errors->has('company_email'))
                                                                        <span class="text-danger"> {{ $errors->first('company_email') }} </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-6">
                                                                <div class="form-group">
                                                                    <label for="">{{ __("Company Mobile") }} </label>
                                                                    <div class="input-group">

                                                                        <span class="input-group-text"><i class="fas fa-mobile"></i></span>
                                                                        <input name="company_mobile" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo config('settings.company_mobile') ?? old('company_mobile'); ?>"  class="form-control" type="text">
                                                                    </div>
                                                                    @if ($errors->has('company_mobile'))
                                                                        <span class="text-danger"> {{ $errors->first('company_mobile') }} </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @if($is_agent && $agent_has_whitelabel)
                                                                <div class="col-12 col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="">{{ __("White-label Domain") }}</label>
                                                                        <div class="input-group">
                                                                            <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                                            <input name="agent_domain" placeholder="yourbotsailor.com" value="{{old('agent_domain',$xdata_user->agent_domain)}}"  class="form-control" type="text">
                                                                        </div>
                                                                        @if ($errors->has('agent_domain'))
                                                                            <span class="text-danger"> {{ $errors->first('agent_domain') }} </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif
                                                           <div class="col-12 col-lg-4">
                                                                <div class="form-group">
                                                                    <label for="">{{ __("Logo") }} </label>
                                                                    <?php $logo2  = ($logo != '') ? asset('storage/app/public/assets/logo/'.$logo) : asset('assets/images/logo.png');?>
                                                                    <img src="{{ $logo2 }}" class="mb-2 border rounded" alt="" height="70px" width="100%">
                                                                    <div class="position-relative">
                                                                        <input type="hidden" name="logo_data" value="{{ $logo2 }}">
                                                                        <input type="file" id="logo" class="form-control" name="logo" >
                                                                        @if ($errors->has('logo'))
                                                                            <span class="text-danger"> {{ $errors->first('logo') }} </span>
                                                                        @else
                                                                            <span class="small"> 1MB, 500x150px, png/jpg/webp </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-lg-4">
                                                                <div class="form-group">
                                                                      <p class="m-0 text-center">
                                                                        <label for="">{{ __("White Logo") }} </label><br>
                                                                        <?php $white_logo  = ($white_logo != '') ? asset('storage/app/public/assets/logo/'.$white_logo) : asset('assets/images/logo-white.png'); ?>
                                                                        <img src="{{ $white_logo }}" class="mb-2 border rounded text-center" alt="" height="70px">
                                                                      </p>
                                                                      <div class="position-relative">
                                                                        <input type="hidden" name="white_logo_data" value="{{ $white_logo }}">
                                                                        <input type="file" id="white_logo" class="form-control" name="white_logo" >
                                                                        @if ($errors->has('white_logo'))
                                                                            <span class="text-danger"> {{ $errors->first('white_logo') }} </span>
                                                                        @else
                                                                            <span class="small"> 100KB, 100x100px, png/jpg/webp</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-lg-4">
                                                                <div class="form-group">
                                                                      <p class="m-0 text-center">
                                                                        <label for="">{{ __("Favicon") }} </label><br>
                                                                        <?php $favicon  = ($favicon != '') ? asset('storage/app/public/assets/favicon/'.$favicon) : asset('assets/images/favicon.png'); ?>
                                                                        <img src="{{ $favicon }}" class="mb-2 border rounded text-center" alt="" height="70px">
                                                                      </p>
                                                                      <div class="position-relative">
                                                                        <input type="hidden" name="favicon_data" value="{{ $favicon }}">

                                                                        <input type="file" id="favicon" class="form-control" name="favicon" >
                                                                        @if ($errors->has('favicon'))
                                                                            <span class="text-danger"> {{ $errors->first('favicon') }} </span>
                                                                        @else
                                                                            <span class="small"> 100KB, 100x100px, png/jpg/webp</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-12">
                                                                <div class="form-group">
                                                                    <label for="">{{ __("Product Name") }} </label>
                                                                    <div class="input-group">

                                                                        <span class="input-group-text"><i class="fab fa-product-hunt"></i></span>
                                                                        <input name="product_name" value="{{config('settings.product_name') ?? old('product_name')}}"  class="form-control" type="text">
                                                                    </div>
                                                                    @if ($errors->has('product_name'))
                                                                        <span class="text-danger"> {{ $errors->first('product_name') }} </span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <div class="col-12 <?php echo ($is_admin || ($is_agent && $agent_has_whitelabel)) ? 'col-lg-4' : 'col-lg-12';?>">
                                                <div class="card mb-4 no-shadow">
                                                    <div class="card-header pt-5">
                                                        <h4>{{__('Preference')}}</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="">{{ __("Timezone") }} </label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                                                                        @php
                                                                            $selected = old('timezone', $xdata->timezone ?? '');
                                                                            if(empty($selected)) $selected = config('app.timezone');
                                                                            $timezone_list = get_timezone_list();
                                                                            echo Form::select('timezone',$timezone_list,$selected,array('class'=>'form-control select2'));
                                                                        @endphp
                                                                    </div>
                                                                    @if ($errors->has('timezone'))
                                                                        <span class="text-danger"> {{ $errors->first('timezone') }} </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="">{{ __("Locale") }} </label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                                        <?php echo Form::select('language',$language_list,old('language', $xdata->language ?? 'en'),array('class'=>'form-control'));?>
                                                                    </div>
                                                                    @if ($errors->has('language'))
                                                                        <span class="text-danger"> {{ $errors->first('language') }} </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        @if($is_agent)
                                            <div class="col-12 d-none">
                                                <div class="card no-radius">
                                                    <div class="card-header"><h4>{{ __("Agency URLs") }}</h4></div>
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <div class="input-group mb-4">
                                                                <h6>{{__('User Signup URL')}}</h6>
                                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">{{route('register')}}?at={{$user_id}}</code></pre>
                                                            </div>
                                                            <div class="input-group mb-4">
                                                                <h6>{{__('User Login URL')}}</h6>
                                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">{{route('login')}}?at={{$user_id}}</code></pre>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($is_admin)
                                <div class="tab-pane fade" id="cron" role="tabpanel" aria-labelledby="cron-tab">
                                    <div class="card no-radius">
                                    <div class="card-header"><h4>{{ __("Cron Commands") }}</h4></div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="input-group mb-4">
                                                <h5>{{__('S3 Export Session Recording (every 5 minutes)')}}</h5>
                                                <?php 
                                                    $url = route('export-session-recordings');
                                                    $url = explode('/cron/',$url);
                                                    $php_path = '';
                                                    if(function_exists('shell_exec'))
                                                        $php_path = trim(shell_exec('which php'));
                                                    if($php_path == '') $php_path = 'php';
                                                ?>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">*/5 * * * * cd {{ base_path() }} && {{ $php_path }} artisan route:call /cron/{{$url[1]}} >/dev/null 2>&1</code></pre>
                                                <span>OR</span>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">curl {{route('export-session-recordings')}} >/dev/null 2>&1</code></pre>
                                            </div>
                                            <div class="input-group mb-4">
                                                <h5>{{__('S3 Export Heatmap Data (every 6 minutes )')}}</h5>
                                                <?php 
                                                    $url = route('export-domain-heatmaps');
                                                    $url = explode('/cron/',$url);
                                                ?>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">*/6 * * * * cd {{ base_path() }} && {{ $php_path }} artisan route:call /cron/{{$url[1]}} >/dev/null 2>&1</code></pre>
                                                <span>OR</span>
                                               <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">curl {{route('export-domain-heatmaps')}} >/dev/null 2>&1</code></pre>
                                            </div>
                                            <div class="input-group mb-4">
                                                <h5>{{__('Domain Validity Check (every hour)')}}</h5>
                                                <?php 
                                                    $url = route('domain-validity-check');
                                                    $url = explode('/cron/',$url);
                                                ?>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">0 * * * * cd {{ base_path() }} && {{ $php_path }} artisan route:call /cron/{{$url[1]}} >/dev/null 2>&1</code></pre>
                                                <span>OR</span>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">curl {{route('domain-validity-check')}} >/dev/null 2>&1</code></pre>
                                            </div>
                                            <div class="input-group mb-4">
                                                <h5>{{__('Domain Delete Action (twice a day)')}}</h5>
                                                <?php 
                                                    $url = route('domain-delete-action');
                                                    $url = explode('/cron/',$url);
                                                ?>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">0 */12 * * * cd {{ base_path() }} && {{ $php_path }} artisan route:call /cron/{{$url[1]}} >/dev/null 2>&1</code></pre>
                                                <span>OR</span>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">curl {{route('domain-delete-action')}} >/dev/null 2>&1</code></pre>
                                            </div>
                                            <div class="input-group mb-4">
                                                <h5>{{__('User Delete Action (once per day)')}}</h5>
                                                <?php 
                                                    $url = route('user-delete-action');
                                                    $url = explode('/cron/',$url);
                                                ?>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">0 1 * * * cd {{ base_path() }} && {{ $php_path }} artisan route:call /cron/{{$url[1]}} >/dev/null 2>&1</code></pre>
                                                <span>OR</span>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">curl {{route('user-delete-action')}} >/dev/null 2>&1</code></pre>
                                            </div>
                                            <div class="input-group mb-4">
                                                <h5>{{__('Get Screenshot For Domain (Every 3 hour)')}}</h5>
                                                <?php 
                                                    $url = route('get-screenshot-for-domain');
                                                    $url = explode('/cron/',$url);
                                                ?>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">0 */3 * * * cd {{ base_path() }} && {{ $php_path }} artisan route:call /cron/{{$url[1]}} >/dev/null 2>&1</code></pre>
                                                <span>OR</span>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">curl {{route('get-screenshot-for-domain')}} >/dev/null 2>&1</code></pre>
                                            </div>
                                            <div class="input-group mb-4">
                                                <h5>{{__('Clean System Logs (once per day)')}}</h5>
                                                <?php 
                                                    $url = route('clean-system-logs');
                                                    $url = explode('/cron/',$url);
                                                ?>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">0 1 * * * cd {{ base_path() }} && {{ $php_path }} artisan route:call /cron/{{$url[1]}} >/dev/null 2>&1</code></pre>
                                                <span>OR</span>
                                                <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">curl {{route('clean-system-logs')}} >/dev/null 2>&1</code></pre>
                                            </div>
                                            @if(license_check_action() == 'double')
                                                <div class="input-group mb-4">
                                                    <h5>{{__('PayPal Subscription (every 5 minutes)')}}</h5>
                                                    <?php 
                                                        $url = route('get-paypal-subscriber-transaction');
                                                        $url = explode('/cron/',$url);
                                                    ?>
                                                    <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">0 1 * * * cd {{ base_path() }} && {{ $php_path }} artisan route:call /cron/{{$url[1]}} >/dev/null 2>&1</code></pre>
                                                    <span>OR</span>
                                                    <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}">curl {{route('get-paypal-subscriber-transaction')}} >/dev/null 2>&1</code></pre>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                </div>
                            @endif

                            <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-12 col-md-4 <?php if($is_member) echo 'd-none';?>">
                                            <div class="card-header pt-5">
                                                <h4>{{__('Email Sender')}}</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="default_email" >{{ __('Default Profile') }} *</label>
                                                            <div class="form-group">
                                                                <div class="input-group" id="default-main-container">
                                                                </div>
                                                                @if ($errors->has('default_email'))
                                                                    <span class="text-danger"> {{ $errors->first('default_email') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Default Sender Email") }}</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                                                                <input name="sender_email" value="{{$sender_email}}"  class="form-control" type="email">
                                                            </div>
                                                            @if ($errors->has('sender_email'))
                                                                <span class="text-danger"> {{ $errors->first('sender_email') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Default Sender Name") }}</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                                                <input name="sender_name" value="{{$sender_name}}"  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('sender_name'))
                                                                <span class="text-danger"> {{ $errors->first('sender_name') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12  <?php echo $is_member ? 'col-md-12' : 'col-md-8';?>">
                                            <div class="card mb-4 no-shadow">
                                                <div class="card-header pt-5">
                                                    <h4>{{__('Email Profile')}} <a id="new-profile" href="#" class="ms-3 btn btn-outline-primary btn-sm"><i class="fas fa-plus-circle"></i> {{__('New')}}</a></h4>
                                                </div>
                                                <div class="card no-shadow">
                                                    <div class="card-body data-card pt-0">
                                                        <div class="table-responsive">
                                                            <table class='table table-hover table-bordered table-sm w-100' id="mytable" >
                                                                <thead>
                                                                <tr class="table-light">
                                                                    <th>#</th>
                                                                    <th>
                                                                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox"  id="datatableSelectAllRows"></div>
                                                                    </th>
                                                                    <th>{{__("Profile Name") }}</th>
                                                                    <th>{{__("API Name") }}</th>
                                                                    <th>{{__("Updated at") }}</th>
                                                                    <th>{{__("Actions") }}</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="sms" role="tabpanel" aria-labelledby="sms-tab">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card mb-4 no-shadow">
                                                <div class="card-header pt-5">
                                                    <h4>{{__('SMS Profile')}} <a id="new-sms-profile" href="#" class="ms-3 btn btn-outline-primary btn-sm"><i class="fas fa-plus-circle"></i> {{__('New')}}</a></h4>
                                                </div>
                                                <div class="card no-shadow">
                                                    <div class="card-body data-card pt-0">
                                                        <div class="table-responsive">
                                                            <table class='table table-hover table-bordered table-sm w-100' id="mytable3" >
                                                                <thead>
                                                                <tr class="table-light">
                                                                    <th>#</th>
                                                                    <th>
                                                                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox"  id="datatableSelectAllRows"></div>
                                                                    </th>
                                                                    <th>{{__("Profile Name") }}</th>
                                                                    <th>{{__("API Name") }}</th>
                                                                    <th>{{__("Updated at") }}</th>
                                                                    <th>{{__("Actions") }}</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                          <div class="tab-pane fade" id="emailauto" role="tabpanel" aria-labelledby="emailauto-tab">
                                <div class="card">
                                    <div class="row">
                                        @if(!$is_member)
                                        <div class="col-12 col-md-4">
                                            <div class="card-header pt-5">
                                                <h4>{{__('Signup Integration')}}</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach ($autoresponser_dropdown_values as $key_root => $value_root)
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="default_email" >{{ __(ucfirst($key_root)) }} {{__('List')}}</label>
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <span class="input-group-text"><i class="fas fa-stream"></i></span>

                                                                    <select class="form-control select2" id="" name="auto_responder_signup_settings[]" multiple>
                                                                        <?php
                                                                        foreach ($value_root as $key => $value)
                                                                        {
                                                                            if(!isset($value['data'])) continue;
                                                                            echo '<optgroup label="'.addslashes($value['api_name']).' : '.addslashes($value['profile_name']).'">';

                                                                            foreach ($value['data'] as $key2 => $value2)
                                                                            {
                                                                                $selected = '';
                                                                                if(isset($value_root['selected']) && in_array($value2['table_id'], $value_root['selected'])) $selected = 'selected';
                                                                                echo "<option value='".$value2['table_id']."-".$key_root."' ".$selected.">".$value2['list_name']."</option>";
                                                                            }
                                                                            echo '</optgroup>';
                                                                        } ?>
                                                                    </select>

                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="col-12 <?php echo !$is_member ? 'col-md-8' : '';?>">
                                            <div class="card mb-4 no-shadow">
                                                <div class="card-header pt-5">
                                                    <h4>{{__('Auto Responder Profile')}} <a id="new-auto-profile" href="#" class="ms-3 btn btn-outline-primary btn-sm"><i class="fas fa-plus-circle"></i> {{__('New')}}</a></h4>
                                                </div>
                                                <div class="card no-shadow">
                                                    <div class="card-body data-card pt-0">
                                                        <div class="table-responsive">
                                                            <table class='table table-hover table-bordered table-sm w-100' id="mytable2" >
                                                                <thead>
                                                                <tr class="table-light">
                                                                    <th>#</th>
                                                                    <th>
                                                                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox"  id="datatableSelectAllRows"></div>
                                                                    </th>
                                                                    <th>{{__("Profile Name") }}</th>
                                                                    <th>{{__("API Name") }}</th>
                                                                    <th>{{__("Updated at") }}</th>
                                                                    <th>{{__("Actions") }}</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            @if(!$is_member)
                            <div class="tab-pane fade" id="upload" role="tabpanel" aria-labelledby="upload-tab">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card mb-4 no-shadow">
                                                <div class="card-header mt-4">
                                                    <h4>{{__('Upload Limit')}} : {{__('Telegram Bot')}}</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12 col-md-6">
                                                            <div class="tab-content" id="v-pills-tabContent">
                                                                <div class="tab-pane active show" id="bot-upload-block-block" role="tabpanel" aria-labelledby="">
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <label for="">{{ __("Image") }} </label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                                                                                    <input name="upload_bot_image" value="{{old('upload_bot_image',$upload_bot_image)}}"  class="form-control" type="number" min="0">
                                                                                    <span class="input-group-text">MB</span>
                                                                                </div>
                                                                                @if ($errors->has('upload_bot_image'))
                                                                                    <span class="text-danger"> {{ $errors->first('upload_bot_image') }} </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <label for="">{{ __("Video") }} </label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-text"><i class="fas fa-play-circle"></i></span>
                                                                                    <input name="upload_bot_video" value="{{old('upload_bot_video',$upload_bot_video)}}"  class="form-control" type="number" min="0">
                                                                                    <span class="input-group-text">MB</span>
                                                                                </div>
                                                                                @if ($errors->has('upload_bot_video'))
                                                                                    <span class="text-danger"> {{ $errors->first('upload_bot_video') }} </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <label for="">{{ __("Audio") }} </label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-text"><i class="fas fa-microphone-alt"></i></span>
                                                                                    <input name="upload_bot_audio" value="{{old('upload_bot_audio',$upload_bot_audio)}}"  class="form-control" type="number" min="0">
                                                                                    <span class="input-group-text">MB</span>
                                                                                </div>
                                                                                @if ($errors->has('upload_bot_audio'))
                                                                                    <span class="text-danger"> {{ $errors->first('upload_bot_audio') }} </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <div class="form-group">
                                                                                <label for="">{{ __("File") }} </label>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-text"><i class="far fa-file-pdf"></i></span>
                                                                                    <input name="upload_bot_file" value="{{old('upload_bot_file',$upload_bot_file)}}"  class="form-control" type="number" min="0">
                                                                                    <span class="input-group-text">MB</span>
                                                                                </div>
                                                                                @if ($errors->has('upload_bot_file'))
                                                                                    <span class="text-danger"> {{ $errors->first('upload_bot_file') }} </span>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-md-12 d-none">
                                                            <div class="nav d-block nav-pills h-max-320px overflow-y" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                                <a class="d-block nav-link active" data-bs-toggle="pill" href="#bot-upload-block" role="tab" aria-controls="" aria-selected="true">{{__('Telegram Bot')}}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="card mb-4 no-shadow">
                                                <div class="card-header pt-5">
                                                    <h4>{{__('Script and Analytics')}}</h4>
                                                </div>
                                                <div class="card-body">

                                                    <?php
                                                        $analytics_data = isset($xdata->analytics_code) ? json_decode($xdata->analytics_code,true):[];
                                                        $fb_pixel_id = isset($analytics_data['fb_pixel_id']) ? $analytics_data['fb_pixel_id']:"";
                                                        $google_analytics_id = isset($analytics_data['google_analytics_id']) ? $analytics_data['google_analytics_id']:"";
                                                        $tme_widget_id = isset($analytics_data['tme_widget_id']) ? $analytics_data['tme_widget_id']:"";
                                                        $whatsapp_widget_id = isset($analytics_data['whatsapp_widget_id']) ? $analytics_data['whatsapp_widget_id']:"";
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="">{{ __("Facebook Pixel Id") }}</label>
                                                                <input type="text" class="form-control" id="fb_pixel_id" name="fb_pixel_id" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo $fb_pixel_id ?? old('fb_pixel_id'); ?>" placeholder="{{ __('Ex: SQVGFQOADFCTVFVWQPYE') }}">

                                                                @if ($errors->has('fb_pixel_id'))
                                                                    <span class="text-danger"> {{ $errors->first('fb_pixel_id') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                            <div class="col-12">
                                                                <div class="form-group">
                                                                    <label for="">{{ __("Google Analytics Id") }}</label>
                                                                    <input type="text" class="form-control" id="google_analytics_id" name="google_analytics_id" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo $google_analytics_id ?? old('google_analytics_id'); ?>" placeholder="{{ __('Ex: G-Z2TZKBFV49') }}">

                                                                    @if ($errors->has('google_analytics_id'))
                                                                        <span class="text-danger"> {{ $errors->first('google_analytics_id') }} </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="">{{ __("botsailor telegram short-link ") }}<a href="https://botsailor.com" target="_blank">{{ __('unique id') }}</a></label>
                                                                <input type="text" class="form-control" id="tme_widget_id" name="tme_widget_id" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo $tme_widget_id ?? old('tme_widget_id'); ?>" placeholder="{{ __('Ex: 16558882981') }}">
                                                                @if ($errors->has('tme_widget_id'))
                                                                    <span class="text-danger"> {{ $errors->first('tme_widget_id') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="">{{ __("botsailor whatsapp short-link ") }}<a href="https://botsailor.com" target="_blank">{{ __('unique id') }}</a></label>
                                                                <input type="text" class="form-control" id="whatsapp_widget_id" name="whatsapp_widget_id" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo $whatsapp_widget_id ?? old('whatsapp_widget_id'); ?>" placeholder="{{ __('Ex: 20550012541') }}">
                                                                @if ($errors->has('whatsapp_widget_id'))
                                                                    <span class="text-danger"> {{ $errors->first('whatsapp_widget_id') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="google" role="tabpanel" aria-labelledby="google-tab">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="card mb-4 no-shadow">
                                                <div class="card-header pt-5">
                                                    <h4>{{__('Social Apps Setting ')}}<span data-bs-toggle="tooltip" title="{{ __('Please enter google API key and make sure google page speed insight is enabled.') }}" class="text-primary"><i class="fas fa-info-circle"></i></span>&nbsp;&nbsp;<a class="btn btn-sm btn-primary" href="https://www.youtube.com/watch?v=4CeF1k3Sdrw" target="_BLANK"><b>{{ __("How to get google API key?") }}</b></a></h4>
                                                </div>
                                                <div class="card-body">

                                                    <?php
                                                        
                                                        $social_apps_setting = isset($xdata->social_apps_setting) ? json_decode($xdata->social_apps_setting,true):[];
                                                        $google_api_key_data = json_decode($social_apps_setting['google_app_setting']);
                                                        $google_api_key = isset($google_api_key_data->google_api_key) ? $google_api_key_data->google_api_key:"";
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="">{{ __("Google Api Key") }}</label>
                                                                <input type="text" class="form-control" id="google_api_key" name="google_api_key" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo $google_api_key ?? old('google_api_key'); ?>">

                                                                @if ($errors->has('google_api_key'))
                                                                    <span class="text-danger"> {{ $errors->first('google_api_key') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="ipnn" role="tabpanel" aria-labelledby="ip-tabdw">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="card mb-4 no-shadow">
                                                <div class="card-header pt-5">
                                                    <h4>{{__('Ip information settings ')}}<a class="btn btn-sm btn-primary" href="https://www.ip2location.io/" target="_BLANK"><b>{{ __("Where to get Ip2Location Api Key?") }}</b></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-sm btn-primary" href="https://ipinfo.io/" target="_BLANK"><b>{{ __("Where to get Ip Info Token?") }}</b></a></h4>
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="ip" role="tabpanel" aria-labelledby="ip-tab">
                                <div class="card no-radius">
                                <div class="card-header"><h4>{{ __("Ip information settings ") }}<a class="btn btn-sm btn-primary" href="https://www.ip2location.io/" target="_BLANK"><b>{{ __("Where to get Ip2Location Api Key?") }}</b></a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-sm btn-primary" href="https://ipinfo.io/" target="_BLANK"><b>{{ __("Where to get Ip Info Token?") }}</b></a></h4></div>
                                 <div class="card-body">

                                    <?php
                                        $social_apps_setting = isset($xdata->social_apps_setting) ? json_decode($xdata->social_apps_setting,true):[];
                                        $get_ip_info_data = json_decode($social_apps_setting['get_ip_info']);
                                        $ip2Location_api_key = isset($get_ip_info_data->ip2Location_api_key) ? $get_ip_info_data->ip2Location_api_key:"";
                                        $ip_info_token = isset($get_ip_info_data->ip_info_token) ? $get_ip_info_data->ip_info_token:"";
                                    ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">{{ __("Ip2Location Api Key") }}</label>
                                                    <input type="text" class="form-control" id="ip2Location_api_key" name="ip2Location_api_key" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo $ip2Location_api_key ?? old('ip2Location_api_key'); ?>">

                                                    @if ($errors->has('ip2Location_api_key'))
                                                        <span class="text-danger"> {{ $errors->first('ip2Location_api_key') }} </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">{{ __("Ip Info Token") }}</label>
                                                    <input type="text" class="form-control" id="ipIntor_token" name="ip_info_token" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo $ip_info_token ?? old('ip_info_token'); ?>">

                                                    @if ($errors->has('ip_info_token'))
                                                        <span class="text-danger"> {{ $errors->first('ip_info_token') }} </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="tab-pane fade" id="storage" role="tabpanel" aria-labelledby="storage-tab">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="card mb-4 no-shadow">
                                                <div class="card-header pt-5">
                                                    <h4>{{__('S3 storage Integration')}}</h4>
                                                </div>
                                                <div class="card-body">

                                                    <?php
                                                        $storage_data = isset($xdata->aws_settings) ? json_decode($xdata->aws_settings,true):[];
                                                        $access_key_id = isset($storage_data['access_key_id']) ? $storage_data['access_key_id']:"";
                                                        $secret_access_key = isset($storage_data['secret_access_key']) ? $storage_data['secret_access_key']:"";
                                                        $default_region = isset($storage_data['default_region']) ? $storage_data['default_region']:"";
                                                        $bucket = isset($storage_data['bucket']) ? $storage_data['bucket']:"";
                                                        $endpoint = isset($storage_data['endpoint']) ? $storage_data['endpoint']:"";
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="">{{ __("Storage access key id") }}</label>
                                                                <input type="text" class="form-control" id="access_key_id" name="access_key_id" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo $access_key_id ?? old('access_key_id'); ?>" placeholder="{{ __('Ex: SQVGFQOA2TCTVUVW') }}">

                                                                @if ($errors->has('access_key_id'))
                                                                    <span class="text-danger"> {{ $errors->first('access_key_id') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="">{{ __("Storage secret access key") }}</label>
                                                                <input type="text" class="form-control" id="secret_access_key" name="secret_access_key" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo $secret_access_key ?? old('secret_access_key'); ?>" placeholder="{{ __('Ex: LAPjQItTzbzEzrMqHu4Jl') }}">

                                                                @if ($errors->has('secret_access_key'))
                                                                    <span class="text-danger"> {{ $errors->first('secret_access_key') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="">{{ __("Storage default region") }}</label>
                                                                <input type="text" class="form-control" id="default_region" name="default_region" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo $default_region ?? old('default_region'); ?>" placeholder="{{ __('Ex: ap-southeast-1') }}">

                                                                @if ($errors->has('default_region'))
                                                                    <span class="text-danger"> {{ $errors->first('default_region') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="">{{ __("Storage bucket") }}</label>
                                                                <input type="text" class="form-control" id="bucket" name="bucket" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo $bucket ?? old('bucket'); ?>" placeholder="{{ __('Ex: demo-devs') }}">

                                                                @if ($errors->has('bucket'))
                                                                    <span class="text-danger"> {{ $errors->first('bucket') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="">{{ __("Storage end point") }}</label>
                                                                <input type="text" class="form-control" id="endpoint" name="endpoint" value="<?php if(config('settings.is_demo')=='1') echo '**************'; else echo $endpoint ?? old('endpoint'); ?>" placeholder="{{ __('Ex: https://s3.ap-demo-1.demo.com/') }}">

                                                                @if ($errors->has('endpoint'))
                                                                    <span class="text-danger"> {{ $errors->first('endpoint') }} </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                        </div>

                        @if(!$is_member)
                        <div class="card mt-4">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary me-1"><i class="fas fa-save"></i> {{__('Save')}}</button>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>                

            </form>
        </section>

    </div>


    <div class="modal fade" id="email_settings_modal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Email Profile')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   <input type="hidden" id="update-id" value="0">
                   <div class="card-body">
                        <div class="row">
                            <div class="col-7 col-md-9">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane active show" id="smtp-block" role="tabpanel" aria-labelledby="">
                                        <form id="smtp-block-form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Profile Name") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                            <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                        </div>
                                                        @if ($errors->has('profile_name'))
                                                            <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Host") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                            <input name="host" value=""  class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('host'))
                                                            <span class="text-danger"> {{ $errors->first('host') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Username") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                            <input name="username" value=""  class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('username'))
                                                            <span class="text-danger"> {{ $errors->first('username') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Password") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                            <input name="password" value=""  class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('password'))
                                                            <span class="text-danger"> {{ $errors->first('password') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Port") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-plug"></i></span>
                                                            <input name="port" value=""  class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('port'))
                                                            <span class="text-danger"> {{ $errors->first('port') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Encryption") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                                            <?php echo Form::select('encryption',array(''=>'Default','tls'=>"TLS",'ssl'=>"SSL"),'',array('class'=>'form-control','not-required'=>'true')); ?>
                                                        </div>
                                                        @if ($errors->has('encryption'))
                                                            <span class="text-danger"> {{ $errors->first('encryption') }} </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="mailgun-block" role="tabpanel" aria-labelledby="">
                                        <form id="mailgun-block-form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Profile Name") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                            <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                        </div>
                                                        @if ($errors->has('profile_name'))
                                                            <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Domain") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-server"></i></span>
                                                            <input name="domain" value=""  class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('domain'))
                                                            <span class="text-danger"> {{ $errors->first('domain') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Secret") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                            <input name="secret" value=""  class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('secret'))
                                                            <span class="text-danger"> {{ $errors->first('secret') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Endpoint") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-genderless"></i></span>
                                                            <input name="endpoint" value="api.eu.mailgun.net"  class="form-control" type="text" reset="false">
                                                        </div>
                                                        @if ($errors->has('endpoint'))
                                                            <span class="text-danger"> {{ $errors->first('endpoint') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="postmark-block" role="tabpanel" aria-labelledby="">
                                        <form id="postmark-block-form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Profile Name") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                            <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                        </div>
                                                        @if ($errors->has('profile_name'))
                                                            <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Token") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                            <input name="token" value=""  class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('token'))
                                                            <span class="text-danger"> {{ $errors->first('token') }} </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="ses-block" role="tabpanel" aria-labelledby="">
                                        <form id="ses-block-form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Profile Name") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                            <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                        </div>
                                                        @if ($errors->has('profile_name'))
                                                            <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Key") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                            <input name="key" value=""  class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('key'))
                                                            <span class="text-danger"> {{ $errors->first('key') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Secret") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                            <input name="secret" value=""  class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('secret'))
                                                            <span class="text-danger"> {{ $errors->first('secret') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Region") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-genderless"></i></span>
                                                            <input name="region" value="us-east-1"  class="form-control" type="text" reset="false">
                                                        </div>
                                                        @if ($errors->has('region'))
                                                            <span class="text-danger"> {{ $errors->first('region') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="mandrill-block" role="tabpanel" aria-labelledby="">
                                        <form id="mandrill-block-form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Profile Name") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                            <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                        </div>
                                                        @if ($errors->has('profile_name'))
                                                            <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Key") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                            <input name="secret" value=""  class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('secret'))
                                                            <span class="text-danger"> {{ $errors->first('secret') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <div class="col-5 col-md-3">
                                <div class="nav d-block nav-pills email-block" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="d-block nav-link active" data-bs-toggle="pill" href="#smtp-block" id="smtp-block-link" role="tab" aria-controls="" aria-selected="true">{{__('SMTP')}}</a>
                                    <a class="nav-link" data-bs-toggle="pill"  href="#mailgun-block" id="mailgun-block-link" role="tab" aria-controls="" aria-selected="true">{{__('Mailgun')}}</a>
                                    <a class="nav-link" data-bs-toggle="pill"  href="#postmark-block"  id="postmark-block-link" role="tab" aria-controls="" aria-selected="true">{{__('Postmark')}}</a>
                                    <a class="nav-link" data-bs-toggle="pill"  href="#ses-block" id="ses-block-link" role="tab" aria-controls="" aria-selected="true">{{__('SES')}}</a>
                                    <a class="nav-link" data-bs-toggle="pill"  href="#mandrill-block" id="mandrill-block-link" role="tab" aria-controls="" aria-selected="true">{{__('Mandril')}}</a>
                                </div>
                            </div>
                        </div>
                   </div>

                </div>

                <div class="modal-footer d-block">
                    <button type="button" class="btn btn-primary float-start" id="save_email_settings"><i class="fas fa-save"></i> {{__('Save')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sms_settings_modal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('SMS Profile')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                       <input type="hidden" id="sms-update-id" value="0">
                       <div class="card-body">
                            <div class="row">
                                <div class="col-7 col-md-9">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane active show" id="plivo-block" role="tabpanel" aria-labelledby="">
                                            <form id="plivo-block-form">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Profile Name") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                                <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                            </div>
                                                            @if ($errors->has('profile_name'))
                                                                <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Auth ID") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="auth_id" value=""  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('auth_id'))
                                                                <span class="text-danger"> {{ $errors->first('auth_id') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Auth Token") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="auth_token" value=""  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('auth_token'))
                                                                <span class="text-danger"> {{ $errors->first('auth_token') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Sender/From") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                                <input name="sender" value=""  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('sender'))
                                                                <span class="text-danger"> {{ $errors->first('sender') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>

                                        <div class="tab-pane fade" id="twilio-block" role="tabpanel" aria-labelledby="">
                                            <form id="twilio-block-form">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Profile Name") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                                <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                            </div>
                                                            @if ($errors->has('profile_name'))
                                                                <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Auth SID") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="auth_sid" value=""  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('auth_sid'))
                                                                <span class="text-danger"> {{ $errors->first('auth_sid') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Auth Token") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="auth_token" value=""  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('auth_token'))
                                                                <span class="text-danger"> {{ $errors->first('auth_token') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Sender/From") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                                <input name="sender" value=""  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('sender'))
                                                                <span class="text-danger"> {{ $errors->first('sender') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>

                                        <div class="tab-pane fade" id="nexmo-block" role="tabpanel" aria-labelledby="">
                                            <form id="nexmo-block-form">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Profile Name") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                                <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                            </div>
                                                            @if ($errors->has('profile_name'))
                                                                <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="">{{ __("API Key") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="api_key" value=""  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('api_key'))
                                                                <span class="text-danger"> {{ $errors->first('api_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <div class="form-group">
                                                            <label for="">{{ __("API Secret") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                                <input name="api_secret" value=""  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('api_secret'))
                                                                <span class="text-danger"> {{ $errors->first('api_secret') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Sender/From") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                                <input name="sender" value=""  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('sender'))
                                                                <span class="text-danger"> {{ $errors->first('sender') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>

                                        <div class="tab-pane fade" id="clickatell-block" role="tabpanel" aria-labelledby="">
                                            <form id="clickatell-block-form">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Profile Name") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                                <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                            </div>
                                                            @if ($errors->has('profile_name'))
                                                                <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("API ID") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="api_id" value=""  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('api_id'))
                                                                <span class="text-danger"> {{ $errors->first('api_id') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>

                                        <div class="tab-pane fade" id="africastalking-block" role="tabpanel" aria-labelledby="">
                                            <form id="africastalking-block-form">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Profile Name") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                                <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                            </div>
                                                            @if ($errors->has('profile_name'))
                                                                <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("API Key") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fab fa-keycdn"></i></span>
                                                                <input name="api_key" value=""  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('api_key'))
                                                                <span class="text-danger"> {{ $errors->first('api_key') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="">{{ __("Sender (Username)") }} *</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                                <input name="sender" value=""  class="form-control" type="text">
                                                            </div>
                                                            @if ($errors->has('sender'))
                                                                <span class="text-danger"> {{ $errors->first('sender') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-5 col-md-3">
                                    <div class="nav d-block nav-pills sms-block" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        <a class="d-block nav-link active" data-bs-toggle="pill" href="#plivo-block" id="plivo-block-link" role="tab" aria-controls="" aria-selected="true">{{__('Plivo')}}</a>
                                        <a class="nav-link" data-bs-toggle="pill"  href="#twilio-block" id="twilio-block-link" role="tab" aria-controls="" aria-selected="true">{{__('Twilio')}}</a>
                                        <a class="nav-link" data-bs-toggle="pill"  href="#nexmo-block"  id="nexmo-block-link" role="tab" aria-controls="" aria-selected="true">{{__('Nexmo/Vonage')}}</a>
                                        <a class="nav-link" data-bs-toggle="pill"  href="#clickatell-block" id="clickatell-block-link" role="tab" aria-controls="" aria-selected="true">{{__('Clickatell')}}</a>
                                        <a class="nav-link" data-bs-toggle="pill"  href="#africastalking-block" id="africastalking-block-link" role="tab" aria-controls="" aria-selected="true">{{__('Africastalking')}}</a>
                                    </div>
                                </div>
                            </div>
                       </div>

                    </div>

                <div class="modal-footer d-block">
                    <button type="button" class="btn btn-primary float-start" id="save_sms_settings"><i class="fas fa-save"></i> {{__('Save')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="email_auto_settings_modal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Auto Responder Profile')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="auto-update-id" value="0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-7 col-md-9">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane active show" id="mailchimp-block" role="tabpanel" aria-labelledby="">
                                        <form id="mailchimp-block-form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Profile Name") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                            <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                        </div>
                                                        @if ($errors->has('profile_name'))
                                                            <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("API Key") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                            <input name="api_key" value="" non-editable="true"  class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('api_key'))
                                                            <span class="text-danger"> {{ $errors->first('api_key') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="sendinblue-block" role="tabpanel" aria-labelledby="">
                                        <form id="sendinblue-block-form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Profile Name") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                            <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                        </div>
                                                        @if ($errors->has('profile_name'))
                                                            <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("API Key") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                            <input name="api_key" value="" non-editable="true"  class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('api_key'))
                                                            <span class="text-danger"> {{ $errors->first('api_key') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="activecampaign-block" role="tabpanel" aria-labelledby="">
                                        <form id="activecampaign-block-form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Profile Name") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                            <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                        </div>
                                                        @if ($errors->has('profile_name'))
                                                            <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("API Key") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                            <input name="api_key" value="" non-editable="true" class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('api_key'))
                                                            <span class="text-danger"> {{ $errors->first('api_key') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("API URL") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                            <input name="api_url" value="" non-editable="true" class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('api_url'))
                                                            <span class="text-danger"> {{ $errors->first('api_url') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="mautic-block" role="tabpanel" aria-labelledby="">
                                        <form id="mautic-block-form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Profile Name") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                            <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                        </div>
                                                        @if ($errors->has('profile_name'))
                                                            <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Username") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fab fa-user"></i></span>
                                                            <input name="username" value="" non-editable="true" class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('username'))
                                                            <span class="text-danger"> {{ $errors->first('username') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Password") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                            <input name="password" value="" non-editable="true" class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('password'))
                                                            <span class="text-danger"> {{ $errors->first('password') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Base URL") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                            <input name="base_url" value="" non-editable="true" class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('base_url'))
                                                            <span class="text-danger"> {{ $errors->first('base_url') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="acelle-block" role="tabpanel" aria-labelledby="">
                                        <form id="acelle-block-form">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("Profile Name") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                            <input name="profile_name" value=""  class="form-control" type="text" placeholder="{{__('Any name to identify it later')}}">
                                                        </div>
                                                        @if ($errors->has('profile_name'))
                                                            <span class="text-danger"> {{ $errors->first('profile_name') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("API Key") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                            <input name="api_key" value="" non-editable="true" class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('api_key'))
                                                            <span class="text-danger"> {{ $errors->first('api_key') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="">{{ __("API URL") }} *</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                            <input name="api_url" value="" non-editable="true" class="form-control" type="text">
                                                        </div>
                                                        @if ($errors->has('api_url'))
                                                            <span class="text-danger"> {{ $errors->first('api_url') }} </span>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            <div class="col-5 col-md-3">
                                <div class="nav d-block nav-pills email-auto-block" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="d-block nav-link active" data-bs-toggle="pill" href="#mailchimp-block" id="mailchimp-block-link" role="tab" aria-controls="" aria-selected="true">{{__('Mailchimp')}}</a>
                                    <a class="nav-link" data-bs-toggle="pill"  href="#sendinblue-block" id="sendinblue-block-link" role="tab" aria-controls="" aria-selected="true">{{__('Sendinblue')}}</a>
                                    <a class="nav-link" data-bs-toggle="pill"  href="#activecampaign-block"  id="activecampaign-block-link" role="tab" aria-controls="" aria-selected="true">{{__('ActiveCampaign')}}</a>
                                    <a class="nav-link" data-bs-toggle="pill"  href="#mautic-block" id="mautic-block-link" role="tab" aria-controls="" aria-selected="true">{{__('Mautic')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer d-block">
                    <button type="button" class="btn btn-primary float-start" id="save_email_auto_settings"><i class="fas fa-save"></i> {{__('Save')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts-footer')
    <script>
        "use strict";
        var ajax_set_active_tag_id = '{{route('general-settings-set-session-active-tab')}}';
        var active_tag_id = '{{session('general_settings_active_tab_id')}}';
    </script>
    <script src="{{ asset('assets/js/pages/member/settings.general-settings.js') }}"></script>
@endpush
