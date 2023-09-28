@extends('layouts.auth')
@section('title','Component')
<link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">

@section('content')
<div class="main-content container-fluid">

    <div class="page-title pb-3">
        <h3 class="d-inline me-2">{{__('Component')}}</h3>
    </div>

    <div class="clearfix"></div>
    <section class="section">

        <div class="row mb-2">
            <div class="col-12 col-md-4">
                <div id="" class="card box-shadow">
                    <div class="card-header">
                        <h4 class="card-title d-flex align-items-start flex-column">
                            <span class="card-label">Today’s Events</span>
                            <small class="">24 events on all activities</small>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-5">
                                <span class="text-primary icon-mega">
                                    <i class="fab fa-telegram"></i>
                                </span>
                            </div>
                            <div class="m-0">
                                <h4 class="fs-5 mb-3 text-muted">Ruby on Rails</h4>
                                <div class="d-flex d-grid gap-5">
                                    <div class="d-flex flex-column flex-shrink-0">
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-primary text-primary px-2 py-1 me-2">
                                                <i class="fas fa-user-circle"></i>
                                            </span>
                                            72 users
                                        </span>
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-warning text-warning px-2 py-1 me-2">
                                                <i class="fas fa-shopping-cart"></i>
                                            </span>
                                            72 orders
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column flex-shrink-0">
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-success text-success px-2 py-1 me-2">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                            8 done
                                        </span>
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-danger text-danger px-2 py-1 me-2">
                                                <i class="fas fa-coins"></i>
                                            </span>
                                            $96
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-4 bg-light-primary">
                        <div class="mb-1">
                            <a href="#" class="btn btn-sm bg-white me-2">Details</a>
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">Join Event</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div id="" class="card box-shadow">
                    <div class="card-header">
                        <h4 class="card-title d-flex align-items-start flex-column">
                            <span class="card-label">Today’s Events</span>
                            <small class="">24 events on all activities</small>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-5">
                                <span class="text-warning icon-mega">
                                    <i class="fas fa-briefcase"></i>
                                </span>
                            </div>
                            <div class="m-0">
                                <h4 class="fs-5 mb-3 text-muted">Ruby on Rails</h4>
                                <div class="d-flex d-grid gap-5">
                                    <div class="d-flex flex-column flex-shrink-0">
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-primary text-primary px-2 py-1 me-2">
                                                <i class="fas fa-user-circle"></i>
                                            </span>
                                            72 users
                                        </span>
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-warning text-warning px-2 py-1 me-2">
                                                <i class="fas fa-shopping-cart"></i>
                                            </span>
                                            72 orders
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column flex-shrink-0">
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-success text-success px-2 py-1 me-2">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                            8 done
                                        </span>
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-danger text-danger px-2 py-1 me-2">
                                                <i class="fas fa-coins"></i>
                                            </span>
                                            $96
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-4 bg-light-warning">
                        <div class="mb-1">
                            <a href="#" class="btn btn-sm bg-white me-2">Details</a>
                            <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">Join Event</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div id="" class="card box-shadow">
                    <div class="card-header">
                        <h4 class="card-title d-flex align-items-start flex-column">
                            <span class="card-label">Today’s Events</span>
                            <small class="">24 events on all activities</small>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-5">
                                <span class="text-success icon-mega">
                                    <i class="fab fa-whatsapp"></i>
                                </span>
                            </div>
                            <div class="m-0">
                                <h4 class="fs-5 mb-3 text-muted">Ruby on Rails</h4>
                                <div class="d-flex d-grid gap-5">
                                    <div class="d-flex flex-column flex-shrink-0">
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-primary text-primary px-2 py-1 me-2">
                                                <i class="fas fa-user-circle"></i>
                                            </span>
                                            72 users
                                        </span>
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-warning text-warning px-2 py-1 me-2">
                                                <i class="fas fa-shopping-cart"></i>
                                            </span>
                                            72 orders
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column flex-shrink-0">
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-success text-success px-2 py-1 me-2">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                            8 done
                                        </span>
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-danger text-danger px-2 py-1 me-2">
                                                <i class="fas fa-coins"></i>
                                            </span>
                                            $96
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-4 bg-light-success">
                        <div class="mb-1">
                            <a href="#" class="btn btn-sm bg-white me-2">Details</a>
                            <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">Join Event</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-12 col-md-3">
                <div id="" class="card box-shadow">
                    <div class="card-header">
                        <h4 class="card-title d-flex align-items-start flex-column">
                            <span class="card-label">Today’s Events</span>
                            <small class="">24 events on all activities</small>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-4">
                                <span class="text-primary icon-mega">
                                    <i class="fab fa-telegram"></i>
                                </span>
                            </div>
                            <div class="m-0">
                                <h4 class="fs-5 mb-3 text-muted">Ruby on Rails</h4>
                                <div class="d-flex d-grid gap-5">
                                    <div class="d-flex flex-column flex-shrink-0">
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-primary text-primary px-2 py-1 me-2">
                                                <i class="fas fa-user-circle"></i>
                                            </span>
                                            72 users
                                        </span>
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-warning text-warning px-2 py-1 me-2">
                                                <i class="fas fa-shopping-cart"></i>
                                            </span>
                                            72 orders
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-4 bg-light-primary">
                        <div class="mb-1">
                            <a href="#" class="btn btn-sm bg-white me-2">Details</a>
                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">Join Event</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div id="" class="card box-shadow">
                    <div class="card-header">
                        <h4 class="card-title d-flex align-items-start flex-column">
                            <span class="card-label">Today’s Events</span>
                            <small class="">24 events on all activities</small>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-4">
                                <span class="text-warning icon-mega">
                                    <i class="fas fa-briefcase"></i>
                                </span>
                            </div>
                            <div class="m-0">
                                <h4 class="fs-5 mb-3 text-muted">Ruby on Rails</h4>
                                <div class="d-flex d-grid gap-5">
                                    <div class="d-flex flex-column flex-shrink-0">
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-primary text-primary px-2 py-1 me-2">
                                                <i class="fas fa-user-circle"></i>
                                            </span>
                                            72 users
                                        </span>
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-warning text-warning px-2 py-1 me-2">
                                                <i class="fas fa-shopping-cart"></i>
                                            </span>
                                            72 orders
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-4 bg-light-warning">
                        <div class="mb-1">
                            <a href="#" class="btn btn-sm bg-white me-2">Details</a>
                            <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">Join Event</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div id="" class="card box-shadow">
                    <div class="card-header">
                        <h4 class="card-title d-flex align-items-start flex-column">
                            <span class="card-label">Today’s Events</span>
                            <small class="">24 events on all activities</small>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-4">
                                <span class="text-success icon-mega">
                                    <i class="fab fa-whatsapp"></i>
                                </span>
                            </div>
                            <div class="m-0">
                                <h4 class="fs-5 mb-3 text-muted">Ruby on Rails</h4>
                                <div class="d-flex d-grid gap-5">
                                    <div class="d-flex flex-column flex-shrink-0">
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-primary text-primary px-2 py-1 me-2">
                                                <i class="fas fa-user-circle"></i>
                                            </span>
                                            72 users
                                        </span>
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-warning text-warning px-2 py-1 me-2">
                                                <i class="fas fa-shopping-cart"></i>
                                            </span>
                                            72 orders
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-4 bg-light-success">
                        <div class="mb-1">
                            <a href="#" class="btn btn-sm bg-white me-2">Details</a>
                            <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">Join Event</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div id="" class="card box-shadow">
                    <div class="card-header">
                        <h4 class="card-title d-flex align-items-start flex-column">
                            <span class="card-label">Today’s Events</span>
                            <small class="">24 events on all activities</small>
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-4">
                                <span class="text-success icon-mega">
                                    <i class="fab fa-whatsapp"></i>
                                </span>
                            </div>
                            <div class="m-0">
                                <h4 class="fs-5 mb-3 text-muted">Ruby on Rails</h4>
                                <div class="d-flex d-grid gap-5">
                                    <div class="d-flex flex-column flex-shrink-0">
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-primary text-primary px-2 py-1 me-2">
                                                <i class="fas fa-user-circle"></i>
                                            </span>
                                            72 users
                                        </span>
                                        <span class="d-flex align-items-center mb-2">
                                            <span class="bg-light-warning text-warning px-2 py-1 me-2">
                                                <i class="fas fa-shopping-cart"></i>
                                            </span>
                                            72 orders
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer py-4 bg-light-success">
                        <div class="mb-1">
                            <a href="#" class="btn btn-sm bg-white me-2">Details</a>
                            <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">Join Event</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-12">
                <div class="card card-icon-bg-md border-light box-shadow pb-0" id="card_card-icon-bg-md_border-primary_box-shadow" >
                    <div class="card-body bg-light ps-4 pe-2" id="card-body_bg-light-purple_ps-4_pe-2">
                        <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-center my-2">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-paper-plane text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$2,034</div>
                                        <div class="fs-6 text-muted">Sales</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center my-2">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-layer-group text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$706</div>
                                        <div class="fs-6 text-muted">Commision</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center my-2">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-shopping-cart text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$49</div>
                                        <div class="fs-6 text-muted">Average</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center my-2">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-user-circle text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$5.8M</div>
                                        <div class="fs-6 text-muted">All Time</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-12 col-md-3">
                <div class="card card-icon-bg-sm border-secondary box-shadow">
                    <div class="card-header bg-light-purple"><h4 class="card-title">Summary</h4></div>
                    <div class="card-body bg-light-purple ps-4 pe-2">
                        <div class="row mb-5">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-paper-plane text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$2,034</div>
                                        <div class="fs-6 text-muted">Sales</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-layer-group text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$706</div>
                                        <div class="fs-6 text-muted">Commision</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-shopping-cart text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$49</div>
                                        <div class="fs-6 text-muted">Average</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-user-circle text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$5.8M</div>
                                        <div class="fs-6 text-muted">All Time</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card card-icon-bg-sm border-success box-shadow">
                    <div class="card-header bg-light-success"><h4 class="card-title">Summary</h4></div>
                    <div class="card-body bg-light-success ps-4 pe-2">
                        <div class="row mb-5">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-paper-plane text-success"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$2,034</div>
                                        <div class="fs-6 text-muted">Sales</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-layer-group text-success"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$706</div>
                                        <div class="fs-6 text-muted">Commision</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <xero class="symbol-label bg-white">
                                            <i class="fas fa-shopping-cart text-success"></i>
                                        </xero>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$49</div>
                                        <div class="fs-6 text-muted">Average</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-user-circle text-success"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$5.8M</div>
                                        <div class="fs-6 text-muted">All Time</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card card-icon-bg-sm border-primary box-shadow">
                    <div class="card-header bg-light-primary"><h4 class="card-title">Summary</h4></div>
                    <div class="card-body bg-light-primary ps-4 pe-2">
                        <div class="row mb-5">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-paper-plane text-info"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$2,034</div>
                                        <div class="fs-6 text-muted">Sales</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-layer-group text-info"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$706</div>
                                        <div class="fs-6 text-muted">Commision</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-shopping-cart text-info"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$49</div>
                                        <div class="fs-6 text-muted">Average</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-user-circle text-info"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$5.8M</div>
                                        <div class="fs-6 text-muted">All Time</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card card-icon-bg-sm border-warning box-shadow">
                    <div class="card-header bg-light-warning"><h4 class="card-title">Summary</h4></div>
                    <div class="card-body bg-light-warning ps-4 pe-2">
                        <div class="row mb-5">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-paper-plane text-warning"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$2,034</div>
                                        <div class="fs-6 text-muted">Sales</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-layer-group text-warning"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$706</div>
                                        <div class="fs-6 text-muted">Commision</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-shopping-cart text-warning"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$49</div>
                                        <div class="fs-6 text-muted">Average</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-white">
                                            <i class="fas fa-user-circle text-warning"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$5.8M</div>
                                        <div class="fs-6 text-muted">All Time</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-12 col-md-4 d-none">
                <div class="card card-icon-bg bg-light-purple box-shadow">
                    <div class="card-body">
                        <div class="card-icon-container">
                            <i class="fas fa-shopping-cart text-primary"></i>
                        </div>
                        <a href="#" class="card-title fw-bold fs-5 text-muted">Meeting Schedule</a>
                        <div class="fw-bold text-primary my-6">3:30PM - 4:20PM</div>
                        <p class="text-dark-75 fw-semibold fs-5 m-0 mt-3">Create a headline that is informative
                        <br>and will capture readers</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card card-icon-bg bg-light-warning box-shadow">
                    <div class="card-body">
                        <div class="card-icon-container">
                            <i class="far fa-check-circle text-warning"></i>
                        </div>
                        <a href="#" class="card-title fw-bold fs-5 text-muted">Meeting Schedule</a>
                        <div class="fw-bold text-primary my-6">3:30PM - 4:20PM</div>
                        <p class="text-dark-75 fw-semibold fs-5 m-0 mt-3">Create a headline that is informative
                        <br>and will capture readers</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card card-icon-bg bg-light-info box-shadow">
                    <div class="card-body">
                        <div class="card-icon-container">
                            <i class="far fa-user-circle text-muted"></i>
                        </div>
                        <a href="#" class="card-title fw-bold fs-5 text-muted">Meeting Schedule</a>
                        <div class="fw-bold text-primary my-6">3:30PM - 4:20PM</div>
                        <p class="text-dark-75 fw-semibold fs-5 m-0 mt-3">Create a headline that is informative
                        <br>and will capture readers</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card card-icon-bg bg-light-primary box-shadow">
                    <div class="card-body">
                        <div class="card-icon-container">
                            <i class="fab fa-telegram text-primary"></i>
                        </div>
                        <a href="#" class="card-title fw-bold fs-5 text-muted">Meeting Schedule</a>
                        <div class="fw-bold text-primary my-6">3:30PM - 4:20PM</div>
                        <p class="text-dark-75 fw-semibold fs-5 m-0 mt-3">Create a headline that is informative
                        <br>and will capture readers</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card card-icon-bg bg-light-danger box-shadow">
                    <div class="card-body">
                        <div class="card-icon-container">
                            <i class="far fa-question-circle text-danger"></i>
                        </div>
                        <a href="#" class="card-title fw-bold fs-5 text-muted">Meeting Schedule</a>
                        <div class="fw-bold text-primary my-6">3:30PM - 4:20PM</div>
                        <p class="text-dark-75 fw-semibold fs-5 m-0 mt-3">Create a headline that is informative
                        <br>and will capture readers</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card card-icon-bg bg-light-success box-shadow">
                    <div class="card-body">
                        <div class="card-icon-container">
                            <i class="fab fa-whatsapp text-success"></i>
                        </div>
                        <a href="#" class="card-title fw-bold fs-5 text-muted">Meeting Schedule</a>
                        <div class="fw-bold text-primary my-6">3:30PM - 4:20PM</div>
                        <p class="text-dark-75 fw-semibold fs-5 m-0 mt-3">Create a headline that is informative
                        <br>and will capture readers</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-12 col-md-4">
                 <div class="card card-icon-bg-lg">
                    <div class="card-header bg-primary bg-gradient p-0">
                        <h4 class="card-title text-white p-4">Card Header</h4>
                         <canvas id="chart" height="100px" class="mt-4"></canvas>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row g-0">
                            <div class="col bg-light-warning px-4 py-4 rounded-4 me-4 mb-4">
                                <span class="text-warning d-block my-2">
                                     <i class="fas fa-chart-bar fs-3"></i>
                                </span>
                                <a href="#" class="text-warning fw-semibold fs-6">Weekly Sales</a>
                            </div>
                            <div class="col bg-light-primary px-4 py-4 rounded-4 mb-4">
                                <span class="text-primary d-block my-2">
                                     <i class="fas fa-briefcase fs-3"></i>
                                </span>
                                <a href="#" class="text-primary fw-semibold fs-6">New Projects</a>
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col bg-light-danger px-4 py-4 rounded-4 me-4">
                                <span class="text-danger d-block my-2">
                                    <i class="fas fa-layer-group fs-3"></i>
                                </span>
                                <a href="#" class="text-danger fw-semibold fs-6 mt-2">Item Orders</a>
                            </div>
                            <div class="col bg-light-success px-4 py-4 rounded-4">
                                <span class="text-success d-block my-2">
                                    <i class="fas fa-bug fs-3"></i>
                                </span>
                                <a href="#" class="text-success fw-semibold fs-6 mt-2">Bug Reports</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card card-icon-bg-md">
                    <div class="card-header"><h4 class="card-title">Card Header</h4></div>
                    <div class="card-body">
                        <div class="row mb-5">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="fas fa-paper-plane text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$2,034</div>
                                        <div class="fs-6 text-muted">Author Sales</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-light-danger">
                                            <i class="fas fa-layer-group text-danger"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$706</div>
                                        <div class="fs-6 text-muted">Commision</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="row mb-5">
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-light-info bg-light-success">
                                            <i class="fas fa-bullhorn text-success"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$2,034</div>
                                        <div class="fs-6 text-muted">Author Sales</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-light-warning">
                                            <i class="fas fa-briefcase text-warning"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$706</div>
                                        <div class="fs-6 text-muted">Commision</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-center me-2">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-light-success">
                                            <i class="fas fa-shopping-cart text-success"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$49</div>
                                        <div class="fs-6 text-muted">Average Bid</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center me-2">
                                    <div class="symbol symbol-50px me-3">
                                        <div class="symbol-label bg-light-info">
                                            <i class="fas fa-user-circle text-info"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fs-6 text-dark fw-bold">$5.8M</div>
                                        <div class="fs-6 text-muted">All Time Sales</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer px-0 pt-4 pb-0">
                        <canvas id="chart3" height="100px"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                 <div class="card card-icon-bg-lg">
                    <div class="card-header bg-warning bg-gradient p-0">
                        <h4 class="card-title text-white p-4">Card Header</h4>
                        <canvas id="chart2" height="100px"></canvas>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row g-0">
                            <div class="col bg-light-warning px-3 py-3 rounded-4 me-3 mb-3">
                                <span class="text-warning d-block my-2">
                                     <i class="fas fa-chart-bar fs-3"></i>
                                </span>
                                <a href="#" class="text-warning fw-semibold fs-6">Weekly Sales</a>
                            </div>
                            <div class="col bg-light-primary px-3 py-3 rounded-4 me-3 mb-3">
                                <span class="text-primary d-block my-2">
                                     <i class="fas fa-briefcase fs-3"></i>
                                </span>
                                <a href="#" class="text-primary fw-semibold fs-6">New Projects</a>
                            </div>
                            <div class="col bg-body px-3 py-3 rounded-4 mb-3">
                                <span class="text-dark d-block my-2">
                                     <i class="fas fa-user-circle fs-3"></i>
                                </span>
                                <a href="#" class="text-dark fw-semibold fs-6">New Users</a>
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col bg-light-danger px-3 py-3 rounded-4 me-3">
                                <span class="text-danger d-block my-2">
                                    <i class="fas fa-layer-group fs-3"></i>
                                </span>
                                <a href="#" class="text-danger fw-semibold fs-6 mt-2">Item Orders</a>
                            </div>
                            <div class="col bg-light-success px-3 py-3 rounded-4 me-3">
                                <span class="text-success d-block my-2">
                                    <i class="fas fa-bug fs-3"></i>
                                </span>
                                <a href="#" class="text-success fw-semibold fs-6 mt-2">Bug Reports</a>
                            </div>
                            <div class="col bg-light-info px-3 py-3 rounded-4">
                                <span class="text-info d-block my-2">
                                    <i class="fas fa-check-circle fs-3"></i>
                                </span>
                                <a href="#" class="text-info fw-semibold fs-6 mt-2">Completed</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-12 col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title mb-0 d-inline">Bot Connected</h4>
                        <small class="text-muted">Lifetime</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-inline-block">
                                <div class="d-md-flex first-block-head">
                                    <h4 class="mb-0 me-3"><i class="fab fa-telegram-plane text-blue"></i> 2</h4>
                                    <h4 class="mb-0"><i class="fab fa-whatsapp text-success"></i> 1</h4>
                                </div>
                            </div>
                            <div class="d-inline-block">
                                <i class="fas fa-robot text-blue icon-lg"></i>
                            </div>
                        </div>

                        <div class="pt-4">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="block-head">
                                        <span><i class="fab fa-telegram-plane text-blue"></i> 41</span>
                                        <span><i class="fab fa-whatsapp text-success"></i> 8</span>
                                    </h5>
                                    <span>Flow</span>
                                </div>

                                <div class="col-6 text-end">
                                    <h5 class="block-head">
                                        <span><i class="fab fa-telegram-plane text-blue"></i> 16</span>
                                        <span><i class="fab fa-whatsapp text-success"></i> 0</span>
                                    </h5>
                                    <span>Short-link</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card mb-2">
                    <div class="card-body">
                        <h4 class="card-title mb-0 d-inline">Subscriber</h4>
                        <small class="text-muted">Sep 2022</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-inline-block">
                                <div class="d-md-flex first-block-head">
                                    <h4 class="mb-0 me-3"><i class="fab fa-telegram-plane text-blue"></i> 0</h4>
                                    <h4 class="mb-0"><i class="fab fa-whatsapp text-success"></i> 10</h4>
                                </div>
                            </div>
                            <div class="d-inline-block">
                                <i class="fas fa-user-circle text-primary icon-lg"></i>
                            </div>
                        </div>

                        <div class="pt-4">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="block-head">
                                        <span><i class="fab fa-telegram-plane text-blue"></i> 0</span>
                                        <span><i class="fab fa-whatsapp text-success"></i> 10</span>
                                    </h5>
                                    <span>Subscribed</span>
                                </div>
                                <div class="col-6 text-end">
                                    <h5 class="block-head">
                                        <span><i class="fab fa-telegram-plane text-blue"></i> 0</span>
                                        <span><i class="fab fa-whatsapp text-success"></i> 0</span>
                                    </h5>
                                    <span>Unsubscribed</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card mb-2">
                    <div class="card-body">
                        <h4 class="card-title mb-0 d-inline">Broadcast</h4>
                        <small class="text-muted">Sep 2022</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-inline-block">
                                <div class="d-md-flex first-block-head">
                                    <h4 class="mb-0 me-3"><i class="fab fa-telegram-plane text-blue"></i> 0</h4>
                                    <h4 class="mb-0"><i class="fab fa-whatsapp text-success"></i> 3</h4>
                                </div>
                            </div>
                            <div class="d-inline-block">
                                <i class="fas fa-bullhorn text-success icon-lg"></i>
                            </div>
                        </div>

                        <div class="pt-4">
                            <div class="row">

                                <div class="col-6">
                                    <h5 class="block-head">
                                        <span><i class="fab fa-telegram-plane text-blue"></i> 0</span>
                                        <span><i class="fab fa-whatsapp text-success"></i> 11</span>
                                    </h5>
                                    <span>Sent</span>
                                </div>
                                <div class="col-6 text-end">
                                    <h5 class="block-head">
                                        <span><i class="fab fa-telegram-plane text-blue"></i> 0</span>
                                        <span><i class="fab fa-whatsapp text-success"></i> 1</span>
                                    </h5>
                                    <span>Pending</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2 ">
            <div class="col-12">
                <div class="card card-statistics mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <div class="statistics-item">
                                <p>
                                    <i class="text-white fas fa-briefcase me-2"></i>
                                    Ecommerce Store
                                </p>
                                <h2>4</h2>

                            </div>
                            <div class="statistics-item">
                                <p>
                                    <i class="text-white fas fa-dollar-sign me-2"></i>
                                    Earnings (Sep 2022)
                                </p>
                                <h2>$ 0</h2>

                            </div>
                            <div class="statistics-item">
                                <p>
                                    <i class="text-white fas fa-shopping-cart me-2"></i>
                                    Order
                                </p>
                                <h2>0</h2>

                            </div>

                            <div class="statistics-item">
                                <p>
                                    <i class="text-white fas fa-check-circle me-2"></i>
                                    Checkout
                                </p>
                                <h2>0</h2>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection


@push('scripts-footer')
<script src="{{ asset('assets/vendors/chartjs/Chart.min.js') }}"></script>
<script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/heatmap/js/component.js') }}"></script>
@endpush

