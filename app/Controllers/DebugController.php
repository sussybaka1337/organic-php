<?php

namespace App\Controllers;

class DebugController extends BaseController
{
    public function __construct()
    {
    }
    public function debug()
    {
        return view('debug');
    }
}