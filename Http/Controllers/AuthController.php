<?php
namespace App\Modules\Auth\Http\Controllers;

use App\Role;
use App\UserData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['only' => ['logout', 'register']]);
  }

  public function login(Request $request)
  {
    $this->validate($request, [
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $credentials = $request->only('email', 'password');

    $login = self::_login($credentials);

    return response()->json($login['data'], $login['status']);
  }

  public function logout()
  {
    app('auth')->invalidate(app('auth')->getToken());

    return response()->json(true, 200);
  }

  public function register()
  {
    $messages = [
      'name.required' => 'İsim gerekli, lütfen isminizi giriniz',
      'email.required' => 'E-posta adresi gerekli, lütfen e-posta adresi giriniz',
      'email.email' => 'E-posta adresiniz geçersiz, lütfen geçerli bir e-posta adresi giriniz',
      'email.unique' => 'Bu e-posta adresi zaten kullanılıyor',
      'password.required' => 'Şifre gerekli, lütfen şifrenizi giriniz',
      'password.min' => 'Şifreniz en az 6 karakterli olmalı',
      'password.confirmed' => 'Şifreleriniz uyuşmuyor, kontrol ediniz',
      'password_confirmation.required' => 'Şifre tekrarı gerekli'
    ];

    request()->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required',
        'role_id' => 'required'
    ], $messages);

    $unique_id = uniqid('user');

    $user = new \App\User([
        'user_id' => $unique_id,
        'name' => request()->input('name'),
        'email' => request()->input('email'),
        'password' => app('hash')->make(request()->input('password')),
    ]);

    $user->save();

    $role = Role::findOrFail(request()->input('role_id'));

    UserData::create([
      'user_id' => $user->user_id,
      'role_id' => $role->id,
      'profile_image' => 'DEFAULT_IMAGE',
      'biography' => ['tr' => 'Buraya kendinizi anlatan kısa bir metin yazın!']
    ]);

    $login = self::_login(request()->only('email', 'password'));

    return response()->json($login['data'], $login['status']);
  }

  public function isAuthenticated()
  {
      return response()->json( (bool) auth()->user(), 200);
  }

  private static function _login($credentials)
  {
    try{
        if (!$token = app('auth')->attempt($credentials)){

            return ['data' => ['error' => 'Invalid Credentials'], 'status' => 401];
        }
    }catch(JWTException $e) {

        return ['data' => ['error' => 'Could not create a token'], 'status' => 500];
    }

    return ['data' => ['token' => $token], 'status' => 200];
  }
}
