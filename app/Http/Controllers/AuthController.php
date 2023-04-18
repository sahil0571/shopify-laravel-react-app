<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Osiset\ShopifyApp\Util;

use App\Models\User;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
    /**
     *
     *  This method will redirect all fallback requests to route.
     *  It will allow routes to work with the App bridge routing system.
     */

    public function redirectHome()
    {
        return redirect()->route(Util::getShopifyConfig('route_names.home'), request()->query());
    }

    /**
     *
     *  Redering main application.
     */
    public function index(Request $request)
    {
        $user = Auth::user()->name;
        $planId = Auth::user()->plan_id;
        return view('layouts.app',["user" => $user,"plan"=>$planId]);
    }

    /**
     *
     *  Redering login page.
     */
    public function login()
    {
        return view('auth.login');
    }

    public function getUser( Request $request,$name ){
        if (!$name) {
            return response()->json(['message' => 'User detail access faild'], 422);
        }

        $data = User::whereName($name)->first();
        return response()->json(['data' => $data], 200);
    }

}
