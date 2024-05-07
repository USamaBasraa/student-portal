<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
 
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'course' => 'required_if:type,teacher',
            'password' => 'required|confirmed',
            'type' => 'required|in:teacher,student',
        ]);
    
        if ($validator->fails()) {
            return response([
                'message' => 'Password confirmation does not match.',
                'status' => 'failed'
            ], 422); 
        }
    
        if(User::where('email', $request->email)->first()){
            return response([
                'message' => 'Email already exists',
                'status' => 'failed'
            ], 200);
        }
    
        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
        ];
    
        if ($request->type === 'teacher') {
            $userData['course'] = $request->course;
        }
    
        $user = User::create($userData);
    
        $token = $user->createToken($request->email)->plainTextToken;
        return response([
            'token' => $token,
            'message' => 'Registration Success',
            'status' => 'success'
        ], 201);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->email)->plainTextToken;
    
            $userType = $user->type;
    
            return response([
                'token' => $token,
                'user_type' => $userType,
                'message' => 'Login Success',
                'status' => 'success'
            ], 200);
        }
    
        return response([
            'message' => 'The provided credentials are incorrect.',
            'status' => 'failed'
        ], 401);
    }
    
    public function logout() {
        auth()->user()->tokens()->delete();
    
        return response([
            'message' => 'Logout Success',
            'status' => 'success'
        ], 200);
    }   

    public function logged_user() {

        $loggeduser = auth()->user();

        return response([
            'user' => $loggeduser,
            'message' => 'Logged User Data',
            'status' => 'success'
        ], 200);
    } 

    public function change_password(Request $request){
        $request->validate([
            'password' => 'required|confirmed',
        ]);
    
        $loggeduser = auth()->user();
        $loggeduser->password = Hash::make($request->password);
        $loggeduser->save();
    
        return response([
            'message' => 'Password Changed Successfully',
            'status' => 'success'
        ], 200);
    }
    
}
