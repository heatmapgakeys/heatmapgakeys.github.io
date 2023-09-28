<!-- ===== Pricing start ===== -->

<?php
$i=0;
$premium_packages = null;
$agency_packages = null;
$replace_array = [
    __('Day'),
    __('Month'),
    __('Week'),
    __('Year')
];
?>
@if(count($package_validity_list)>1)
    <div class="flex items-center justify-center">
         <div class="inline-flex rounded-md shadow-sm content-center" role="group">
            <?php $count = 0;?>
             @foreach($package_validity_list as $kv=>$vv)
                 <?php
                 $count++;
                 $less = '';
                 $rounded_class = '';
                 if($count=='1') $rounded_class = 'rounded-l-lg';
                 else if($count==count($package_validity_list)) $rounded_class = 'rounded-r-lg';
                 ?>
                <a  type="button" href="{{route('pricing-plan')}}?validity={{$kv}}" class="py-2 px-4 text-sm font-medium text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white {{$rounded_class}} <?php if($kv==$default_validity) echo 'active';?>">{{$vv}} {{$less}}</a>
            @endforeach
        </div>
    </div>
 @endif
<section id="pricing" class="pt-5">
  <div class="ud-container">
    <div class="ud-flex ud-flex-wrap ud--mx-4 justify-center">
        @foreach($get_pricing_list as $key=>$value)
            <?php
                $not_in_package = '';
                if($value->is_default=='0') {
                    if($value->is_agency=='1' && $value->is_whitelabel=='1') $agency_packages[] = (array) $value;
                    else $premium_packages[] = (array) $value;
                    continue;
                }
                $i++;
                $price = $value->price;

                $class = '';
                if($i==1 || $i==4) $class='first-item';
                if($i==3 || $i==6) $class='last-item';

                $validity = $value->validity;
                $validity_extra_info = $value->validity_extra_info;
                if($validity>0){
                    $validity_text = convert_number_validity_phrase($validity);;
                }
                if($validity==0) {
                    $validity_text = __('Forever');
                }
                $module_ids = explode(',',$value->module_ids);
                $monthly_limit = json_decode($value->monthly_limit,true);
                $buy_button_name = __('Get Access');
            ?>
            <div class="ud-w-full md:ud-w-1/2 lg:ud-w-1/3 ud-px-4">
                <div class="ud-relative ud-overflow-hidden ud-py-10 ud-px-8 sm:ud-p-12 md:ud-px-8 lg:ud-px-5 xl:ud-px-9 2xl:ud-px-12 ud-rounded-[20px] ud-border-2 ud-border-[#f3eeff] dark:ud-border-black ud-mb-12 wow fadeInUp" data-wow-delay=".25s">
                  <span class="ud-font-bold ud-text-xl ud-text-black dark:ud-text-white ud-mb-2 ud-block">{{strtoupper($value->package_name)}}</span>
                  <h3 class="ud-font-bold ud-text-black dark:ud-text-white ud-text-[40px]">
                    {{$price=='Trial' ? __('Trial') : __('FREE')}}
                    <span class="ud-font-normal ud-text-base ud-text-body-color dark:ud-text-white">
                     {{$price=='Trial' ? $validity_text : ''}}
                    </span>
                  </h3>

                  <div class="ud-pt-24 ud-space-y-3">

                    <?php
                    foreach($get_modules as $key2=>$value2) :
                        if(!in_array($value2->id,$module_ids)) {
                            echo '<p class="ud-flex ud-items-center ud-font-semibold ud-text-base ud-text-body-color">
                                    <i class="far fa-times-circle text-danger ud-pr-3"></i> '.__($value2->module_name).
                                  '</p>';
                            continue;
                        }
                        $limit=0;
                        $limit=convert_number_numeric_phrase($monthly_limit[$value2->id],0);
                        if($limit=="0") $limit2="<b>&nbsp;".__('Unlimited')."</b>";
                        else $limit2=$limit;
                        if($value2->extra_text!='' && $limit>0)
                            $limit2="<b>&nbsp;".$limit2."/".__('Month')."</b>";
                        $module_name = $value2->module_name;
                    ?>

                    <p class="ud-flex ud-items-center ud-font-semibold ud-text-base ud-text-body-color">
                        <i class="far fa-check-circle text-theme ud-pr-3"></i> 
                      {{ __($module_name) }} :  {!! $limit2 !!}
                    </p>
                    <?php endforeach; ?>
                  </div>
                  <div class="ud-pt-11">
                    @if(!Auth::user())
                    <a href="{{route('register')}}" class=" ud-bg-primary ud-w-full ud-flex ud-items-center ud-justify-center ud-text-white ud-text-base ud-font-bold ud-p-3 ud-rounded-xl ud-transition-all hover:ud-shadow-primary-hover">
                        {{$buy_button_name}}
                    </a>
                    @endif
                  </div>
                  <div>
                    <span class="ud-absolute ud-top-0 ud-left-0 ud--z-1">
                      <img src="{{ asset('assets/front/images/svg/pricing.svg') }}">
                    </span>
                    <span class="ud-absolute ud-top-0 ud-right-0 ud--z-1">
                      <img src="{{ asset('assets/front/images/svg/pricing_2.svg') }}">

                    </span>
                    <span class="ud-absolute ud-top-0 ud-right-32 ud--z-1">
                      <img src="{{ asset('assets/front/images/svg/pricing_3.svg') }}">

                    </span>
                  </div>

                </div>
            </div>
        @endforeach

        <?php $package_map = [];?>
        @if(!empty($premium_packages))
            <?php
                $count_premium = 0;
                $min_website = 0;
                $first_package_id = 0;
                $first_package_price = 0;
                $first_package_discount_message = '';
                $first_package_validity = '';
                $first_package_name = '';
                $premium_package_li_str = '';
            ?>
            @foreach($premium_packages as $key=>$value)
                @php
                    $monthly_limit_temp = json_decode($value['monthly_limit'],true);
                    $discount_data = $value['discount_data'];
                    $price_raw_data = format_price($value['price'],$format_settings,$discount_data,['return_raw_array'=>true]);

                    $discount_message = '';
                    if(isset($price_raw_data->discount_valid) && $price_raw_data->discount_valid)
                    $discount_message = __('Save').' '.$price_raw_data->discount_amount_formatted_currency;
                    $package_website_map = convert_number_numeric_phrase($monthly_limit_temp[1],0) ?? 0;
                    $package_id_map = $value['id'];
                    $package_name = $value['package_name'];


                    $validity = $value['validity'];
                    $validity_extra_info = $value['validity_extra_info'];
                    $validity_unit = __('Day');
                    if($validity>0){

                        $validity_text = convert_number_validity_phrase($validity);
                    }
                    if($validity==0) {
                        $validity_text = __('Forever');
                    }

                    if($count_premium==0){
                        $min_website = $package_website_map;
                        $first_package_id = $package_id_map;
                        $first_package_price = $price_raw_data->display_price_currency ?? '';
                        $first_package_discount_message = $discount_message;
                        $first_package_validity = $validity_text;
                        $first_package_name = $package_name;
                    }

                    $count_premium++;
                    $package_price_map = $price_raw_data->display_price_currency ?? '';
                    $package_map[$count_premium] = ['id'=>$package_id_map,'price'=>$package_price_map,'website'=>$package_website_map,'discount_message'=>$discount_message,"validity_text"=>$validity_text,'name'=>$package_name];

                    $module_ids = explode(',',$value['module_ids']);
                    foreach($get_modules as $key2=>$value2):
                        $li_class = 'ud-flex ud-items-center ud-font-semibold ud-text-base ud-text-body-color text-white premium-li premium-'.$count_premium;
                        $hide_other_package_unavailable_module = $count_premium>1 ? 'hidden' : '';
                        if(!in_array($value2->id,$module_ids)) {
                            $premium_package_li_str .= '
                            <p class="'.$li_class.' '.$hide_other_package_unavailable_module.'">
                                <i class="far fa-times-circle text-white ud-pr-3"></i> 
                                '.__($value2->module_name).'
                            </p>';
                            continue;
                        }
                        if($value2->id==1) continue;
                        $limit=0;
                        $limit=convert_number_numeric_phrase($monthly_limit_temp[$value2->id],0);
                        if($limit=="0") $limit2="<b>".__('Unlimited')."</b>";
                        else $limit2=$limit;
                        if($value2->extra_text!='' && $limit>0)
                            $limit2="<b>".$limit2."/".__('Month')."</b>";
                        if($count_premium>1) $li_class .= ' hidden';
                        $module_name = $value2->module_name;
                        $premium_package_li_str .= '<li class="'.$li_class.'"><i class="far fa-check-circle text-white ud-pr-3"></i> '.__($module_name).' :&nbsp;'.$limit2.'</li>';
                    endforeach;
                @endphp
            @endforeach
            <div class="ud-w-full md:ud-w-1/2 lg:ud-w-1/3 ud-px-4">
                <div class=" ud-relative ud-overflow-hidden ud-py-10 ud-px-8 sm:ud-p-12 md:ud-px-8 lg:ud-px-5 xl:ud-px-9 2xl:ud-px-12 ud-rounded-[20px] ud-border-2 ud-bg-primary ud-mb-12 wow fadeInUp" data-wow-delay=".3s">
                    <span class=" ud-font-bold ud-text-xl ud-text-black dark:ud-text-white ud-mb-2 ud-block">
                        <span id="package_name">{{$first_package_name}}</span>
                        <small id="package_price_save" class="border border-white px-2 py-1 ms-2 text-white rounded <?php echo !empty($first_package_discount_message) ? 'd-inline' : 'hidden'; ?>"><?php echo $first_package_discount_message;?></small>
                    </span>

                    <h3 class="ud-font-bold ud-text-black dark:ud-text-white ud-text-[40px]"><span id="package_price"><?php echo $first_package_price;?></span>
                        <span class=" ud-font-normal ud-text-base ud-text-body-color ud-text-black dark:ud-text-white" id="validity_text">{{$first_package_validity}}</span>
                    </h3>

                    @if(!$is_agency_site)<p class="">{{str_replace("_",' ',ENV('PAYPRO_PREMIUM_DISCOUNT_EXTRA_MESSAGE'))}}</p>@endif

                    <div class="ud-pt-24 ud-space-y-3">
                       
                        <p class=" ud-flex ud-items-center ud-font-semibold ud-text-base ud-text-body-color  text-white">
                            <i class="far fa-check-circle text-white ud-pr-3"></i> {{__('Website')}} # <span class="d-inline text-white" id="package_no_website">{{$min_website}}</span>
                            <p><input type="range" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700" min="1" max="{{$count_premium}}" step="1" value="1" id="package_website_range"></p>
                        </p>
   
                        <?php echo $premium_package_li_str;?>
                        
                    </div>
                    <div class="ud-pt-11">
                        <a href="{{route('buy-package',$first_package_id)}}" id="package_link" class="ud-bg-white ud-w-full ud-flex ud-items-center ud-justify-center ud-text-dark ud-text-base ud-font-bold ud-p-3 ud-rounded-xl ud-transition-all hover:ud-shadow-primary-hover hover:ud-text-dark ud-border-white">
                            {{__('Purchase')}}
                        </a>
                    </div>

                    <div>
                      <span class="ud-absolute ud-top-0 ud-left-0">
                        <img src="{{ asset('assets/front/images/svg/pricing.svg') }}">
                      </span>
                      <span class="ud-absolute ud-top-0 ud-right-0">
                        <img src="{{ asset('assets/front/images/svg/pricing_2.svg') }}">

                      </span>
                      <span class="ud-absolute ud-top-0 ud-right-32">
                        <img src="{{ asset('assets/front/images/svg/pricing_3.svg') }}">

                      </span>
                    </div>
                </div>
            </div>
        @endif

    </div>
  </div>
</section>
<!-- ===== Pricing end ===== -->

@push('scripts-footer')
    <script>
        "use strict";
        var lang_Unlimited = '{{__('Unlimited')}}';
        var package_map = {!! json_encode($package_map) !!};
        var buy_url = '{{route("buy-package",":id")}}';
    </script>
    <script src="{{ asset('assets/heatmap/js/partials/show-pricing.js') }}"></script>
@endpush

