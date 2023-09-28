<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Home;

class Test extends Home
{
    public function __construct()
    {
        $this->set_global_userdata();
    }

    public function component()
    {
        $data['body'] = 'component';
        return $this->viewcontroller($data);
    }
}
