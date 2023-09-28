@extends('layouts.heatsketchdocs')
@section('title', 'Live Users')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <ul id="submenu">


                </ul>
                <div class="section-header">
                    <h1 class="main-header" id="package_role">{{__("Packages")}}</h1>
                </div>
                <hr class="main-hr"/>

                <ul class="list-group">
                    <li class="list-group-item">{{__("HeatSketch has a Package management system. With the Package management system, you can create subscription packages.")}}</li>
                    <li class="list-group-item">{{__("To create a new package and edit the predefined packages, go to package menu at the left side of the Dashboard.")}}</li>
                    <li class="list-group-item">{{__("Click on the Create button.")}}</li>

                </ul>

                <img src={{asset("assets/docs/heatsketch_images/packages/create_package.png")}} class="img-fluid"/>


                <div class="alert alert-primary" role="alert">
                    {{__("Now fill out the Create Subscription Package form. And click on the Create button.")}}
                </div>



                <img src={{asset("assets/docs/heatsketch_images/packages/create_subscription_package.png")}} class="img-fluid"/>
                <br><br>
                <div class="alert alert-primary" role="alert">
                    {{__("Instantly, the package will be created.")}}
                </div>


            </div>
        </section>
    </div>

@endsection
