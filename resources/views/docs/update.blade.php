@extends('layouts.heatsketchdocs')
@section('title', 'Live Users')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <ul id="submenu">


                </ul>
                <div class="section-header">
                    <h1 class="main-header" id="update">{{__("Update")}}</h1>
                </div>

                <hr class="main-hr" />

                <ul class="list-group">
                    <li class="list-group-item">{{__("To update, select Update tab.")}}</li>
                    <li class="list-group-item">{{__("Click on the Update now button to get update.")}}</li>
                    <li class="list-group-item">{{__("Click on the See Log button to see the Change Log.")}}</li>

                </ul>

                <img src={{asset("assets/docs/heatsketch_images/update/update.png")}} class="img-fluid">

            </div>
        </section>
    </div>

@endsection
