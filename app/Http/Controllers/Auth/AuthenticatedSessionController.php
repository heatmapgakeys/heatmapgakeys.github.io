<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // initiate_agency_config();
        set_agency_config();
        $agent_user_id = get_agent_id();
        $data['agent_user_id'] = $agent_user_id;
        return view('auth.login',$data);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param \App\Http\Requests\Auth\LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(LoginRequest $request)
    {
        $agent_user_id = get_agent_id();
        if(!empty($agent_user_id)) // agent site
        {
            $auth_error = __('These credentials do not match our records.');
            $where = ['users.email'=>$request->email,'users.status'=>'1'];
            $select =
            [
                'users.id',
                'users.parent_user_id',
                'users_parent.parent_user_id as parent_parent_user_id'
            ];
            $query_first = DB::table('users')->select($select)
                ->leftJoin('users as users_parent','users.parent_user_id','=','users_parent.id')
                ->where($where)->first();
            if(!isset($query_first->id)) throw ValidationException::withMessages(['email' => $auth_error]);
            $user_id = $query_first->id;
            $parent_user_id = $query_first->parent_user_id;
            $parent_parent_user_id = $query_first->parent_parent_user_id;

            if( !in_array($agent_user_id,[$user_id,$parent_user_id,$parent_parent_user_id]))
            throw ValidationException::withMessages(['email' => $auth_error]);

        }

        $request->authenticate();
        $request->session()->regenerate();

        $domains =  DB::table('visitor_analysis_domain_list')->select('id','domain_name','domain_code')->where(['user_id'=>Auth::user()->id,'deleted'=>'0'])->orderBy("id","DESC")->first();
        if(isset($domains->id))
        {
            session(['active_domain_id_session' => $domains->id]);
            session(['active_domain_name_session' => $domains->domain_name]);
            session(['active_domain_code_session' => $domains->domain_code]);

            $user_and_domain = explode('-',$domains->domain_code);
            $domain_user = $user_and_domain[1] ?? 1;
            $domain_code = $user_and_domain[0] ?? 1;
            $table_names = get_table_names($domain_user,$domain_code);
            
            $domains_pages = DB::table($table_names['heatmap_table'])->select(['visit_url'])->groupBy('visit_url')->where(array("user_id" => Auth::user()->id, "domain_list_id" => $domains->id))->first();
            if(isset($domains_pages->visit_url))
                session(['active_heatmap_page_name_session' => $domains_pages->visit_url]);
        }

        DB::table('users')->where('id',Auth::user()->id)->update(['last_login_at'=>date('Y-m-d H:i:s'),'last_login_ip'=>get_real_ip()]);
        if(Auth::user()->user_type=="Affiliate") return redirect()->intended(route('affiliate-dashboard'));
        else return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $agent_user_id = Auth::user()->parent_user_id;
        Cache::flush();
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $route = ($agent_user_id>1 &&  get_base_url()==url_convert_to_domain(env('APP_URL'))) ? route('login').'?at='.$agent_user_id : route('login');
        return redirect($route);
    }
}
