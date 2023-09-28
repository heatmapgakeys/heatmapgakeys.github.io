@extends("layouts.auth")
@section("title",__("Affiliate Settings"))

@section('content')

<div class="main-content container-fluid">
    <div class="page-title pb-3">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last d-flex">
                <h3>{{__('Settings')}}</h3>
            </div>
        </div>
    </div>
    <section class="section">
    	<div class="row grid-margin">
    		<div class="col-12">
                <div class="card card-icon-bg-md border-light box-shadow pb-0" id="card_card-icon-bg-md_border-primary_box-shadow" >
                    <div class="card-body bg-light ps-4 pe-2" id="card-body_bg-light-purple_ps-4_pe-2">
                        <div class="row">
                            <div class="col" data-bs-toggle='tooltip' title="<?php echo __("Affiliate will get commission on every user signup who have come through the affiliation link."); ?>">
                                <div class="d-flex align-items-center my-2">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-user-plus text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">{{ $curency_icon.' '.$signup_amount }}</div>
                                        <div class="fs-6 text-muted">{{__('Signup Commission')}} </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col" data-bs-toggle='tooltip' title="<?php echo __("Affiliate will get fixed/percentage commission on package buying."); ?>">
                                <div class="d-flex align-items-center my-2">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-credit-card text-warning"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">{{ ucfirst($payment_commission_type) }}</div>
                                        <div class="fs-6 text-muted">{{__('Payment Commission')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col" data-bs-toggle='tooltip' title="<?php echo __("Affiliate will get commission on every user package buying who have come through the affiliation link."); ?>">
                                <div class="d-flex align-items-center my-2">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-coins text-success"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <?php 
                                            if($payment_commission_type == 'percentage') 
                                                $payment_commission_amount = $payment_commission_amount.' %';
                                            else
                                                $payment_commission_amount = $curency_icon.' '.$payment_commission_amount;
                                        ?>
                                        <div class="fs-6 text-dark fw-bold">{{ $payment_commission_amount }}</div>
                                        <div class="fs-6 text-muted">{{__('Amount')}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    		</div>
    	</div>
    	<div class="row">
    	    <div class="col-12 col-lg-9 order-2 order-lg-1">
    	        <div class="card">
    	            <div class="card-header">
    	                <h4><i class="fas fa-link"></i> <?php echo __('Affiliate Link'); ?></h4>
    	            </div>
    	            <div class="card-body">
    	                <div class="row">
    	                    <div class="col-12">
    	                        <div class="text-center" id="gif_div">
    	                            <img width="30%" class="center-block" src="<?php echo asset('assets/images/pre-loader/loading-animations.gif'); ?>" alt="Processing...">
    	                        </div>
    	                        <div class="link_div_class"  id="link_div">
    	                            <pre class="language-javascript"><code class="dlanguage-javascript"><span class="token keyword"><?php echo $aff_link; ?></span></code></pre>
    	                        </div>
    	                    </div>
    	                </div>
    	            </div>
    	        </div>
    	    </div>
            @include('affiliate.affiliate_user.sidebar')
    	</div>

    </section>
</div>


@endsection


@push('scripts-footer')
    
<script src="{{ asset('assets/heatmap/js/affiliate/settings.js') }}"></script>

@endpush


@push('styles-footer')

<link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">

@endpush