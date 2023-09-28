@extends('layouts.heatsketchdocs')
@section('title', 'Domains')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <ul id="submenu">
{{--                    <li><a href="#">{{ __("Domains") }}</a></li>--}}

                </ul>
                <div class="section-header ">
                    <h1 class="main-header">{{ __("Domain") }}</h1>
                </div>
                <hr class="main-hr"/>

                <div class="alert alert-primary" role="alert"> {{__("To use the HeatSketch– a Heatmap and session recordings tool, first you have to add your Domain to the HeatSketch.")}}

                </div>


                <ul class="list-group">

                    <li class="list-group-item">{{__("To add your Domain to the HeatSketch, go to the Domain menu and then click on the New Button.")}}</li>
                    <li class="list-group-item">{{__("In the New Domain modal form, first, select your Domain Prefix.")}}</li>
                    <li class="list-group-item">{{__("Now fill out the form.")}}</li>
                    <li class="list-group-item">{{__("Before filling out the form read the instructions carefully. To get the instruction, hover your mouse over i sign.")}}</li>
                    <li class="list-group-item">{{__("Click on the Add button.")}}</li>
                    <li class="list-group-item">{{__("Instantly, the Modal form will be expanded with two other options– WordPress and Html. Now you have to select WordPress or Html options.")}}</li>

                </ul>



                <img
                    src={{asset("assets/docs/heatsketch_images/domain/domain_first_look.png")}}
                    class="img-fluid"
                />

                <br><br><br>
                <img
                    src={{asset("assets/docs/heatsketch_images/domain/new_domain_form.png")}}
                    class="img-fluid"
                />


                <br><br><br>

                <img
                    src={{asset("assets/docs/heatsketch_images/domain/instruction.png")}}
                    class="img-fluid"
                />

                <br><br><br>


                <img
                    src={{asset("assets/docs/heatsketch_images/domain/form_expanded.png")}}
                    class="img-fluid"
                />

                <br><br><br>

                <ul class="list-group">
                    <li class="list-group-item">{{__("If you select the WordPress option, a modal will appear with some instructions on how to configure Wordpress based website and a Javascript code.")}}</li>

                    <li class="list-group-item">{{__("Read the instruction carefully and copy the javascript code.")}}</li>
                    <li class="list-group-item">{{__("Just follow the instructions step by step.")}}</li>
                </ul>

                <img
                    src={{asset("assets/docs/heatsketch_images/domain/embeded_code.png")}}
                    class="img-fluid"
                />
                <ul class="list-group">
                    <li class="list-group-item">{{__("On the other hand, If you click on the Html option, a modal will appear with some instructions on how to configure Html based website and a Javascript code.")}}</li>
                    <li class="list-group-item">{{__("Read the instruction carefully and copy the javascript code.")}}</li>
                    <li class="list-group-item">{{__("Just follow the instructions step by step.")}}</li>

                </ul>



                <img
                    src= {{asset("assets/docs/heatsketch_images/domain/new_domain_html.png")}}
                    class="img-fluid"
                />

                <p></p>

                <div class="alert alert-primary" role="alert">
                    {{__("Now click on the Back button and you will be redirected to the Domain page. And you will see that the domain has been added to the list of Domains.")}}

                </div>

                <img
                    src= {{asset("assets/docs/heatsketch_images/domain/domain_added.png")}}
                    class="img-fluid"
                />



                <p> </p>
                <ul class="list-group">
                    <li class="list-group-item">{{__("You can delete the domain by clicking on the delete button.")}}</li>
                    <li class="list-group-item">{{__("You can edit domain settings by clicking on the edit button.")}}</li>
                    <li class="list-group-item">{{__("To get the embeded code, click on the script button.")}}</li>
                    <li class="list-group-item">{{__("To stop session recording, click on the pause button.")}}</li>

                </ul>

                <img
                    src= {{asset("assets/docs/heatsketch_images/domain/delete_domain.png")}}
                    class="img-fluid"
                />



            </div>
        </section>
    </div>

@endsection
