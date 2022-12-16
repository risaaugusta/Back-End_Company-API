<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
// use App\Http\Controllers\API\BaseController;
use App\Helpers\ResponseFormatter;
// use App\Http\Controllers\API\AuthController as AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
   
class RegisterController extends ResponseFormatter
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            // return $this->sendError('Validation Error.', $validator->errors());       
            return 'error validation';       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $tokenResult = $user->createToken('authToken')->plainTextToken;
        $success['name'] =  $user->name;
   
        // return $this->sendResponse($success, 'User register successfully.');
        return ResponseFormatter::success([
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
            'user' => $user
        ], 'Authenticated', 200);
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user();  
            $success['name'] =  $user->name;
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'User login successfully', 200);
        } 
        else{ 
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ],'User login failed', 500);
        } 
    }
}