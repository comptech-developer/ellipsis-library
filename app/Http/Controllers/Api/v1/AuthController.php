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
use App\Http\Resources\UserResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    //
    public $successStatus = 200;

    public function login(AuthRequest $request)
    {
        $validator = $request->validated();
        try {
        $check_email = User::where('email', $request->email)->first();
        if (!is_null($check_email)) {
            if (FacadesAuth::attempt([
                'email' => request('email'),
                'password' => request('password')
            ])) {
                $user = FacadesAuth::user();
                $success['token'] =  $user->createToken('library')->accessToken;
                $success['message'] = 'Access Granted Succefully...';
                $success['status'] = 0;
                $success['client'] = UserResource::make($user);
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

    public function UserRegistration(UserRequest $request)
    {
              $validator = $request->validated();
            try {
                $user = User::create([
                    'name' =>  $request->name,
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
                    $success['client'] =UserResource::make($user);
                    return response()->json($success, $this->successStatus);
                }

            } catch (\Throwable $e) {
                $response['message'] = $e->getMessage();
                $response['status'] = 2;
                $response['token'] = '';
                $response['client'] = null;
                return response()->json($response, $this->successStatus);

            }
        
    }
    public function EditUser(Request $request)
    {
              
            try {
                $input = $request->all();
                if(!empty($input['password'])){
                    $input['password'] = Hash::make($input['password']);
                }else{
                    $input = Arr::except($input,array('password'));
                }
                $user = User::find($request->user_id)->update($input);
                    $success['message'] = 'User Edited Succefully...';
                    $success['status'] = 1;
                    return response()->json($success, $this->successStatus);
            } catch (\Throwable $e) {
                $response['message'] = $e->getMessage();
                $response['status'] = 2;
                $response['token'] = '';
                return response()->json($response, $this->successStatus);

            }
        
    }
    public function DeleteUser($user_id)
    {
            try {
                  User::find($user_id)->delete();
                    $success['message'] = 'User Deleted Succefully...';
                    $success['status'] = 1;
                    return response()->json($success, $this->successStatus);
            } catch (\Throwable $e) {
                $response['message'] = $e->getMessage();
                $response['status'] = 2;
                $response['token'] = '';
                return response()->json($response, $this->successStatus);

            }
        
    }
    public function ListUser()
    {
        try {
           
            $user = User::all();
                $success['message'] = 'Succefully...';
                $success['status'] = 1;
                $success['client'] =UserResource::collection($user);
                return response()->json($success, $this->successStatus);

        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
            $response['status'] = 2;
            $response['token'] = '';
            $response['client'] = null;
            return response()->json($response, $this->successStatus);

        }
    }
    public function ViewUser($user_id)
    {
        try {
            Log::info($user_id);
            $user = User::find($user_id);
                $success['message'] = 'Succefully...';
                $success['status'] = 1;
                $success['client'] =UserResource::make($user);
                return response()->json($success, $this->successStatus);

        } catch (\Throwable $e) {
            $response['message'] = $e->getMessage();
            $response['status'] = 2;
            $response['token'] = '';
            $response['client'] = null;
            return response()->json($response, $this->successStatus);

        }
    }
      
}
