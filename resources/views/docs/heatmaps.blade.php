@extends('layouts.heatsketchdocs')
@section('title', 'Heatmaps')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <ul id="submenu">


                </ul>
                <div class="section-header">
                    <h1 class="main-header" id="heatmap">{{__("Heatmaps")}}</h1>
                </div>
                <hr class="main-hr"/>



                <p>{{__("To see the Heatmap of your Website, click on the Heatmap menu in the Dashboard of HeatSketch. Instantly, the Domain Heatmap page will appear with the click option selected and click heatmap of your website.")}}</p>

                <img
                    src={{asset("assets/docs/heatsketch_images/heatmap/heatmap_first_look.png")}}
                    class="img-fluid"
                />

                <p> {{__("On the page, you will see some initial information about the interactions of your user – total unique sessions, average stay time, and total clicks.")}}</p>



                <img
                    src={{asset("assets/docs/heatsketch_images/heatmap/heatmap_initial_information.png")}}
                    class="img-fluid"
                />


                <p>{{__("If you want to see the click heatmap of your website, select click option. And instantly, a click heatmap of your website will appear.")}}</p>


                <p>{{__("Likewise, If you want to see the move heatmap of your website, select move option. And instantly, a move heatmap of your website will appear.")}}</p>


                <img
                    src={{asset("assets/docs/heatsketch_images/heatmap/move_heatmap.png")}}
                    class="img-fluid"
                />

                <p>{{__("Likewise, If you want to see the Scroll heatmap of your website, select the Scroll option. And instantly, the Scroll heatmap of your website will appear.")}} </p>


                <img
                    src={{asset("assets/docs/heatsketch_images/heatmap/scroll_heatmap.png")}}
                    class="img-fluid"
                />


            </div>
        </section>
    </div>

@endsection
