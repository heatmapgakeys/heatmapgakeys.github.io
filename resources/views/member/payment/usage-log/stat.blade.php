<link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">

 
 <div class="row">
    <div class="col-12">
        <div class="card card-icon-bg-md border-light box-shadow pb-0" id="card_card-icon-bg-md_border-primary_box-shadow" >
            <div class="card-body bg-light ps-4 pe-2" id="card-body_bg-light-purple_ps-4_pe-2">
                <div class="row">
                    <div class="col">
                        <div class="d-flex align-items-center my-2">
                            <div class="symbol symbol-50px me-3">
                                <div class="symbol-label bg-white">
                                    <i class="fas fa-shopping-bag text-primary"></i>
                                </div>
                            </div>
                            <div>
                                <div class="fs-6 text-dark fw-bold">{{$package_name}}</div>
                                <div class="fs-6 text-muted">{{__('Package')}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center my-2">
                            <div class="symbol symbol-50px me-3">
                                <div class="symbol-label bg-white">
                                    <i class="fas fa-coins text-warning"></i>
                                </div>
                            </div>
                            <div>
                                <div class="fs-6 text-dark fw-bold">
                                    <?php if($price=="Trial") $price=0; ?>
                                    <?php echo $price>0 ? $curency_icon.number_format($price,'2','.','').'/'.$validity.' '.__("Days") : __('Free');?>
                                </div>
                                <div class="fs-6 text-muted">{{__('Price')}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center my-2">
                            <div class="symbol symbol-50px me-3">
                                <div class="symbol-label bg-white">
                                    <i class="far fa-calendar text-danger"></i>
                                </div>
                            </div>
                            <div>
                                <div class="fs-6 text-dark fw-bold">
                                   <?php echo $price>0 ? date("dS M Y",strtotime($expired_date)) : __('Never'); ?>
                                </div>
                                <div class="fs-6 text-muted">{{__('Expiry')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>