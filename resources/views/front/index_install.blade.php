<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="{{ config('app.favicon') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">

  <!-- Optional theme -->
  <link rel="stylesheet" href="{{asset('assets/cdn/css/bootstrap-theme.min.css')}}" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
  <title>{{ config('app.name') }} - {{__('Installation')}}</title>
</head>
<body>
  <?php 
    /*****Curl******/
    $curl=$mbstring=$safe_mode=$allow_url_fopen=$set_time_limit=$install_txt_display=$env_file_display=$views_file_display =$controller_file_display =$helpers_file_display = $services_file_display = $config_file_display = $assets_file_display =$routes_file_display =$storage_file_display=$symlink=$ziparchive ="<li class='list-group-item list-group-item list-group-item-danger text-light'><i class='fa fa-times-circle'></i> <b>Failed : </b>Could not check.</li>";
    
    $mysql_support="";
    $install_allow = 1;

    if(class_exists('ZipArchive'))
    $ziparchive="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>ziparchive : </b>Supported</li>";
    else
    {
      $install_allow = 0;
      $ziparchive="<li class='list-group-item list-group-item list-group-item-warning'><i class='fa fa-times-circle'></i> <b>mkdir() : </b>Disabled, please enable ziparchive extension</li>";
    }

    if(function_exists('symlink'))
    $symlink="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>symlink() : </b>Supported</li>";
    else
    {
      $install_allow = 0;
      $symlink="<li class='list-group-item list-group-item list-group-item-warning'><i class='fa fa-times-circle'></i> <b>set_time_limit() : </b>Disabled, please enable symlink() function</li>";
    }

    if($install_txt_permission)
      $install_txt_display="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>public/install.txt : </b>Has Write Permission.</li>";
    else
    {
      $install_allow = 0;
      $install_txt_display="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>public/install.txt : </b>It doesn't have write permission.</li>";
    }

    if($env_file_permission)
      $env_file_display="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>.env : </b>Has Write Permission.</li>";
    else
    {
      $install_allow = 0;
      $env_file_display="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>.env : </b>It doesn't have write permission.</li>";
    }

    if($views_file_permission)
      $views_file_display="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>resources/views : </b>Has Write Permission.</li>";
    else
    {
      $install_allow = 0;
      $views_file_display="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>resources/views : </b>It doesn't have write permission.</li>";
    }

    if($controllers_file_permission)
      $controller_file_display="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>app/Http/Controllers : </b>Has Write Permission.</li>";
    else
    {
      $install_allow = 0;
      $controller_file_display="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>app\Http\Controllers : </b>It doesn't have write permission.</li>";
    }

    if($helpers_file_permission)
      $helpers_file_display="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>app/Helpers : </b>Has Write Permission.</li>";
    else
    {
      $install_allow = 0;
      $helpers_file_display="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>app/Helpers : </b>It doesn't have write permission.</li>";
    }

    if($services_file_permission)
      $services_file_display="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>app/Services : </b>Has Write Permission.</li>";
    else
    {
      $install_allow = 0;
      $services_file_display="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>app/Services : </b>It doesn't have write permission.</li>";
    }
    if($config_file_permission)
      $config_file_display="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>config : </b>Has Write Permission.</li>";
    else
    {
      $install_allow = 0;
      $config_file_display="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>config : </b>It doesn't have write permission.</li>";
    }

    if($assets_file_permission)
      $assets_file_display="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>assets : </b>Has Write Permission.</li>";
    else
    {
      $install_allow = 0;
      $assets_file_display="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>assets : </b>It doesn't have write permission.</li>";
    }

    if($routes_file_permission)
      $routes_file_display="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>routes : </b>Has Write Permission.</li>";
    else
    {
      $install_allow = 0;
      $routes_file_display="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>routes : </b>It doesn't have write permission.</li>";
    }

    if($storage_file_permission)
      $storage_file_display="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>storage/app/public : </b>Has Write Permission.</li>";
    else
    {
      $install_allow = 0;
      $storage_file_display="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>storage/app/public : </b>It doesn't have write permission.</li>";
    }


    if(function_exists('curl_version'))
    $curl="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>cURL : </b>Enabled</li>";
    else
    {
      $install_allow = 0;
      $curl="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>cURL : </b>Disabled, please enable cURL</li>";
    }
    
    if(function_exists( "mb_detect_encoding" ) )
    $mbstring="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>mbstring : </b>Enabled</li>";
    else
    {
      $install_allow = 0;
      $mbstring="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>mbstring : </b>Disabled, please enable mbstring</li>";
    }
      
      
    if(function_exists('ini_get'))
    {
      if( ini_get('safe_mode') )
      {
        $install_allow = 0;
        $safe_mode="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>safe mode : </b>ON, please set safe_mode=off</li>";
      }
      else
      $safe_mode="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>safe mode : </b>OFF</li>";
        
      
      if(ini_get('allow_url_fopen'))
      $allow_url_fopen="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>allow url open : </b>TRUE</li>";
      else
      {
        $install_allow = 0;
        $allow_url_fopen="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>allow url open : </b>FALSE, please make allow_url_fopen=1 in php.ini</li>";
      }
      
    }
    
    if(function_exists('mysqli_connect'))
    $mysql_support="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>MySQLi support : </b>Supported</li>";
    else
    {
      $install_allow = 0;
      $mysql_support="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>MySQLi support : </b>Unsupported, please enable MySQLi support</li>";
    }

    if(function_exists('set_time_limit'))
    $set_time_limit="<li class='list-group-item text-success'><i class='fa fa-check-circle'></i> <b>set time limit : </b>Supported</li>";
    else
    {
      $install_allow = 0;
      $set_time_limit="<li class='list-group-item list-group-item list-group-item-warning text-light'><i class='fa fa-times-circle'></i> <b>set time limit : </b>Disabled, please enable set_time_limit() function</li>";
    }
  ?>

  <div class="container-fluid mt-5">
    <div class="row">
      <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-6 offset-xl-3">
        <div class="login-brand">
          <a  class="text-center" href="{{ url('/') }}"><img  src="<?php echo config('app.logo') ?>" alt="<?php echo config('product_name');?>" width="200" ></a><br><br>
        </div>
        </div>

        <div class="col-12">
          <?php 
          if(Session::get('mysql_error')!="")
            {
              echo "<pre class='mt-0 mb-0 ml-auto mr-auto text-danger text-center'><h6 class='text-danger'>";
              echo Session::get('mysql_error');
              Session::forget('mysql_error');
              echo "</h6></pre><br/>"; 
            }
          ?>

        </div>

        <div class="col-12 col-sm-6 col-md-6 col-xl-6">
          <div class="card card-primary">
           <div class="card-header"><h4><i class="far fa-check-circle"></i> Install "<?php echo config('settings.product_name');?>" Package </h4></div>

            @if ($errors->any())
                <div class="alert alert-warning">
                    <h4 class="alert-heading">{{__('Something Missing')}}</h4>
                    <p> {{ __('Something is missing. Please check the required inputs.') }}</p>
                </div>  
            @endif
            <div class="card-body" id="recovery_form">
              <form class="form-horizontal" action="{{ route('installation-submit') }}" method="POST">
                    @csrf
                    <div class="row">
                      <div class="form-group col-12 col-lg-6">
                         <div class="input-row">
                            <label><b>{{ __('Host Name *') }}</b></label>
                            <input type="text" value="{{ old('host_name') }}" name="host_name" required class="form-control col-xs-12"  placeholder="Host Name *">       
                         </div>   
                         <br>
                          @if ($errors->has('host_name'))
                            <span class="text-danger"> {{ $errors->first('host_name') }} </span>
                          @endif
                      </div>
                      <div class="form-group col-12 col-lg-6">
                        <div class="input-row">
                           <label><b>{{ __('Database Name *') }}</b></label>
                           <input type="text" value="<?php echo old('database_name'); ?>" name="database_name" required class="form-control col-xs-12"  placeholder="Database Name *">          
                        </div>
                        <br>
                          @if ($errors->has('database_name'))
                              <span class="text-danger"> {{ $errors->first('database_name') }} </span>
                          @endif
                      </div>
                    </div>


                    <div class="row">
                      <div class="form-group col-12 col-lg-6">
                       <div class="input-row">
                          
                           <label><b>{{ __('Database Username *') }}</b></label>
                           <input type="text" value="<?php echo old('database_username'); ?>" name="database_username" required class="form-control col-xs-12"  placeholder="Database Username *"> 

                        </div>
                        <br>
                          @if ($errors->has('database_username'))
                                <span class="text-danger"> {{ $errors->first('database_username') }} </span>
                          @endif
                      </div>

                      <div class="form-group col-12 col-lg-6">
                         <div class="input-row">
                           <label><b>{{ __('Database Password *') }}</b> </label>

                           <input type="password" name="database_password"  class="form-control col-xs-12"  placeholder="Database Password ">

                         </div>  
                         <br> 
                          @if ($errors->has('database_password'))
                              <span class="text-danger"> {{ $errors->first('database_password') }} </span>
                          @endif      
                      </div>
                    </div>

                     <div class="row">
                        <div class="form-group col-12 col-lg-6">
                          <div class="input-row">
                            
                            <label><?php echo config('settings.product_name') ?> <b>{{ __('Admin Panel Login Email *') }}</b></label>
                            <input type="email" value="<?php echo old('app_username'); ?>" name="app_username" class="form-control col-xs-12"  placeholder="Application Admin Login Email *">          
                          </div>
                          <br>
                           @if ($errors->has('app_username'))
                              <span class="text-danger"> {{ $errors->first('app_username') }} </span>
                          @endif
                       </div>
                       <div class="form-group col-12 col-lg-6">
                          <div class="input-row">
                            <label><?php echo config('settings.product_name') ?> <b>{{ __('Admin Panel Login Password *') }}</b></label>
                            <input type="password" name="app_password" required class="form-control col-xs-12"  placeholder="Application Password *">          
                          </div>
                          <br>

                          @if ($errors->has('app_password'))
                              <span class="text-danger"> {{ $errors->first('app_password') }} </span>
                          @endif
                       </div>
                     </div>

               
                    <div class="row">

                      <div class="form-group col-12 col-lg-6">
                        <div class="input-row">
                           <label><b>{{ __('Company Name') }}</b> </label>
                           <input type="text" value="<?php echo old('institute_name'); ?>" name="institute_name" class="form-control col-xs-12"  placeholder="Company Name">          
                        </div>
                        <br>
                      </div>                    
                      <div class="form-group col-12 col-lg-6">
                        <div class="input-row">
                           <label><b>{{ __('Company Phone / Mobile') }}</b> </label>
                           <input type="text" value="<?php echo old('institute_mobile'); ?>" name="institute_mobile" class="form-control col-xs-12"  placeholder="Company Phone / Mobile">          
                        </div>
                        <br>
                      </div>  
                    </div> 

                    <div class="form-group">
                      <div class="input-group"></div>
                       <label><b>{{ __('Company Address') }}</b> </label>
                       <input type="text" value="<?php echo old('institute_address'); ?>" name="institute_address" class="form-control col-xs-12"  placeholder="Company Address">          
                    </div>

                   
                    <div class="form-group text-center">
                      <button type="submit" class="btn btn-primary btn-lg mt-4" <?php if($install_allow == 0) echo "disabled"; ?> ><i class="fa fa-check"></i>{{ __('Install') }} <?php echo config('settings.product_name');?> {{ __('Now') }}</button><br/><br/> 
                    </div>  
              </form>   
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-6 col-xl-6">
          <div class="card card-primary">
            <div class="card-header"><h4><i class="fas fa-server"></i> {{ __('Server Requirements') }}</h4></div>

            <div class="card-body">
              <p class="text-muted" id="msg">
                <?php if($install_allow==1) :?>
                  <div class="alert alert-success text-center"><b><i class="fa fa-check-circle"></i> {{ __('Congratulation ! Your server is fully configured to install this application. Just make sure all files and folders have write permission (755 permission recommended)') }}</p></b></div>
                <?php else : ?>
                  <div class="alert alert-warning text-center"><b><i class="fa fa-warning"></i>{{ __('Warning ! Please fullfill the below requirements (yellow) first.') }} </b></div>
                <?php endif; ?>
              </p>
                
              <ul class="list-group">
                <?php
                  echo $symlink;
                  echo $ziparchive;
                  echo $env_file_display;
                  echo $install_txt_display;
                  echo $views_file_display;
                  echo $controller_file_display;
                  echo $helpers_file_display;
                  echo $services_file_display;
                  echo $config_file_display;
                  echo $assets_file_display;
                  echo $routes_file_display;
                  echo $storage_file_display;
                  echo $curl;
                  echo $mbstring;
                  echo $safe_mode;
                  echo $allow_url_fopen;
                  echo $mysql_support;
                  echo $set_time_limit;
                ?>
              </ul>

            <br><br><br><br><br>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>





