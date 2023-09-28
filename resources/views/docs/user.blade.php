@extends('layouts.heatsketchdocs')
@section('title', 'User')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <ul id="submenu">


                </ul>
                <div class="section-header">
                    <h1 id="user">User</h1>
                </div>
                <hr class="main-hr"/>

                <ul class="list-group">
                    <li class="list-group-item">{{__("HeatSketch has a User management system. With the User management system, you can create users easily.")}}</li>
                    <li class="list-group-item">{{__("To create a new new user and edit the predefined users, go to User menu at the left side of the Dashboard.")}}</li>
                    <li class="list-group-item">{{__("Click on the Create button.")}}</li>

                </ul>

                <img
                    src={{asset("assets/docs/heatsketch_images/user/create_users.png")}}
                    class="img-fluid"
                />

                <div class="alert alert-primary" role="alert">
                    {{__("Now fill out the Create User form. And click on the Create button.")}}
                </div>

                <img
                    src={{asset("assets/docs/heatsketch_images/user/create_user_form.png")}}
                    class="img-fluid"
                />

                <div class="alert alert-primary" role="alert">
                    {{__("Instantly, the user will be created.")}}
                </div>

            </div>
        </section>
    </div>

@endsection
