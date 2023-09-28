@extends('layouts.heatsketchdocs')
@section('title', 'Live Users')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <ul id="submenu">


                </ul>
                <div class="section-header ">
                    <h1 class="main-header">{{ __("Live Users") }}</h1>
                </div>
                <hr class="main-hr"/>

                <div class="alert alert-primary" role="alert"> {{__("In the Live User section, you can see the different types of information about live users and the interaction of live users with your website in graphical representation.")}}

                </div>

                <img src="{{asset('assets/docs/heatsketch_images/live_users/live_users.png')}}" class="img-fluid"
                />
                <hr />

            </div>
        </section>
    </div>

@endsection
