@extends('layouts.auth')
@section('title',__('Domain'))

@push('styles-header')
    <link rel="stylesheet" href="{{ asset('assets/heatmap/css/domain_list.css') }}" />
    <link href="{{asset('assets/cdn/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">

@endpush
@section('content')
    <div class="main-content container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{__('Domain')}} 
                            <?php if(has_module_action_access(1,1,$team_access,$is_manager)) : ?>
                            <a class="btn btn-outline-primary add_domain_modal" href="#">
                                <i class="fas fa-plus-circle"></i> <?php echo __("New"); ?>
                            </a> 
                            <?php endif; ?>
                    </h3>
                    <p class="text-subtitle text-muted">{{__('List of Domain')}}</p>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                @php($i=0)
                @foreach($domain_list as $key => $value)
                <?php
                    $screenshot = $value->screenshot !=NULL ? $value->screenshot: asset('assets/images/example-image.jpg');
                    if($value->pause_play=='play') {

                        $title = __('Stop Recording');
                        $button_icon = '<i class="fas fa-pause"></i>';
                        $status = '<i class="fas fa-record-vinyl text-success"></i> '. __('Recording'); 

                    } else if($value->pause_play=='pause') {
                        $title = __('Start Recording');
                        $button_icon = '<i class="fas fa-play"></i>';
                        $status = '<i class="fas fa-stop text-danger"></i> '. __('Stopped'); 
                    } 
                ?>
                <div class="col-12 col-md-6">
                    <div class="card mb-3 domain-card mb-4 block-{{$i}}" id="card_mb-3_domain-car" >
                      <div class="row no-gutters">
                        <div class="col-12 col-md-5 pe-xl-0">
                          <img src="{{ $screenshot }}" class="card-img rounded-0" alt="...">
                        </div>
                        <div class="col-md-7 ps-xl-0">
                          <div class="card-body p-3">
                            <div class="d-inline">
                                <span class="card-status float-start text-muted" data-bs-toggle="tooltip" title="{{ __('Pause/play Recording') }}">{!! $status !!}</span>
                                <span class="card-engaged float-end text-muted" data-bs-toggle="tooltip" title="{{ __('Added at') }}">{{ date("j M, Y",strtotime($value->add_date)) }}</span>
                            </div>

                            <div class="card-domain-name">{{ $value->domain_name }}</div>
                            <div class="card-btn2 mt-3 text-center">

                                <button data-id="{{ $value->id }}" class="card-btn-item a pause_play_domain" data-bs-toggle="tooltip" title="{{ $title }}" blockId="{{ $i }}" eventType="{{ $value->pause_play }}">{!! $button_icon !!}</button>

                                <button campaign_id="{{ $value->id }}" class="card-btn-item b get_js_embed" data-bs-toggle="tooltip" title="{{ __('Embeded Code') }}"><i class="fas fa-code"></i></button>
                                <button campaign_id="{{ $value->id }}" class="card-btn-item d edit_domain" data-bs-toggle="tooltip" title="{{ __('Domain Settings') }}"><i class="fas fa-edit"></i></button>
                                <?php if(has_module_action_access(1,3,$team_access,$is_manager)) : ?>
                                    <button blockId="{{ $i }}" href="{{route('delete-domain')}}" data-id="{{ $value->id }}" class="card-btn-item c delete-domain" data-bs-toggle="tooltip" title="{{ __('Delete') }}"><i class="fas fa-trash-alt"></i></button>
                                <?php endif; ?>
                            </div>
                            
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                @php($i++)
                @endforeach
            </div>

            <div class="clearfix"></div>

            {{ $domain_list->links() }}

        </section>

    </div>

    <div class="modal fade" id="get_embed_modal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Embed Code')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="">
                    <div class="row" id="installation-method-post">
                        <div class="col-12">
                            <div class="card no-shadow">
                                <div class="card-header p-0">
                                    <h6>{{ __("Choose your installation method and we'll show you how to add it to your site in a few easy steps.") }}</h6>
                                </div>

                                <div class="card-content">
                                    <div class="card-body mt-4 p-0">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="tech-body-content-post border text-center float-end ms-3 me-0 px-5 py-3 pointer rounded" tech-type="wp">
                                                    <img width="60" height="60" src="{{ asset('assets/images/wordpress.png') }}" alt="">
                                                    <div><p class="mb-0">{{__('Wordpress') }}</p></div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="tech-body-content-post border text-center float-start ms-3 me-0 px-5 py-3 pointer rounded" tech-type="html">
                                                    <img width="60" height="60" src="{{ asset('assets/images/html.png') }}" alt="">
                                                    <div><p class="mb-0">{{__('HTML') }}</p></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tech-body-post">
                        <div id="wordpress-post">
                            <div>
                                <div class="float-start text-center border rounded mt-0 me-5 mb-3 ms-0 px-4 py-2">
                                    <img src="{{ asset('assets/images/wordpress.png') }}" alt="wordpress" width="70" height="70">
                                    <p class="mb-0">{{ __('Wordpress') }}</p>
                                </div>

                                <div class="content-body m-0">
                                    <ol>
                                        <li>{{ __('Open up your Wordpress dashboard and click on "Appearance -> Theme Editor" section and theme file will be loaded.') }}</li>
                                        <li>{{ __('From the right side menu, scroll down to find the theme footer (footer.php). Select to edit the footer.php file.') }}</li>
                                        <li>{!! __('Below code is the generated embeded tracking code of :appname, just copy the below code',['appname'=>config('app.name')]) !!}:
                                            <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}" id="put_embed_js_code_wp_post"></code></pre>
                                        </li>
                                        <li>{{ __('Now paste the copied embed code just before the body closing tag.') }}</li>
                                        <li>{{ __('Now hit the "Save" button to update the file.') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div id="html-post">
                            <div>
                                <div class="float-start text-center border rounded mt-0 me-5 mb-0 ms-0 px-4 py-2">
                                    <img src="{{ asset('assets/images/html.png') }}" alt="wordpress" width="70" height="70">
                                    <p class="mb-0">{{ __('Html') }}</p>
                                </div>

                                <div class="content-body m-0">
                                    <p class="fw-bold">{!! __("Adding the :appname tracking code on your website just needs few steps, please follow them to install the code",['appname'=>config("app.name")]) !!}:</p>
                                    <ol>
                                        <li>{{ __("First go to your content management system (CMS) and then find the template of the desired website where you want to add the tracking code. It's better to have common files that loads in every pages like header.html/header.php or footer.html/footer.php, so we recommend to add the tracking code into your footer section of the website. Now open that common footer file.") }}</li>
                                        <li>{{ __("Below code is the generated embeded tracking code, just copy the below code") }}:
                                            <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}" id="put_embed_js_code_html_post"></code></pre>
                                        </li>
                                        <li>{{ __('Now Paste the copied Code into your website template`s footer section before the body closing tag.') }}</li>
                                        <li>{{ __('save the changes.') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="content-button mt-5">
                            <button class="btn btn-outline-secondary" id="go_back_form_post"><i class="fas fa-arrow-left"></i> {{ __("Back") }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_domain_modal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document" id="modal-dialog_idd">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo __('New Domain'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="form-body">
                        <input type="hidden" name="domain_table_id" id="domain_table_id">
                        <div class="row mb-3">
                            <label for="domain_prefix" class="col-sm-2 col-form-label text-xl-end px-xl-0">{{ __("Domain Prefix") }}</label>
                            <div class="col-sm-10 mt-2">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                         <input class="form-check-input" name="domain_prefix" value="https://" checked type="radio"  id="domain_prefix1">
                                         <label class="form-check-label" for="domain_prefix1">https://</label>
                                     </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                             <input class="form-check-input" name="domain_prefix" value="http://" type="radio" id="domain_prefix2">
                                            <label class="form-check-label" for="domain_prefix2">http://</label>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 only_create">
                            <label for="domain_name_add" class="col-sm-2 col-form-label text-xl-end px-xl-0">{{ __("Domain") }}</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" placeholder="<?php echo __('Add a domain to record'); ?>" id="domain_name_add" name="domain_name_add">
                            </div>
                        </div>
                        <div class="row mb-4" >
                            <label for="excluded_ips" class="col-sm-2 col-form-label text-xl-end px-xl-0">{{ __('Exluded IPs') }}</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="excluded_ips" name="excluded_ips" placeholder="<?php echo __('Add IP addresses to exclude'); ?>">
                                    <span class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="right" title="{{__('This is how you exclude your own IP address(es) from tracking. You can enter IP addresses comma separated and use wildcards (asterisks) to exclude variations (i.e. 50.50.* or 50:50:50:*).')}}"><i class="fas fa-info-circle"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="block_class" class="col-sm-2 col-form-label text-xl-end px-xl-0">{{ __('Block Class') }}</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="block_class" name="block_class" placeholder="<?php echo __('Use a string or RegExp to configure which elements should be blocked'); ?>">
                                    <span class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="right" title="{{__('An element with the class name Ex.(.rr-block)  will not be recorded. Instead, it will replay as a placeholder with the same dimension')}}"><i class="fas fa-info-circle"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="ignore_class" class="col-sm-2 col-form-label text-xl-end px-xl-0">{{ __('Ignore Class') }}</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="ignore_class" name="ignore_class" placeholder="<?php echo __('Use a string or RegExp to configure which elements should be ignored'); ?>">
                                    <span class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="right" title="{{__('An element with the class name Ex.(.rr-ignore)  will not record its input events')}}"><i class="fas fa-info-circle"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="maskText_class" class="col-sm-2 col-form-label text-xl-end px-xl-0">{{ __('MaskText Class') }}</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="maskText_class" name="maskText_class" placeholder="<?php echo __('Use a string or RegExp to configure which elements should be masked'); ?>">
                                    <span class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="right" title="{{__('All text of elements with the class name Ex.(.rr-mask) and their children will be masked.')}}"><i class="fas fa-info-circle"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="maskInputOptions" class="col-sm-2 col-form-label text-xl-end px-xl-0">{{ __('Mask Input Option') }}</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="maskInput_option" name="maskInput_option" placeholder="<?php echo __('Write input option with comma separator(password,url,color)'); ?>">
                                    <span class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="right" title="{{__('Input Option List *password,color,date,email,datetime-local,month,number,range,search,tel,text,time,url,week,textarea,select')}}"><i class="fas fa-info-circle"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="MaskAllInputs" class="col-sm-2 col-form-label text-xl-end px-xl-0">{{ __("Mask All Inputs") }}</label>
                            <div class="col-sm-10 mt-2">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                         <input class="form-check-input" name="maskAllInputs" value="false" checked type="radio"  id="maskAllInputs1">
                                         <label class="form-check-label" for="maskAllInputs1">No</label>
                                     </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                             <input class="form-check-input" name="maskAllInputs" value="true" type="radio" id="maskAllInputs2">
                                            <label class="form-check-label" for="maskAllInputs2">Yes</label>
                                         </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <button class="btn btn-primary float-end" type="submit" id="add_domain"><i class="fas fa-plus-circle"></i> <?php echo __('Add'); ?></button>
                            </div>
                        </div>

                        <div class="row" id="installation-method" >
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6>{{ __("Choose your installation method and we'll show you how to add it to your site in a few easy steps.") }}</h6>
                                    </div>

                                    <div class="card-content">
                                        <div class="card-body pt-0">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="tech-body-content border text-center float-end ms-3 me-0 px-5 py-3 pointer rounded" tech-type="wp">
                                                        <img width="60" height="60" src="{{ asset('assets/images/wordpress.png') }}" alt="">
                                                        <div><p class="mb-0">{{__('Wordpress') }}</p></div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="tech-body-content border text-center float-start ms-3 me-0 px-5 py-3 pointer rounded" tech-type="html">
                                                        <img width="60" height="60" src="{{ asset('assets/images/html.png') }}" alt="">
                                                        <div><p class="mb-0">{{__('HTML') }}</p></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tech-body">
                        <div id="wordpress" >
                            <div>
                                <div class="float-start text-center border rounded mt-0 me-5 mb-3 ms-0 px-4 py-2">
                                    <img src="{{ asset('assets/images/wordpress.png') }}" alt="wordpress" width="70" height="70">
                                    <p class="mb-0">{{ __('Wordpress') }}</p>
                                </div>

                                <div class="content-body m-0">
                                    <ol>
                                        <li>{{ __('Open up your Wordpress dashboard and click on "Appearance -> Theme Editor" section and theme file will be loaded.') }}</li>
                                        <li>{{ __('From the right side menu, scroll down to find the theme footer (footer.php). Select to edit the footer.php file.') }}</li>
                                        <li>{!! __('Below code is the generated embeded tracking code of :appname, just copy the below code',['appname'=>config('app.name')]) !!}:
                                            <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}" id="put_embed_js_code_wp"></code></pre>
                                        </li>
                                        <li>{{ __('Now paste the copied embed code just before the body closing tag.') }}</li>
                                        <li>{{ __('Now hit the "Save" button to update the file.') }}</li>

                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div id="html">
                            <div>
                                <div class="float-start text-center border rounded mt-0 me-5 mb-3 ms-0 px-4 py-2">
                                    <img src="{{ asset('assets/images/html.png') }}" alt="wordpress" width="70" height="70">
                                    <p class="mb-0">{{ __('Html') }}</p>
                                </div>

                                <div class="content-body m-0">
                                    <ol>
                                        <li>{{ __("First go to your content management system (CMS) and then find the template of the desired website where you want to add the tracking code. It's better to have common files that loads in every pages like header.html/header.php or footer.html/footer.php, so we recommend to add the tracking code into your footer section of the website. Now open that common footer file.") }}</li>
                                        <li>{{ __("Below code is the generated embeded tracking code, just copy the below code") }}:
                                            <pre><code class="language-html" data-prismjs-copy="{{__('Copy')}}" id="put_embed_js_code_html"></code></pre>
                                        </li>
                                        <li>{{ __('Now Paste the copied Code into your website template`s footer section before the body closing tag.') }}</li>
                                        <li>{{ __('save the changes.') }}</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="content-button mt-5">
                            <button class="btn btn-outline-secondary" id="go_back_form"><i class="fas fa-arrow-left"></i> {{ __("Back") }}</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-none">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts-footer')
    <script src="{{ asset('assets/heatmap/js/domain_list.js') }}"></script>
    <script src="{{asset('assets/cdn/js/bootstrap-toggle.min.js')}}"></script>
@endpush
