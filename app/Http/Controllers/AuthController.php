<?php

namespace App\Http\Controllers;

use Osiset\ShopifyApp\Util;

class AuthController extends Controller
{
    public function redirectHome()
    {
        return redirect()->route(Util::getShopifyConfig('route_names.home'), request()->query());
    }

    public function index()
    {
        return view('welcome');
    }

    public function login()
    {
        return view('auth.login');
    }
}
