<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();
        $this->validate($request, [
            'phone' => 'required|numeric',
        ]);
        $data['token'] = Str::random(60);
        $data['code'] = rand(100000, 999999);
        try {
            //sendSMS
            $user = User::create($data);
            return "go to verified page";
        } catch (Exception $exception) {
            return "user exist ";
        }
    }

    public function verified(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'phone' => 'required|numeric',
        ]);
        $users = DB::table('users')
            ->where('code', '=', $request->code)
            ->where('phone', '=', $request->phone)
            ->exists();
        if ($users) {
            $result['success'] = true;
            $result['token'] = Str::random(60);
            $affected = DB::table('users')
                ->where('phone', $request->phone)
                ->update(['token' => $result['token']]);
            return $result;
        } else {
            $result['success'] = false;
        }
    }

    public function login(Request $request)
    {
        $data = $request->all();
        $this->validate($request, [
            'phone' => 'required|exists:users',
            'token' => 'nullable',
        ]);
        $user = User::where('phone', $request->phone)->where('token', $request->token)->get();
        if ($request->token == null || !DB::table('users')->where('phone', $request->phone)->where('token', $request->token)->exists()) {
            $request['token'] = Str::random(60);
            $request['code'] = rand(100000, 999999);
            try {
                //sendSMS
                $affected = DB::table('users')
                    ->where('phone', $request->phone)
                    ->update(
                        [
                            'token' => $request['token'],
                            'code' => $request['code'],
                        ]);
                return "go to verified page";
            } catch (Exception $exception) {
                return false;
            }
        } else {
            return "user can login!!!";
        }
    }

}
