<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        return User::all();
    }

    //function to create a new User
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);
        return response()->json([
            'status_code' => env('USER_CREATED'),
            'message' => 'user created successfully'
        ]);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        $user = User::where('email', '=', $fields['email'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'message' => 'Informations incorrectes'
            ]);
        }

        $token = $user->createToken('appdepensetoken')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    // $from, $to, $title, $message
    // function sendMessage(Request $request)
    // {
    //     $request->validate([
            
    //     ])
    //    $save = Message::create([$request->all()]);
    //    if($save){
    //        response()->json([
    //            'message'=> 'Message created successfully'
    //        ]);
    //    }
    //    return response()->json([
    //         'error'=>env('FAILED_REQUEST')
    //    ]);
    // }
}
