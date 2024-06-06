<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\login;                      //
use App\Http\Requests\registration;   


use App\Services\userService;        //

class userController extends Controller
{
    //user services 
    protected $userService;                                        
    public function __construct(userService $userService){        
        $this->userService = $userService;                         
    }    


   

//user login
public function userLogin(login $request)
{
            $response = $this->userService->userLogin(
                $request->email,
                $request->password
            );
                       return response()->json($response,$response['status'] == 1 ? 200:500);                                
} 

}
