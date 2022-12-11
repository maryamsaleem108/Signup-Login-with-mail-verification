<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use App\Http\Requests\storeUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Http\Middleware\emailVerifyMiddleware;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index()
    {
        return User::all()->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(loginRequest $request) //Login USING POST METHOD
    {
        if($request->validated()){   //Check Validation
            try {
                $email = $request->email;
                $password = $request->password;
            }catch (\Exception $e){
                abort(203);  //No Content
            }

            try {   //Exception Handling,
                $user = (DB::table('users')->where('email', '=', $email));
                $userTokenTable = (DB::table('token')->where('email', '=', $email));
            }catch (\Exception $exception) {   //If Table or record don't Exist
                $response = [
                    'status' => 404,
                    'message' => 'Record Not Found',
                ];
            }

            //Verify Password from DB
            if (Hash::check($password, $user->value('password'))) {
                $token = Str::random(10);
                $userTokenTable->update(['userToken' => $token]);  //Updating Token of user in d
                if (!$user->value('emailVerified')){   //Password is correct but email is not verified
                    $message = "You are Logged In, Kindly Verify Your Email. Check Your MailBox :)";
                }else{   //Password and email are verified.
                    $message = "You are Logged In Successfully :)";
                }
                $response = [
                    'status' => 200,
                    'message' => $message,
                    'Profile' => [
                        'name' => $user->value('name'),
                        'Age' => $user->value('age'),
                        'Email' => $user->value('email'),
                        'Phone Number' => $user->value('phone_number')],
                    'Reset Password' => 'http://127.0.0.1:8000/api/reset/'.$user->value('id')
                ];
            }else{      //Incorrect Password
                $response = [
                    'status' => 404,
                    'message' => 'Wrong Password',
                ];
            }
        }else{
            $response = [
                'status' => 404,
                'message' => 'Something Went Wrong',
            ];
        }
        return response()->json($response);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function register(storeUserRequest $request)   //Register User and Send Email for verification use POST Method
    {
        if($request->validated()) {   //Check Validation
            if ($request->hasFile('picturePath')) {   //check if request contains image or not
                $filename = time() . '.' . $request->file('picturePath')->getClientOriginalExtension();
                $picPath = $request->file('picturePath')->move('storage\images\\', $filename, 'public');
            } else {
                $picPath = 'storage/default.jpg';
            }

            //Create New User in db using USER Model
            try {
                $newUser = User::create([
                    'name' => $request->name,
                    'age' => $request->age,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'password' => $request->password,
                    'picturePath' => $picPath
                ]);
            }catch (\Exception $e){
                return abort(204);  //No Content
            }

            $token = Str::random(10);   //Generate Token

            //Create New Token for user in db using UserToken Model
            try {
                $newToken = UserToken::create([
                    'email' => $request->email,
                    'userToken' => $token,
                ]);
            }catch (\Exception $e){
                return abort(204);  //No Content
            }
        }

        //Go to route with data to send verification email
        return Redirect::route('verifyEmail',['userId' => $newUser->id,'userEmail' => $newUser->email,'userName' => strtoupper($newUser->name), 'userToken'=>$token]);

        //Route of Request = Controller(register function) -> Routr(verifyEmail) -> Mail(VerifyMail) -> Views(verificationEmail)->Rout(emailVerified)->Middlware(registration)
    }

    /**
     * Display the specified resource.
     *
* //     * @param  int  $id
     * @return JsonResponse
     * @return RedirectResponse
     */

    public function ForgetPassword(Request $request)   //Forget Password use POST Method
    {
        $validator = Validator::make($request->all(),  //Validate Inputs
            $rules = [
                'email' => 'required|email',
                'password' => 'required',
            ],
            $messages = [
                'email.required' => 'Email is required.',
                'password.required' => 'Please Enter New Password.',
                'email.email' => 'Invalid Email Address'
            ]
        );

        if ($validator->fails()){  //If Validation Failed
            $response = [
                'status' => 404,
                'Message' => $validator->errors()
            ];
        }

        try {       //Handling Exception if some excpetion occurs during getting value from db
            $userId = (DB::table('users')->where('email', '=', $request->email))->value('id');
        }catch (\Exception){
            $response = [
                'status' => 404,
                'Message' => 'Something Went Wrong'
            ];
        }

        if($userId){  //Update Password in db If User Exist
            DB::table('users')->where('id', '=', $userId)->update(['password' => HASH::make($request->password)]);
            $response = [
                'status' => true,
                'message' => 'Password Updated Successfully! Login Now :)',
                'Login' => 'http://127.0.0.1:8000/api/login'
            ];
        }
        else{   //If User Does not Exist
            $response = [
                'status' => 404,
                'message' => 'Email Not Found! Register Now',
                'register' => 'http://127.0.0.1:8000/api/newUser'
            ];
        }
        return response()->json($response);
    }
}
