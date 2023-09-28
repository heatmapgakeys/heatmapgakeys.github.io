<?php
$lang_display = $has_team_access ? __('Packages & Roles') : __('Packages');
$title_display = $is_admin || $is_agent ? $lang_display : __('Team Roles');
?>
@extends('layouts.auth')
@section('title',$title_display)
@section('content')
<div class="main-content container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{$title_display}}

                    @if($has_team_access && ($is_admin || $is_agent))
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown d-inline">
                            <div class="btn-group" role="group">
                                <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-plus-circle"></i> {{__('Create')}}
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li><a class="dropdown-item" href="{{route('create-package')}}">{{__('New Subscription Package')}}</a></li>
                                    <li class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{route('create-package')}}?type=team">{{__('New Team Role')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    @elseif($has_team_access) <a href="{{route('create-package')}}?type=team" class="btn btn-outline-primary"><i class="fas fa-plus-circle"></i> {{__('Create')}}</a>
                    @else <a href="{{route('create-package')}}" class="btn btn-outline-primary"><i class="fas fa-plus-circle"></i> {{__('Create')}}</a>
                    @endif
                </h3>
                <p class="text-subtitle text-muted">{{__('List of user packages/roles')}}</p>
            </div>
        </div>
    </div>
    @if (session('save_package_status')=='1')
        <div class="alert alert-success">
            <h4 class="alert-heading">{{__('Successful')}}</h4>
            <p> {{ __('Package/role has been saved successfully.') }}</p>
        </div>
    @elseif (session('save_package_status')=='0')
        <div class="alert alert-danger">
            <h4 class="alert-heading">{{__('Failed')}}</h4>
            <p> {{ __('Something went wrong. Failed to save package/role.') }}</p>
        </div>
    @endif
    <section class="section">
        <div class="card">
            <div class="card-body data-card">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group mb-3" id="searchbox">
                            <?php $two_block=false;?>
                            @if($has_team_access)
                                <?php $two_block=true;?>
                                    <div class="input-group-prepend">
                                        <select class="form-control select2" id="search_package_type">
                                            <option value="">{{__("Any Type")}}</option>
                                            <option value="subscription">{{__("Subscription")}}</option>
                                            <option value="Team">{{__("Team")}}</option>
                                        </select>
                                    </div>
                            @endif
                            <div class="input-group-prepend">
                                <input type="text" class="form-control no-radius" autofocus id="search_value" name="search_value" placeholder="{{__("Search...")}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class='table table-hover table-bordered table-sm w-100' id="mytable" >
                        <thead>
                        <tr class="table-light">
                            <th>#</th>
                            <th>{{__("Package ID") }}</th>
                            <th>{{__("Package/Role") }}</th>
                            <th>{{__("Type") }}</th>
                            <th>{{__("Price") }} - <?php echo isset($payment_config->currency) ? $payment_config->currency : 'USD';?></th>
                            <th>{{__("Validity") }} - {{__("days") }}</th>
                            <th>{{__("Default") }}</th>
                            <th id="th_id-id">{{__("Actions") }}</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection

@push('styles-header')
<link rel="stylesheet" href="{{ asset('assets/css/list-package-blade.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">


@endpush

@push('scripts-footer')
<script src="{{ asset('assets/js/pages/subscription/package.list-package.js') }}"></script>
@endpush
