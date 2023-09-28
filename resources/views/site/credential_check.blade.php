@extends('layouts.auth')
@section('title',__('Settings'))
@section('content') 
<link rel="stylesheet" href="{{ asset('assets/css/pages/credential-check.css') }}">

	<div class="container mt-2 " id="test">
	  <div class="row" id="row">
	    <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-6 offset-xl-3">
	      <div class="login-brand text-center">
	        <a href="{{ url('') }}"><img src="{{ config('app.logo') }}" alt="{{ config('settings.product_name') }}" width="200"></a>
	      </div>

	      <div class="card card-primary mt-2">
	        <div class="card-header"><h4><i class="far fa-copyright"></i>{{  __("Register your software") }}</h4></div>

	        <div class="card-body" id="recovery_form">
	          <p class="text-muted">{{ __("Put purchase code to activate software") }}</p>
	          <form method="POST">
	            <div class="form-group">
	              <label for="email">{{ __("Purchase Code") }}*</label>
	              <input id="purchase_code" type="text" class="form-control" id="purchase_code" name="email" tabindex="1" autofocus>
	              <div class="invalid-feedback">{{ __("Please enter purchase code") }}</div>
	            </div>

	            <div class="form-group">
	              <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
	               <i class="far fa-paper-plane"></i> {{ __("Submit Purchase Code") }}
	              </button>
	            </div>
	          </form>
	        </div>
	      </div>
	    </div>
	  </div>
	</div>


@endsection
@push('scripts-footer')
<script src="{{ asset('assets/js/pages/credential_check.js') }}"></script>
@endpush

