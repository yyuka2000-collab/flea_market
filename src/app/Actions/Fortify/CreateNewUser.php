<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * 新規ユーザー作成
     * 登録後、メール認証メールが自動送信される（MustVerifyEmail実装が前提）
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name'                  => ['required', 'string', 'max:20'],
            'email'                 => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password'              => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8', 'max:72'],
        ], [
            'name.required'                  => 'お名前を入力してください',
            'name.max'                       => 'お名前は20文字以内で入力してください',
            'email.required'                 => 'メールアドレスを入力してください',
            'email.email'                    => 'メールアドレスはメール形式で入力してください',
            'email.max'                      => 'メールアドレスは255文字以内で入力してください。',
            'email.unique'                   => 'このメールアドレスは既に登録されています。',
            'password.required'              => 'パスワードを入力してください',
            'password.min'                   => 'パスワードは8文字以上で入力してください',
            'password.max'                   => 'パスワードは72文字以内で入力してください。',
            'password.confirmed'             => 'パスワードと一致しません',
            'password_confirmation.required' => 'パスワードを入力してください',
            'password_confirmation.min'      => 'パスワードは8文字以上で入力してください',
            'password_confirmation.max'      => 'パスワードは72文字以内で入力してください。',
        ])->validate();

        $user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // メール認証メールを送信
        $user->sendEmailVerificationNotification();

        return $user;
    }
}