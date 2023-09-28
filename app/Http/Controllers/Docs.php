<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Home;


class Docs extends Home
{
    public function settings(){
        $data = app('App\Http\Controllers\Front')->make_view_data();
        $data['body'] = 'docs/settings';
        return $this->docs_viewcontroller($data);

    }

    public function live_users(){
        $data = app('App\Http\Controllers\Front')->make_view_data();
        $data['body'] = 'docs/live-users';
        return $this->docs_viewcontroller($data);

    }
    public function how_to_install(){
        $data = app('App\Http\Controllers\Front')->make_view_data();
        $data['body'] = 'docs/install';
        return $this->docs_viewcontroller($data);

    }

    public function domains(){
        $data = app('App\Http\Controllers\Front')->make_view_data();
        $data['body'] = 'docs/domains';
        return $this->docs_viewcontroller($data);
    }

    public function heatmaps(){
        $data = app('App\Http\Controllers\Front')->make_view_data();
        $data['body'] = 'docs/heatmaps';
        return $this->docs_viewcontroller($data);
    }

    public function recordings(){
        $data = app('App\Http\Controllers\Front')->make_view_data();
        $data['body'] = 'docs/recordings';
        return $this->docs_viewcontroller($data);
    }

    public function user(){
        $data = app('App\Http\Controllers\Front')->make_view_data();
        $data['body'] = 'docs/user';
        return $this->docs_viewcontroller($data);
    }

    public function package(){
        $data = app('App\Http\Controllers\Front')->make_view_data();
        $data['body'] = 'docs/package';
        return $this->docs_viewcontroller($data);
    }

    public function transaction(){
        $data = app('App\Http\Controllers\Front')->make_view_data();
        $data['body'] = 'docs/transaction';
        return $this->docs_viewcontroller($data);
    }


    public function update(){
        $data = app('App\Http\Controllers\Front')->make_view_data();
        $data['body'] = 'docs.update';
        return $this->docs_viewcontroller($data);
    }
}
