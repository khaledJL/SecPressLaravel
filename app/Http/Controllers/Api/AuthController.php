<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
        public function register(Request $request){
                $request->validate([
                    'email' => 'required',
                    'name' => 'required',
                    'password' => 'required'
                ]);


              
                $user=new User();
                $user->name=$request->name;
                $user->email=$request->email;
                $user->password=bcrypt($request->password);
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;

                $user->save();

                $response = ['token'=>$token];
        return response($response,200);
        }
        

        public function login(Request $request){
            $request->validate([
                'email'=>'required',
                'password'=>'required'
            ]);
            $user= User::where('email',$request->email)->first();
            if(!$user){
                return response(['status'=>'error','message'=>'User not found']);
            }
            if(Hash::check($request->password, $user->password)){
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['user'=>$user,'token'=>$token];
                return response($response,200);
            }else{
                return response(['message'=>'password not match','status'=>'error']);
            }
            

        }
}
