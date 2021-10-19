<?php

namespace App\Http\Controllers\API\Admin;

use App\Exceptions\ChangePasswordFailureException;
use App\Exceptions\FailureResponseException;
use App\Exceptions\LoginFailedException;
use App\Exceptions\LoginUnAuthorizeException;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\CreateUserAPIRequest;
use App\Mail\MailService;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mail;
use Str;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use URL;

class AuthController extends AppBaseController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
    * @param  CreateUserAPIRequest $request
    *
    * @return  mixed
    */
    public function register(CreateUserAPIRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        try {
            DB::beginTransaction();
            $user = $this->userRepository->create($input);
            $data['username'] = $user->username;
            $data['link'] = URL::to('email/verify/'.Crypt::encrypt($user->email));

            if(isset($input['role'])){
                $userRole = Role::find($input['role']);
                $user->assignRole($userRole);
            }

            Mail::to($user->email)
                ->send(new MailService('emails.verify_email',
                'Verify Email Address',
                $data));

            DB::commit();
            return $this->successResponse($user);
        }catch (Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * @param  Request $request
    *
    * @throws  LoginFailedException
    * @throws  LoginUnAuthorizeException
    *
    * @return  mixed
    */
    public function login(Request $request)
    {
        $input = $request->all();
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $user = User::where('username',$input['username'])->first();
        if(empty($user)){
            $user = User::where('email',$input['username'])->first();
        }
        if(empty($user)){
            throw new LoginFailedException('User not exists');
        }
        if (! $user->email_verified_at) {
            throw new LoginUnAuthorizeException('Your account is not verified.');
        }
        if (! $user->is_active) {
            throw new LoginUnAuthorizeException('Your account is deactivated. please contact your administrator.');
        }
        if ($user->login_retry_limit >= User::MAX_LOGIN_RETRY_LIMIT){
            $now = Carbon::now();
            if(empty($user->login_reactive_time)){
                $expireTime = Carbon::now()->addMinutes(User::LOGIN_REACTIVE_TIME)->toISOString();
                $user->update([
                    'login_reactive_time' => $expireTime,
                    'login_retry_limit' => $user->login_retry_limit + 1
                ]);
                throw new LoginFailedException('you have exceed the number of limit.you can login after '.User::LOGIN_REACTIVE_TIME.' minutes.');
            }
            $limitTime = Carbon::parse($user->login_reactive_time);
                if ($limitTime > $now){
                    $expireTime = Carbon::now()->addMinutes(User::LOGIN_REACTIVE_TIME)->toISOString();
                    $user->update([
                        'login_reactive_time' => $expireTime,
                        'login_retry_limit' => $user->login_retry_limit + 1
                    ]);
                }
                throw new LoginFailedException('you have exceed the number of limit.you can login after '.User::LOGIN_REACTIVE_TIME.' minutes.');
        }

        if(!Hash::check($input['password'],$user->password)){
            $user->update([
                'login_retry_limit' => $user->login_retry_limit + 1
            ]);
            throw new LoginFailedException('Password is incorrect');
        }

        $roles = $user->getRoleNames();
        if(!$roles->count()){
            throw new LoginFailedException('You have not assigned any role');
        }
        $role = $roles->first();
        if(!in_array(User::PLATFORM['admin'],User::LOGIN_ACCESS[$role])){
            throw new LoginFailedException('you are unable to access this platform');
        }

        $data = $user->toArray();
        $data['token'] = $user->createToken('Admin Login')->plainTextToken;

        $user->update([
            'login_reactive_time' => null,
            'login_retry_limit' => 0
        ]);

        return $this->loginSuccess($data);
    }

    /**
    * @param  Request $request
    *
    * @return  mixed
    */
    public function forgotPassword(Request $request)
    {
        $input = $request->all();
        $request->validate([
            'email' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $user = User::where('email',$input['email'])->firstOrFail();

            $resultOfEmail = false;
            $resultOfSMS = false;
            $code = $this->generateCode();

            if ( User::FORGOT_PASSWORD_WITH['link']['email']){
                $resultOfEmail = $this->sendEmailForResetPasswordLink($user,$code);
            }
            if ( User::FORGOT_PASSWORD_WITH['link']['sms']){
                $resultOfSMS = $this->sendSMSForResetPasswordLink($user,$code);
            }

            DB::commit();

            if ($resultOfEmail && $resultOfSMS){
                return $this->successResponse('otp successfully send.');
            } else if ($resultOfEmail && !$resultOfSMS) {
                return $this->successResponse('otp successfully send to your email.');
            } else if (!$resultOfEmail && $resultOfSMS) {
                return $this->successResponse('otp successfully send to your mobile number.');
            } else {
                throw new FailureResponseException('otp can not be sent due to some issue try again later');
            }
        }catch (Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * This function will send reset password email to given user.
    * 
    * @param  Request $request
    *
    * @return  mixed
    */
    public function resetPassword(Request $request)
    {
        $input = $request->all();
        $request->validate([
            'code' => 'required',
            'newPassword' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $user = User::where('reset_password_code',$input['code'])->first();
            if ($user && $user->reset_password_expire_time) {
                if (Carbon::now()->isAfter($user->reset_password_expire_time)) {
                    return $this->successResponse('Your reset password link is expired on invalid');
                }
            } else {
                return $this->successResponse('Invalid Code');
            }

            $user->update([
                'password' => Hash::make($input['newPassword']),
                'reset_password_expire_time' => null,
                'login_retry_limit' => 0,
                'reset_password_code' => null
            ]);

            $data['username'] = $user->username;
            $data['message'] = 'Your Password Successfully Reset';
            Mail::to($user->email)
                ->send(new MailService('emails.password_reset_success',
                'Reset Password',
                $data));

            DB::commit();
            return $this->successResponse('Password reset successful.');
        }catch (Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * @param  Request $request
    *
    * @return  mixed
    */
    public function validateResetPasswordOtp(Request $request)
    {
        $input = $request->all();
        $request->validate([
            'otp' => 'required',
        ]);

        $user = User::where('reset_password_code',$input['otp'])->first();
        if (!$user || !$user->reset_password_expire_time) {
            return $this->successResponse('Invalid OTP');
        }

        // link expire
        if (Carbon::now()->isAfter($user->reset_password_expire_time)) {
            return $this->successResponse('Your reset password link is expired or invalid');
        }

        return $this->successResponse('Otp verified');
    }

    /**
    * @param  App\Mode\s\User $user
    * @param  string $code
    *
    * @return  bool
    */
    public function sendEmailForResetPasswordLink($user,$code)
    {
        try {
            DB::beginTransaction();
            $expireTime = Carbon::now()->addMinutes(User::FORGOT_PASSWORD_WITH['expireTime'])->toISOString();
            $user->update([
                'reset_password_expire_time' => $expireTime,
                'reset_password_code' => $code
            ]);

            // mail send code
            $data['username'] = $user->username;
            $data['link'] = URL::to('reset-password/'.$code);
            $data['expireTime'] = User::FORGOT_PASSWORD_WITH['expireTime'];
            $data['message'] = 'Click on the link below to reset your password.';
            Mail::to($user->email)
                ->send(new MailService('emails.password_reset',
                'Reset Password',
                $data));

            DB::commit();
            return true;
        }catch (Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * @param  App\Models\User $user
    * @param  string $code
    *
    * @return  bool
    */
    public function sendSMSForResetPasswordLink($user,$code)
    {
        $expireTime = Carbon::now()->addMinutes(User::FORGOT_PASSWORD_WITH['expireTime'])->toISOString();
        $user->update([
            'reset_password_expire_time' => $expireTime,
            'reset_password_code' => $code
        ]);

        // sms send code
        return true;
    }

    /**
    * Change password of logged in user.
    * 
    * @param  Request $request
    *
    * @return  array
    */
    public function changePassword(Request $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->all();
            $request->validate([
                'oldPassword'      => 'required',
                'newPassword'      => 'required',
            ]);

            $user = Auth::user();
            if (! Hash::check($input['oldPassword'],  $user->password)) {
                throw new ChangePasswordFailureException('Current password is invalid.');
            }
            $input['password'] = Hash::make($input['newPassword']);
            $user->update($input);

            DB::commit();
            return $this->changePasswordSuccess('Password Updated Successfully.');
        }catch (Exception $e){
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
    * Generate unique code to reset password of given user.
    * 
    * @return  string
    */
    public function generateCode()
    {
        $code = Str::random(6);
        while (true) {
            $codeExists = User::where('reset_password_code',$code)->exists();
            if ($codeExists) {
                $this->generateCode();
            }
            break;
        }

        return $code;
    }
}
