    @extends('layouts.auth')
    @section('title',__('Account'))
    @section('content')
    <div class="main-content container-fluid">
        <section id="basic-horizontal-layouts">
            <div class="row match-height">
                <div class="col-lg-6 offset-lg-3 col-md-12 col-12' : 'col-lg-5 col-md-12 col-12">

                    @if (session()->has('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                    @endif
                    @if (session()->has('error_otp_message'))
                    <div class="alert alert-danger">
                        {{ session('error_otp_message') }}
                    </div>
                    @endif
                    <div class="card">
                        <div class="card-content">
                            <div class="card-header">
                                <h4>{{__('Affiliate Request')}}
                                    @if (isset($request_status->status)&& $request_status->status == 3)
                                    <small class="float-end  text-danger">{{ __('*Request pending') }}</small>
                                    @elseif (isset($request_status->status)&& $request_status->status == 1)
                                        <small class="float-end  text-danger">{{ __('*Your request has been rejected') }}</small>
                                    @else
                                    @endif
                                </h4>
                            </div>
                            <div class="card-body">
                                <form class="form form-vertical" enctype="multipart/form-data" method="POST" action="{{ route('affiliate-request-action') }}">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group has-icon-left">
                                                            <label>{{ __('Email') }}</label>
                                                            <div class="input-group mb-3">
                                                                <span class="input-group-text" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="{{ __('Your email address to get the OTP') }}"><i class="fas fa-info-circle"></i></span>
                                                                <input type="email" id="email"class="form-control" name="email" placeholder="{{ __('Your Email Address') }}" @if(!empty($request_status->email))
                                                                value ="{{ $request_status->email }}"
                                                                @else
                                                                    value = "{{ old('email') }}"
                                                                @endif>
                                                                    
                               
                                                                <button class="btn btn-success" @if (!empty($request_status->status)&& $request_status->status == 3)disabled @endif type="button" id="verify_button">{{__('Send OTP')}}</button>
                                                            </div>

                                                            @if ($errors->has('email'))
                                                            <span class="text-danger"> {{ $errors->first('email') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-12" id="otp_number">
                                                        <div class="form-group has-icon-left">
                                                            <label>{{ __('OTP Code') }}*</label>
                                                            <div class="position-relative">
                                                                <input type="text" id="contact-info" class="form-control" name="otp" placeholder="Your otp code">
                                                                <div class="form-control-icon">
                                                                    <i class="fas fa-mobile-alt"></i>
                                                                </div>
                                                            </div>
                                                            @if ($errors->has('otp'))
                                                            <span class="text-danger"> {{ $errors->first('otp') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div class="form-group has-icon-left">
                                                            <label>{{ __('Facebook Profile Link') }}*</label>
                                                            <div class="position-relative">
                                                                <input type="text" id="fb_link" class="form-control" name="fb_link" placeholder="Your Facebook Profile Link"@if (!empty($request_status->fb_link))
                                                                value= " {{ $request_status->fb_link }}"
                                                                 @else
                                                                    value = "{{ old('fb_link') }}"
                                                                @endif>
                                                                <div class="form-control-icon">
                                                                    <i class="fab fa-facebook"></i>
                                                                </div>
                                                            </div>
                                                            @if ($errors->has('fb_link'))
                                                            <span class="text-danger"> {{ $errors->first('fb_link') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group has-icon-left">
                                                            <label>{{ __('Website Url') }}*</label>
                                                            <div class="position-relative">
                                                                <input type="text" id="website" class="form-control" name="website" placeholder="Your Website url"@if (!empty($request_status->website))
                                                                value ="{{ $request_status->website }}"
                                                                 @else
                                                                    value = "{{ old('website') }}"
                                                                @endif>
                                                                <div class="form-control-icon">
                                                                    <i class="fas fa-globe"></i>
                                                                </div>
                                                            </div>
                                                            @if ($errors->has('website'))
                                                            <span class="text-danger"> {{ $errors->first('website') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group ">
                                                            <label>{{ __('How You Will Promote') }}*</label>
                                                            <div class="position-relative">
                                                                <textarea type="text" id="affiliating_process" cols="50" class="form-control" name="affiliating_process" placeholder="How you will promote">@if(!empty($request_status->affiliating_process)){{ $request_status->affiliating_process }} @else {{ old('affiliating_process') }} @endif</textarea> 
                                                            </div>
                                                            @if ($errors->has('affiliating_process'))
                                                            <span class="text-danger"> {{ $errors->first('affiliating_process') }} </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mt-4">
                                                        <button type="submit" @if (isset($request_status->status)&& $request_status->status == 3)
                                                            disabled 
                                                            @endif class="btn btn-primary me-1 mb-1"><i class="fas fa-paper-plane"></i> {{ __('Submit Affiliate Request') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>


        @endsection
        @push('scripts-footer')
            <script src="{{ asset('assets/js/pages/affiliate/affiliate_request.js') }}"></script>
        @endpush