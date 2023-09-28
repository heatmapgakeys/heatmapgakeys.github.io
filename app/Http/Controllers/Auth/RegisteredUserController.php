<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        set_agency_config();
        $agent_user_id = get_agent_id();

        $tos_url = route('policy-terms');
        $privacy_url = route('policy-privacy');

        // checking if valid agent
        $has_affiliate_access = false;
        if(!empty($agent_user_id)){
            $agent_data = DB::table('users')->select('user_type','package_id')->where(['users.id'=>$agent_user_id,'status'=>'1'])->first();
            $user_type = $agent_data->user_type ?? 'Member';
            if($user_type=='Member') abort('404');

            $package_id = $agent_data->package_id ?? 0;
            $package_data = DB::table('packages')->where('id',$package_id)->select('module_ids')->first();
            $module_ids = isset($package_data->module_ids) ? explode(',',$package_data->module_ids) : [];
            $has_affiliate_access = in_array(12,$module_ids);
        }

        $data['has_affiliate_access'] = $has_affiliate_access;
        $data['agent_user_id'] = $agent_user_id;
        $data['tos_url'] = $tos_url;
        $data['privacy_url'] = $privacy_url;
        return view('auth.register',$data);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        $affiliate_user_id = Cookie::get('affiliate_user_id');
        if($affiliate_user_id === null) $affiliate_user_id = 0;
        set_agency_config();
        $agent_user_id = get_agent_id();
        $affiliate_user = $request->is_affiliate;
        $userType = $affiliate_user == 1 ? "Affiliate" : "Member";

        if(!empty($agent_user_id)){
            // checking if valid agent and have valid limit
            $agent_data = DB::table('users')
                ->select('user_type','package_id','user_limit')
                ->where(['users.id'=>$agent_user_id,'status'=>'1'])
                ->leftJoin('packages','users.package_id','=','packages.id')->first();
            $user_type = $agent_data->user_type ?? 'Member';
            $user_limit = $agent_data->user_limit ?? -1;
            if($user_type=='Agent' && $userType == 'Member')
            {
                $user_count = DB::table('users')->select('id')->where(['parent_user_id'=>$agent_user_id,'user_type'=>'Member','status'=>'1'])->count();
                if($user_limit<0 || ($user_limit>0 && $user_count>=$user_limit)) return response()->json(['error' => true, 'message' => __('New user sign-up has been turned off. Please contact your service provider to add you manually.')]);
            }
            else if($user_type=='Member') return response()->json(['error' => true, 'message' => __('Bad request.')]);
        }


        if(empty($agent_user_id)) $agent_user_id = '1';

        $package_info = DB::table('packages')->where(['user_id'=>$agent_user_id,'is_default'=>'1'])->first();
        $validity = isset($package_info->validity) ? $package_info->validity : 0;
        $package_id = isset($package_info->id) ? $package_info->id : 0;
        $to_date = date('Y-m-d');
        $expiry_date=date("Y-m-d",strtotime('+'.$validity.' day',strtotime($to_date)));
        if($affiliate_user) {
            $package_id = $expiry_date = NULL;
        }
        $curtime = date("Y-m-d H:i:s");
        $userdata = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type'=>$userType,
            'package_id'=>$package_id,
            'created_at'=>$curtime,
            'updated_at'=>$curtime,
            'expired_date'=>$expiry_date,
            'parent_user_id' => $agent_user_id,
            'last_login_at'=>date('Y-m-d H:i:s'),
            'last_login_ip'=>get_real_ip(),
            'under_which_affiliate_user'=>$affiliate_user_id
        ];
        $user = User::create($userdata);

        if($user instanceof User)
        {
            event(new Registered($user));
            Auth::login($user);
            $user_id = $user->id;
            if($affiliate_user_id != 0)
            app('App\Http\Controllers\Home')->affiliate_commission($affiliate_user_id,$user_id,$event='signup',$package_price=0);
            return response()->json([
                'error' => false,
                'message' => __('You have been registered successfully'),
            ]);
        }

        return response()->json([
            'error' => true,
            'message' => __('Something went wrong'),
        ]);
    }
}
