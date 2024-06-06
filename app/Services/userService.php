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
           'status'=>0,
           
       ];
 }     
}


//customer Register
public function  userRegister($name,$email,$password,$phoneNo,$role){
    try{
        DB::beginTransaction();
        $User = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'phone_no'=>$phoneNo,
            'role'=>$role

       ]);
        
         $token=$User->createToken("auth_token",[$role])->accessToken;
         DB::commit();
         return
           [
               'message' => 'User created successfully',
               'status' =>1,
               'token' => $token,
               'user' => $User->makeHidden(['customer_password','created_at','updated_at']),
           ];
   }
   catch(\Throwable $err){
       DB::rollback();
       $User = null;
           if($User==null){
               return [
                     'message'=> 'internal server error',
                     'status'=> '0',
                     'error'=>$err->getMessage()
               ];
           }
   }
}
}