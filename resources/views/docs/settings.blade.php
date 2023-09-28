@extends('layouts.heatsketchdocs')
@section('title', 'Settings')
@section('content')

    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <ul id="submenu">
                    <li><a href="#general_settings">{{ __("General") }}</a></li>
                    <li><a href="#email_settings">{{ __("Email") }}</a></li>
                    <li><a href="#google_api">{{__("Google API")}}</a></li>
                    <li><a href="#ip">{{ __("IP") }}</a></li>
                    <li><a href="#s3">{{__("S3 Storage")}}</a></li>
                    <li><a href="#responder">{{__("Responder")}}</a></li>
                    <li><a href="#script">{{ __("Script") }}</a></li>
                    <li><a href="#cron_commands">{{__("Cron")}}</a></li>

                    <li><a href="#payment">{{ __("Payment") }}</a></li>
                    <li><a href="#landing_page_editor">{{ __("Landing") }}</a></li>
                    <li><a href="#language_editor">{{__("Language")}}</a></li>
                </ul>
                <div class="section-header ">
                    <h1 id="general_settings" class="main-header">{{ __("General") }}</h1>
                </div>
                <hr class="main-hr"/>


                <div class="alert alert-primary" role="alert"> {{__("Now select the General tab to configure your brand settings.")}}

                </div>

                <img src="{{asset('assets/docs/heatsketch_images/settings/settings_general.png')}}" class="img-fluid"
                />
                <hr />
                <ol class="list-group list-group-numbered">
                    <li class="list-group-item">{{__("Now fill out your COMPANY NAME, COMPANY ADDRESS, COMPANY EMAIL, COMPANY MOBILE fields.")}}</li>
                    <li class="list-group-item">{{__("Then provide your logo, white logo and favicon.")}}</li>
                    <li class="list-group-item">{{__("Write your product name in the PRODUCT NAME field.")}}</li>
                    <li class="list-group-item">{{__("You can change the Time Zone and Locale.")}}</li>
                    <li class="list-group-item">{{__("Then click on the Save button.")}}</li>

                </ol>


                <div class="section-header">
                    <h1 id="email_settings">{{__("Email")}}</h1>
                </div>

                <hr />
                <p>{{__("Select Email tab to add an Email sender. To add an email sender, first you have to add an email profile.")}}

                </p>
                <ul class="list-group">
                    <li class="list-group-item">{{__("To add an email profile, click on the new button.")}}</li>

                </ul>
                <p></p>
                <img src={{asset("assets/docs/heatsketch_images/settings/email_general.png")}} class="img-fluid" />


                <p>{{__("Instantly, a Modal form will appear with a list of email provider agencies. You have to select the email provider agency you want to add.")}}



                </p>

                <ul class="list-group">
                    <li class="list-group-item">{{__("Select an email provider agency. For example, select smtp.")}}</li>
                    <li class="list-group-item">{{__("Fill-out the modal form and click on the save button.")}}</li>
                    <li class="list-group-item">{{__("the SMTP profile will be added.")}}</li>

                </ul>

                <img
                    src={{asset("assets/docs/heatsketch_images/settings/email_profile_smtp.png")}}
                    class="img-fluid"
                />
                <p>{{__("Likewise, you can add more email profile agencies. And the added email provider agency will be shown in the list.")}}

                </p>
                <p>
                    {{__("Now you can select any of the profiles as the default provider.")}}

                </p>



                <ul class="list-group">
                    <li class="list-group-item">{{__("Click on the default provider field, a drop-down menu of added email provider agencies will arrive.")}}</li>

                    <li class="list-group-item">{{__("From the drop-down menu, you have to select an email provider agency.")}}</li>



                </ul>

                <div class="section-header">
                    <h1 id="google_api">{{__("Google API")}}</h1>
                </div>
                <p>{{__("An API key is a unique identifier that is used to authenticate requests to an API (Application Programming Interface). Google API Key is a special type of API key that is used to authenticate requests to Google's APIs, such as Google Maps API, Google Places API, and Google Translate API.")}}</p>
                <p>{{__("To use any of Google's APIs, you need to have a valid API key. You can obtain a Google API key by creating a project in the Google Cloud Console and enabling the API(s) that you want to use. Once you have a valid API key, you can use it to make requests to the corresponding Google API(s) by including the key in the API request.")}}</p>

                <p>{{__("API keys provide a way for Google to track and control usage of its APIs, ensuring that the APIs are used in compliance with Google's terms of service. They also allow Google to monitor usage and potentially limit access to specific APIs if necessary.")}}</p>


                <p>{!!__("Watch the <a target = '_blank' href='https://www.youtube.com/watch?v=4CeF1k3Sdrw'>video tutorial</a> to know how to get Google API key.")!!}</p>


                <ul class="list-group">
                    <li class="list-group-item">{{__("Select Google API tab to add Google API key.")}}</li>
                    <li class="list-group-item">{{__("Now provide Google API key in the GOOGLE API Key field.")}}</li>
                    <li class="list-group-item">{{__("Now click on the Save button.")}}</li>

                </ul>

                <img src={{asset("assets/docs/heatsketch_images/settings/google_api_key.png")}} class="img-fluid"/>

                <div class="section-header">
                    <h1 id="ip">{{__("IP")}}</h1>
                </div>

                <hr />

                <p>{{__(" To obtain a user's location, you are required to provide an IP2Location key. Similarly, to obtain accurate IP address data for a user, you need to provide an Ip info token.")}}</p>

                <p>{{__("Failure to provide the IP2Location key will result in the inability to retrieve the user's location. Likewise, if you fail to provide the Ip Info Token, you will not be able to obtain accurate IP address data of the user.")}}</p>


                <ul class="list-group">
                    <li class="list-group-item">{{__("Select API tab to add IP2LOCATION API KEY and IP INFO TOKEN")}}</li>
                    <li class="list-group-item">{{__("To get IP2LOCATION API KEY, click on the Where to get IP2LOCATION API KEY? button.")}}</li>
                    <li class="list-group-item">{{__("To get Ip info token, click on the Where to get Ip info token? button.")}} </li>
                    <li class="list-group-item">{{__("In the IP2LOCATION API KEY field, provide the IP2LOCATION API KEY.")}}</li>
                    <li class="list-group-item">{{__("In the Ip info token field, provide Ip info token.")}}</li>
                    <li class="list-group-item">{{__("Click on the Save button.")}}</li>
                </ul>
                <img src={{asset("assets/docs/heatsketch_images/settings/Ip.png")}} class="img-fluid"/>

                <div class="section-header">
                    <h1 id="s3">S3 Storage</h1>
                </div>

                <hr />

                <p>{{__("HeatSketch, a heatmap and session recording tool, requires S3 storage integration to store and manage large volumes of screenshots and session recordings, especially for high-traffic websites or applications. S3 storage provides a cost-effective and scalable storage solution that allows for easy backup and recovery of data.")}}</p>




                <ul class="list-group">
                    <li class="list-group-item">{{__("Select S3 storage, to make S3 integration.")}}</li>
                    <li class="list-group-item">{{__("Now fill out the form.")}}</li>
                    <li class="list-group-item">{{__("Click on the Save button.")}}</li>

                </ul>

                <img src={{asset("assets/docs/heatsketch_images/settings/s3_storage_integration.png")}} class="img-fluid"/>

                <div class="section-header">
                    <h1 id="responder">{{__("Responder")}}</h1>
                </div>
                <hr />

                <ul class="list-group">
                    <li class="list-group-item">{{__("Select Responder to add an autoresponder profile.")}}</li>

                </ul>

                <img
                    src={{asset("assets/docs/heatsketch_images/settings/responder_general.png")}}
                    class="img-fluid"
                />
                <ul class="list-group">
                    <li class="list-group-item">{{__("Click on the new button.")}}</li>

                </ul>
                <p>{{__("For example, select Sendinblue, and fill-out some fields and click on the save button.")}}


                </p>
                <ul class="list-group">
                    <li class="list-group-item">{{__("You have to select an email provider agency. For example, select Sendinblue.")}}</li>
                    <li class="list-group-item"> {{__("Write a profile name in the profile name field.")}}</li>
                    <li class="list-group-item">{{__("Provide an api key in the api key field.")}}</li>
                    <li class="list-group-item"> {{__("Click on the save button.")}}} </li>
                </ul>
                <img
                    src={{asset("assets/docs/heatsketch_images/settings/responder_sendinblue.png")}}
                    class="img-fluid"
                />


                <div class="section-header">
                    <h1 id="script">{{__("Script")}}</h1>
                </div>
                <hr />

                <ul class="list-group">
                    <li class="list-group-item">{{__("Select Script tab to add Analytics code.")}}</li>
                    <li class="list-group-item">{{__("Now fill out the form.")}}</li>
                    <li class="list-group-item">{{__("Click on the save button.")}}</li>

                </ul>

                <p>{{__("Now go to the script and Analytics section and the Script and Analytics page will appear with some fields. If you want to set up Facebook and Google Analytics for your domain, enter Facebook Pixel Id and Google Analytics in the respective fields. Besides, if you can provide HeatSketch WhatsApp short-link unique ID and HeatSketch Telegram short-link unique ID in the respective fields.")}}</p>





                <img src={{asset("assets/docs/heatsketch_images/settings/script_analytics.png")}} class="img-fluid" />


                <div class="section-header">
                    <h1 id="cron_commands">{{__("Cron")}}</h1>
                </div>

                <hr/>
                <ul class="list-group">
                    <li class="list-group-item">{{__("Select CRON tab to see the Cron commands")}}</li>

                </ul>
                <img src={{asset("assets/docs/heatsketch_images/settings/corn_commands.png")}} alt="" class="img-fluid">





                <div class="section-header">
                    <h1 id="payment">{{__("Payment")}}</h1>
                </div>

                <hr />

                <ul class="list-group">
                    <li class="list-group-item">{{__("Select payment tab to set up payment method you want to use to receive payment from your end-users.")}}</li>
                    <li class="list-group-item">{{__("HeatSketch supports multiple payment methods. And you can set up any of the payment methods.")}}</li>

                    <li class="list-group-item">{{__("For example, select PayPal, and fill-out some fields and click  on the save button.")}}</li>

                    <li class="list-group-item">{{__("Instantly, the payment method will be added.")}}</li>

                </ul>

                <img src={{asset("assets/docs/heatsketch_images/settings/payment_settings.png")}}  class="img-fluid" />


                <div class="section-header">
                    <h1 id="landing_page_editor">{{__("Landing")}}</h1>
                </div>

                <hr />

                <ul class="list-group">
                    <li class="list-group-item">{{__("Select Landing tab to customize the landing page.")}}</li>
                    <li class="list-group-item">{{__("To change the image of the landing page, select media option.")}}</li>
                    <li class="list-group-item">{{__("To change the company related information, select company. ")}}</li>
                    <li class="list-group-item">{{__("You can reset the landing page by clicking on the Reset button.")}}</li>
                    <li class="list-group-item">{{__("Well, you can disable landing page by enabling the Disable landing page radio button. ")}}</li>

                </ul>

                <img
                    src={{asset("assets/docs/heatsketch_images/settings/landing_page_media.png")}}
                    class="img-fluid"
                />
                <p></p>
                <img
                    src={{asset("assets/docs/heatsketch_images/settings/landing_page_company.png")}}
                    class="img-fluid"
                />


                <div class="section-header">
                    <h1 id="language_editor">{{__("Language")}}</h1>
                </div>

                <hr />

                <ul class="list-group">
                    <li class="list-group-item">{{__("Select Language tab to add languages.")}}</li>
                    <li class="list-group-item">{{__("Click on the Add button to add language.")}}</li>
                    <li class="list-group-item">{{__("Select a language you want to add and click on the Save button.")}}</li>
                    <li class="list-group-item">{{__("Instantly, the language will be added.")}}</li>
                    <li class="list-group-item">{{__("You can delete a language by clicking on the Trash icon.")}}</li>
                    <li class="list-group-item">{{__("Click on the eye icon to provide translation.")}}</li>
                    <li class="list-group-item">{{__("After providing translated text click on the Apply Changes button.")}}</li>
                    <li class="list-group-item">{{__("To download the translated text as json format, click on the Download button.")}}</li>

                </ul>
                <p></p>

                <img
                    src={{asset("assets/docs/heatsketch_images/settings/add_language.png")}}
                    class="img-fluid"
                />
                <br><br><br>
                <img
                    src={{asset("assets/docs/heatsketch_images/settings/select_a_language.png")}}
                    class="img-fluid"
                />

                <img
                    src={{asset("assets/docs/heatsketch_images/settings/language_added.png")}}
                    class="img-fluid"
                />
                <br><br><br>
                <img
                    src={{asset("assets/docs/heatsketch_images/settings/translation.png")}}
                    class="img-fluid"
                />
                <br><br><br>





            </div>
        </section>
    </div>


@endsection
