<?php
$action_type = $xdata->user_type == 'Manager' ? 'team' : 'user';
$title_display = $action_type=='team' ? __('Update Team') : __('Update User');
$title_display_des = $action_type=='team' ? __('Update an existing team member') : __('Update an existing user');
?>
@extends('layouts.auth')
@section('title',$title_display)
@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <div class="row text-center">
            <div class="col-12 text-center">
                <h3>{{$title_display}}</h3>
                <p class="text-subtitle text-muted">{{$title_display_des}}</p>
            </div>

        </div>
    </div>

    <section id="basic-horizontal-layouts">
        <div class="row match-height">
            <div class="col-lg-8 offset-md-2 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('User Information') }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-vertical" enctype="multipart/form-data" method="POST" action="{{ route('update-user-action') }}">
                                @csrf
                                <input type="hidden" name="action_type" id="action_type" value="{{$action_type}}">
                                <input type="hidden" name="id" value="{{$xdata->id}}">
                                <input type="hidden" name="xemail" value="{{$xdata->email}}">
                                <div class="form-body">

                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <div class="form-group">
                                                <label for="name"> {{ __("Full Name") }} *</label>
                                                <input name="name"  class="form-control" type="text" value="{{old('name',$xdata->name)}}">
                                                @if ($errors->has('name'))
                                                    <span class="text-danger"> {{ $errors->first('name') }} </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-group">
                                                <label for="name"> {{ __("Email") }} *</label>
                                                <input name="email"  class="form-control" type="email" value="{{old('email',$xdata->email)}}">
                                                @if ($errors->has('email'))
                                                    <span class="text-danger"> {{ $errors->first('email') }} </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <div class="form-group">
                                                <label for="name"> {{ __("Mobile") }}</label>
                                                <input name="mobile"  class="form-control" type="text" value="{{old('mobile',$xdata->mobile)}}">
                                                @if ($errors->has('mobile'))
                                                    <span class="text-danger"> {{ $errors->first('mobile') }} </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="name"> {{ __("Password") }}</label>
                                                <input name="password"  class="form-control" type="password">
                                                @if ($errors->has('password'))
                                                    <span class="text-danger"> {{ $errors->first('password') }} </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="name"> {{ __("Confirm Password") }}</label>
                                                <input name="password_confirmation"  class="form-control" type="password">
                                                @if ($errors->has('password_confirmation'))
                                                    <span class="text-danger"> {{ $errors->first('password_confirmation') }} </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>{{ __('Address') }}</label>
                                                <textarea id="address"  class="form-control" name="address">{{old('address',$xdata->address)}}</textarea>
                                                @if ($errors->has('address'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('address') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4 d-none">
                                            <div class="form-group">
                                                <label>{{ __('User Type') }}*</label>
                                                <input type="text" class="form-control" name="user_type" id="user_type" value="{{$xdata->user_type}}">
                                                @if ($errors->has('user_type'))
                                                    <span class="text-danger">
                                                    {{ $errors->first('user_type') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>{{ $action_type=='team' ? __('Team Role') :  __('Subscription Package') }}*</label>
                                                <select name="package_id" class="form-control select2">
                                                    <option value="">{{__('Select')}}</option>
                                                    @foreach($packages as $value)
                                                        <?php $selected = $value->id == old('package_id',$xdata->package_id) ? 'selected' : '';?>
                                                        <option {{$selected}} value="{{$value->id}}">{{$value->package_name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('package_id'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('package_id') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($action_type!='team')
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>{{ __('Expiry date') }}*</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                                    <input type="text" name="expired_date" class="form-control datepicker" value="{{old('expired_date',date("Y/m/d",strtotime($xdata->expired_date)))}}">
                                                </div>
                                                @if ($errors->has('expired_date'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('expired_date') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        @if($action_type=='team')
                                            <?php $xallowed_domain_ids = !empty($xdata->allowed_domain_ids) ? json_decode($xdata->allowed_domain_ids,true) : []; ?>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>{{ __('Allowed Domains') }}</label>
                                                    <?php echo Form::select('allowed_domain_ids[]',$website_list,old('allowed_domain_ids',$xallowed_domain_ids),['class' => 'form-control select2','id'=>'allowed_domain_ids','style'=>'width:100% !important;','multiple'=>'multiple']);?>
                                                    @if ($errors->has('allowed_domain_ids'))
                                                        <span class="text-danger">
                                                        {{ $errors->first('allowed_domain_ids') }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-12 col-md-3 mt-4">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" id="status" name="status" type="checkbox" value="1" <?php echo (old('status',$xdata->status)=='0') ? '' : 'checked'; ?>>
                                                    <label class="form-check-label" for="status">{{__("Status")}}</label>
                                                </div>
                                                @if ($errors->has('status'))
                                                    <span class="text-danger"> {{ $errors->first('status') }} </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                    </div>

                                    @if($xdata->user_type=='Agent' && $xdata->agent_has_whitelabel=='1' && $is_admin)
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <label for="">{{ __("White-label Domain") }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                        <input name="agent_domain" placeholder="yourbotsailor.com" value="{{old('agent_domain',$xdata->agent_domain)}}"  class="form-control" type="text">
                                                    </div>
                                                    @if ($errors->has('agent_domain'))
                                                        <span class="text-danger"> {{ $errors->first('agent_domain') }} </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <label for="">{{ __("Mailgun Username") }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        <input name="agent_mailgun_username" placeholder="" value="{{old('agent_mailgun_username',$xdata->agent_mailgun_username)}}"  class="form-control" type="text">
                                                    </div>
                                                    @if ($errors->has('agent_mailgun_username'))
                                                        <span class="text-danger"> {{ $errors->first('agent_mailgun_username') }} </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <label for="">{{ __("Mailgun Password") }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                        <input name="agent_mailgun_password" placeholder="" value="{{old('agent_mailgun_password',$xdata->agent_mailgun_password)}}"  class="form-control" type="text">
                                                    </div>
                                                    @if ($errors->has('agent_mailgun_password'))
                                                        <span class="text-danger"> {{ $errors->first('agent_mailgun_password') }} </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                                <div class="form-footer mt-4">
                                    <button type="submit" class="btn btn-primary me-1"><i class="fas fa-edit"></i> {{__('Update')}}</button>
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

