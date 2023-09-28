@extends('layouts.auth')
@section('title',__('Withdrawal Methods'))

@section("content")


<div class="main-content container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>{{ __("Withdrawal Requests") }}
                    <span id="subtitle"></span>
                    <a href="#" target="_BLANK" class="btn btn-outline-primary add_request"><i class="fas fa-plus-circle"></i> {{ __("Create") }}</a>
                </h3>
                <p class="text-subtitle text-muted">{{ __("List of withdrawal Requests") }}</p>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="row">
            <div class="col-12 col-lg-9 order-2 order-lg-1">
                <div class="card card-icon-bg-md border-light box-shadow pb-0" id="card_card-icon-bg-md_border-primary_box-shadow" >
                    <div class="card-body bg-light ps-4 pe-2" id="card-body_bg-light-purple_ps-4_pe-2" >
                        <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-center my-2">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-coins text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">{{ $currency }} {{ $total_earned }}</div>
                                        <div class="fs-6 text-muted">{{__('Earned')}} </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center my-2">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-spinner text-warning"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">{{ $currency }} {{ $pending_money  }}</div>
                                        <div class="fs-6 text-muted">{{__('Pending')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center my-2">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-check-circle text-success"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">{{ $currency }} {{$transfered_money}}</div>
                                        <div class="fs-6 text-muted">{{__('Transferred')}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="card-header">
                            <h4>{{__('Recent Request')}}</h4>
                        </div>
                        <div class="card-body">

                            <div class="card">
                                <div class="card-body" id="table_div">
                                    <form action="{{ route('affiliate-withdrawal-requests-search') }}" method="post">
                                     @csrf
                                        <div class="input-group mb-3" id="searchbox">
                                            <select name="rows_number" class=" form-select" id="rows_number">
                                                <option value="10" <?php if ($per_page == 10) echo 'selected'; ?>><?php echo __('10 items'); ?></option>
                                                <option value="25" <?php if ($per_page == 25) echo 'selected'; ?>><?php echo __('25 items'); ?></option>
                                                <option value="50" <?php if ($per_page == 50) echo 'selected'; ?>><?php echo __('50 items'); ?></option>
                                                <option value="100" <?php if ($per_page == 100) echo 'selected'; ?>><?php echo __('100 items'); ?></option>
                                                <option value="500" <?php if ($per_page == 500) echo 'selected'; ?>><?php echo __('500 items'); ?></option>
                                                <option value="all" <?php if ($per_page == 'all') echo 'selected'; ?>><?php echo __('All items'); ?></option>
                                            </select>
                                            <select name="search_value" class=" form-select" id="search_value">
                                                <option value="" <?php if ($search_value == "") echo 'selected'; ?>><?php echo __('Status'); ?></option>
                                                <option value="0" <?php if ($search_value == "0") echo 'selected'; ?>><?php echo __('Pending'); ?></option>
                                                <option value="1" <?php if ($search_value == "1") echo 'selected'; ?>><?php echo __('Approved'); ?></option>
                                                <option value="2" <?php if ($search_value == "2") echo 'selected'; ?>><?php echo __('Canceled'); ?></option>
                                            </select>
                                            <button type="submit" class="btn btn-outline-primary" id="group_search_submit"><i class="fas fa-search"></i></button>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <?php if(!empty($withdrawal_requests)) : ?>
                                        <?php foreach ($withdrawal_requests as $value) { ?>
                                        <div class="col-12 col-md-4">
                                            <div class="card pointer" data-toggle="tooltip" data-title="<?php echo $value->payment_type; ?>">
                                                <div class="card-body p-0">
                                                    <div class="block_head bg-primary"></div>
                                                    <div class="details_section">
                                                        @if ($value->payment_type == 'PayPal' )
                                                            <div class="d-flex method_info">
                                                                <span><?php echo $value->icon; ?></span>
                                                            </div>
                                                        @else
                                                            <div class="d-flex method_info">
                                                                <span><i class="fas fa-university text-primary"></i></i></span>
                                                            </div>
                                                        @endif
                                                        <div class="text-center request_method"><?php echo $value->payment_type; ?></div>
                                                        <ul class="list-group reques_info_body">
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span><i class="fas fa-bookmark text-primary"></i>&nbsp;&nbsp;<?php echo $value->method_id; ?></span>
                                                                <span class="amount">{{ $currency }} <?php echo $value->requested_amount; ?></span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <span><i class="fas fa-flag text-success"></i> <?php echo __('Status'); ?></span>
                                                                <?php echo $value->request_status_icon; ?>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center mb-3">
                                                                <span><i class="fas fa-star text-warning"></i> <?php echo __('Approved'); ?></span>
                                                                <span class="text-muted text-small"><?php echo $value->completed_at; ?></span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <?php if ($value->status != '1'): ?>
                                                <div class="card-footer text-center bg-light pr-3 pl-3 pt-2 pb-2">
                                                    <button class="btn btn-sm btn-danger float-right delete_request" table_id="<?php echo $value->id; ?>"><i class="fas fa-trash-alt"></i> <?php echo __('Delete'); ?></button>

                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php } ?>
                                        <?php else : ?>
                                        <div class="col-12">
                                            <div class="empty-state p-0">
                                                <img class="img-fluid" width="40%" src="<?php echo asset('assets/img/drawkit/drawkit-nature-man-colour.svg'); ?>" alt="image">
                                                <h2 class="mt-0"><?php echo __("We could not find any data.");?></h2>

                                            </div>
                                        </div>
                                        <?php endif; ?>

                                    </div>
                                    <div class="pagination_div">
                                        {{ $withdrawal_requests->links() }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card box-shadow" id="new_form_div">
                    <div class="card-header">
                        <h4 class="card-title d-flex align-items-start flex-column">
                            <span class="card-label">{{__('New Withdraw Request')}}</span>
                        </h4>
                    </div>
                    <div class="card-body px-4 pb-0">
                        <form action="#" id="new_requests_form" method="post">
                            <input type="hidden" name="tableId" id="tableId" value="">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <div class="input-group-prepend">
                                                    <select name="withdrawal_account" id="withdrawal_account" class="form-control select2">
                                                        <option value="">{{ __('Select withdrawal Account')}}</option>
                                                        <?php foreach ($method_info as $value) {

                                                            if($value->payment_type == 'paypal') {
                                                                echo '<option value="'.$value->id.'"> PayPal : '.$value->paypal_email.'</option>';
                                                            }
                                                            else if($value->payment_type == 'bank_account') {
                                                                echo '<option value="'.$value->id.'"> bKash : '.$value->bank_acc_no.'</option>';
                                                            }
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="fas fa-circle"></i></span>
                                                    <input type="number" class="form-control" placeholder="{{ __('Provide Requested Amount')}}" id="requested_amount" name="requested_amount">
                                                    <input type="hidden" class="form-control" id="previous_amount" name="previous_amount" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer px-4 py-3">
                        <div class="mb-0">
                            <button type="submit" id="add_request_submit" class="btn btn-primary" submit_action="add"><i class="fas fa-save"></i> {{ __('Submit') }}</button>

                        </div>
                    </div>
                </div>

            </div>

            @include('affiliate.affiliate_user.sidebar')

        </div>
    </div>

</div>

@endsection

@push('scripts-footer')
<script>
    var page_title = '{{ $page_title }}';
    var method_numbers = '{{ $count_method_info}}';
    var method_url ='{{ route('affiliate-withdrawal-methods') }}';
    var method_link = "{{ __('Sorry, we do not find any withdrawal methods. Please add atleast one method to issue a request. create method from') }}"+" "+"<a target='_BLANK' href='"+method_url+"'> {{ __('here') }}</a>" ;
    var select_method ="{{ __('Please Select a method.') }}";
    var requested_amount = "{{ __('Please Provide your requested amount') }}";
    var somethingwentwrong = "{{ __('Something went wrong, please try once again.') }}";
    var delete_notice ="{{ __('Do you really want to delete this method?') }}";
</script>
    <script src="{{ asset('assets/js/pages/affiliate/withdrawal-requests.js') }}"></script>
@endpush

@push('styles-footer')

<link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">

@endpush
