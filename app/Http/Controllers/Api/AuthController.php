<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PasswordReset as MailPasswordReset;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'resetPassword', 'savePasswordReset', 'product']]);
    }

    public function login(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);
        if ($validateData->fails()) {
            return response()->json($validateData->errors(), 403);
        }
        $token = auth()->attempt($request->all());
        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function register(Request $request){
        try {
            $validateData = Validator::make($request->all(), [
                'name'     => 'required|string',
                'lastname' => 'required|string',
                'email'    => 'required|email',
                'password' => 'required|string'
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            $keys = array_keys($request->all());
            $newUser = new User();
            foreach ($keys as $key => $value) {
                if($value == 'password'){
                    $newUser->$value = Hash::make($request[$value]);
                    continue;
                }
                $newUser->$value = $request[$value];
            }
            $newUser->save();
            return response()->json(
                ['message' => 'Success operation', 'data' => $newUser], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function resetPassword(string $email)
    {
        try {
            $validateData = Validator::make(['email' => $email], [
                'email' => 'required|email|exists:users,email'
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            $user = User::select('id','name')->where('email', '=', $email)->first();
            $createToken = new PasswordReset();
            $createToken->email = $email;
            $createToken->token = Crypt::encryptString($user->id);
            $createToken->save();
            Mail::to($email)->send(new MailPasswordReset(['name'=> $user->name, 'token' => $createToken->token]));
            return response()->json(['message' => 'email send'], 200);  
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
    
    public function savePasswordReset(Request $request)
    {
        try {
            $validateData = Validator::make($request->all(), [
                'password' => 'required|string',
                'token'    => 'required|string|exists:password_resets,token'
            ]);
            if ($validateData->fails()) {
                return response()->json($validateData->errors(), 403);
            }
            $infoUser = PasswordReset::where('token', '=', $request->token)->first();
            User::where('email', '=', $infoUser->email)->update(['password' => Hash::make($request->password)]);
            return response()->json(['message' => 'Update password'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }
}
