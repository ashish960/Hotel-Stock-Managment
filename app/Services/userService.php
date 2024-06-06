<?php

namespace App\Services;                        

use Illuminate\Support\Facades\Hash;           
use Illuminate\Support\Facades\DB;             


use App\Models\User;     

//for user registration.

class userService{

public function userLogin($email,$password){
    try{ 
        $User =User::where(['email'=>$email])->first();
        if($User && Hash::check($password, $User->password)){
                        $token = $User->createToken("auth_token",[$User->role])->accessToken;     
                       return
                           [
                               'token' => $token,
                               'message' => 'Login successfully',
                               'status' =>1
                           ];
       }  
       else{
           return
               [
                   'message'=>'user not found',
                   'status'=>0
               ];
       }
}catch(\Throwable $err){
      return
       [
           'message'=>'Internal server error',
           'error'=>$err->getMessage(),
           'status'=>0,
           
       ];
 }     
}
}