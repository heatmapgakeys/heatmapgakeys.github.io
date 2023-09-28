@extends('layouts.heatsketchdocs')
@section('title', 'Recordings')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <ul id="submenu">


                </ul>
                <div class="section-header">
                    <h1 class="main-header" id="recording">{{__("Recordings")}}</h1>

                  </div>
                  <hr class="main-hr"/>

                  <p>
                    {{__("To get the Session Recordings of your website, Click on the Recordings menu in the HeatSketch Dashboard.")}}
                  </p>

                  <p>{{__("Instantly, the Domain Recording page will appear. On the page, you will the list of session recordings on your website. ")}}</p>

                <img
                    src= {{asset("assets/docs/heatsketch_images/session_recording/session_recording_page.png")}}
                    class="img-fluid"
                />

                  <p>{{__("On the Session Recording page, you can filter the session recordings by country, browser, os,  device, and specific time.")}}</p>

                  <img
                    src= {{asset("assets/docs/heatsketch_images/session_recording/session_recording_filter.png")}}
                    class="img-fluid"
                  />


                  <p>{{__("In the session recordings list, you can see the countries of the users, the duration of the sessions, the device they are visiting from, Entry time, the IP addresses of the users, and which website they are coming from.")}}</p>

                  <p>{{__("There are some action buttons-- Play, Visited Urls, Download and Delete beside each session recording.")}}</p>

                <img
                    src={{asset("assets/docs/heatsketch_images/session_recording/action_button.png")}}
                  class="img-fluid"
                />


                <p>{{__("By clicking on the play button, you can watch the recording sessions ")}}</p>


                  <img
                  src={{asset("assets/docs/heatsketch_images/session_recording/session_recording_play_video.png")}}
                  class="img-fluid"
              />

                 <p>{{__(" Also, you can see the visited URLs by clicking on the visited URLs.")}}</p>

                  <img src={{asset("assets/docs/heatsketch_images/session_recording/session_recording_visited_url.png")}}
                    class="img-fluid"
                  />



                  <p>{{__("Of course, you can download the session recordings by clicking on the download button.")}}</p>

                  <p>{{__("Of course, you can delete the session recordings by clicking on the delete button.")}}</p>



            </div>
        </section>
    </div>

@endsection
