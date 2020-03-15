<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Facades\Auth;

class PassportController extends Controller
{
    /**
     * New user registration API
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // User input data validation
        $validator = Validator::make($request->all(), [ 
            'name'                    => 'required', 
            'email'                   => 'required|email|unique:users', 
            'password'                => 'required|min:8'
        ]);
        
        // Return error if validation fails
        if ($validator->fails()) { 
            return response()->json(['error' => true, 'message' => $validator->errors()], 400);         
        }
        
        // Insert user data
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // Generate new access token for user
        $access_token = $user->createToken('access-token')->accessToken;
 
        return response()->json(['error' => false, 'message' => "success", '_token' => $access_token], 200);
    }
 
    /**
     * User login API
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // User login input data validation
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email', 
            'password' => 'required|min:8',
        ]);
        
        // Return error if validation fails
        if ($validator->fails()) { 
            return response()->json(['error' => true, 'message' => $validator->errors()], 400);            
        }

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) { 
            $user = Auth::user(); 
            // Generate new access token for user
            $access_token =  $user->createToken('access-token')->accessToken; 
            return response()->json(['error' => false, 'message' => "success", '_token' => $access_token], 200); 
        } else { 
            return response()->json(['error' => true, 'message' => 'incorrect username or password.'], 400);      
        }
    }
 
    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function details()
    {
        return response()->json(['user' => auth()->user()], 200);
    }
}
