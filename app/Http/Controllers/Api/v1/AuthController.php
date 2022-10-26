<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Throwable;
use Carbon\Carbon;
use Response;
use Hash;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use App\Http\Requests\UserRequest;
use App\Http\Requests\AuthRequest;
class AuthController extends Controller
{
    //
    public $successStatus = 200;

    public function login(AuthRequest $request)
    {
        $validator = $request->validated();
        try {
        $check_phone = User::where('email', $request->phone)->first();
        if (!is_null($check_phone)) {
            if (FacadesAuth::attempt([
                'email' => request('email'),
                'password' => request('password')
            ])) {
                $user = FacadesAuth::user();
                $success['token'] =  $user->createToken('library')->accessToken;
                $success['message'] = 'Access Granted Succefully...';
                $success['status'] = 0;
                $success['client'] = $user;
                return response()->json($success, $this->successStatus);
            } else {
                return response()->json([
                    'message' => 'Invalid Credentials..!!',
                    'token' => '',
                    'client' => null,
                    'status' => 2
                ], $this->successStatus);
            }
        } else {
            return response()->json([
                'message' => 'Email Does Not Exist..!!',
                'token' => '',
                'client' => null,
                'status' => 3
            ], $this->successStatus);
        }
    }catch(\Throwable $e){
        $response['message']= $e->getMessage();
        $response['status'] = 2;
        $response['data']= [];
        return response()->json($response,$this->successStatus);
    }
    }

    public function UserRegistration(Request $request)
    {
              $validator = $request->validated();
            try {
                $user = User::create([
                    'name' =>  $request->phone,
                    'email' => $request->email,
                    'password' => FacadesHash::make($request->password),
                    'role_id' => 2
                ]);
                if (FacadesAuth::attempt([
                    'email' => request('email'),
                    'password' => request('password')
                ])) {
                    $user = FacadesAuth::user();
                    $success['token'] =  $user->createToken('library')->accessToken;
                    $success['message'] = 'Access Granted Succefully...';
                    $success['status'] = 1;
                    $success['client'] = $user;
                    return response()->json($success, $this->successStatus);
                }

            } catch (\Throwable $e) {
                $response['message'] = 'System Error';
                $response['status'] = 2;
                $response['token'] = '';
                $response['client'] = null;
                return response()->json($response, $this->successStatus);

            }
        
    }
}
