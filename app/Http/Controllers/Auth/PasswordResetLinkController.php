<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        set_agency_config();
        $agent_user_id = get_agent_id();
        $data['agent_user_id'] = $agent_user_id;
        return view('auth.forgot-password',$data);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        set_agency_config();

        $request->validate([
            'email' => 'required|email',
        ]);

        $agent_user_id = get_agent_id();
        if(!empty($agent_user_id)) // agent site
        {
            $query = DB::table('users')->where(['email'=>$request->email,'status'=>'1']);
            $query->where(function($query) use ($agent_user_id){
                $query->orWhere('parent_user_id','=',$agent_user_id);
                $query->orWhere('id','=',$agent_user_id);
            });
            $check = $query->select('id')->first();
            if(!isset($check->id)) throw ValidationException::withMessages(['email' => __('No user found associated with this email.')]);
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
