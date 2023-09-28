@extends('layouts.auth')
@section('title',__('Withdrawal Methods'))

@section("content")

    <div class="main-content container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>{{ __("Withdrawal Methods") }}
                        <span id="subtitle"></span>
                        <a href="#" target="_BLANK" class="btn btn-outline-primary add_method"><i class="fas fa-plus-circle"></i> {{ __("Create") }}</a>
                    </h3>
                    <p class="text-subtitle text-muted">{{ __("List of Withdrawal Methods") }}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-9 order-2 order-lg-1">
                <div class="card border-0">
                    <div class="card no-shadow">
                        <div class="card-body data-card">
                            <div class="table-responsive">
                                <table class='table table-hover table-bordered table-sm w-100' id="mytable">
                                    <thead>
                                    <tr class="table-light">
                                        <th>#</th>
                                        <th>{{__("ID") }}</th>
                                        <th>{{__("Method") }}</th>
                                        <th>{{__("Created") }}</th>
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
            @include('affiliate.affiliate_user.sidebar')
        </div>
    </div>



    <div class="modal fade" id="method_details_modal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bbw">
                    <h4 class="modal-title text-center blue"><?php echo __("Method Details"); ?></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body section">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title fw-bold"><?php echo __('Name'); ?></div>
                            <p class="section-lead" id="method_name"></p>

                            <div class="section-title fw-bold"><?php echo __('Details'); ?></div>
                            <div class="section-lead">
                                <p class="alert alert-secondary" id="method_details"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="add_witdrawalMethod_modal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bbw">
                    <h5 class="modal-title text-center blue"><?php echo __("New Method"); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="#" enctype="multipart/form-data" id="witdrawalMethod_add_form" method="post">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label><?php echo __('Method'); ?></label>
                                            <select name="method_type" id="method_type" class="form-control" >
                                                <option value=""><?php echo __('Select Method'); ?></option>
                                                <option value="paypal"><?php echo __('PayPal'); ?></option>
                                                <option value="bank_account"><?php echo __('bKash'); ?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12" id="paypal_email_div" >
                                        <div class="form-group">
                                            <label><?php echo __('PayPal Email'); ?></label>
                                            <input type="email" class="form-control" name="paypal_email" id="paypal_email">
                                        </div>
                                    </div>

                                    <div class="col-12" id="bank_acc_div" >
                                        <div class="form-group">
                                            <label><?php echo __('Details'); ?></label>
                                            <textarea class="form-control" name="bank_acc_no" id="bank_acc_no" placeholder="<?php echo __("write your bKash account number, also mention if it is a merchant number."); ?>"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-12 padding-0">
                        <button class="btn btn-primary" id="save_method_info" type="button"><i class="fas fa-save"></i> <?php echo __("Save") ?> </button>
                        <a class="btn btn-light float-end" data-bs-dismiss="modal" aria-hidden="true"><i class="fas fa-times"></i> <?php echo __("Cancel") ?> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_witdrawalMethod_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bbw">
                    <h5 class="modal-title text-center blue"><?php echo __("Update Method"); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="method_update_body"></div>

                <div class="modal-footer bg-whitesmoke action_div">
                    <div class="col-12 padding-0">
                        <button class="btn btn-primary" id="update_method_info" type="button"><i class="fas fa-edit"></i> <?php echo __("Update") ?> </button>
                        <a class="btn btn-light float-end" data-bs-dismiss="modal" aria-hidden="true"><i class="fas fa-times"></i> <?php echo __("Cancel") ?> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts-footer')
    <script src="{{ asset('assets/js/pages/affiliate/withdrawal-methods.js') }}"></script>
@endpush
@push('styles-footer')
<link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">
@endpush