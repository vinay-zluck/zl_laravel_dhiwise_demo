<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('email/verify/{token}',function(\Illuminate\Http\Request $request){
    $email = Crypt::decrypt($request->route('token'));
    
    $user =  User::where('email',$email)->firstOrfail();
    $user->update(['email_verified_at' => Carbon::now()]);

    return response()->json(['success' => 'Email verified successfully']);
});
