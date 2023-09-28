<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        set_agency_config(Auth::user()->id);
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }
        if(auth()->user()->id == 1) $parent_id = 1;
        else $parent_id = auth()->user()->parent_user_id;
        $data = DB::table('settings')->select('email_settings')->where('user_id',$parent_id)->first();
        $check_email = json_decode($data->email_settings,true);
        if(!isset($check_email['default']) ||$check_email['default'] == '' || empty($check_email['default']) )
            dd('Please configure your Email settings.');
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
