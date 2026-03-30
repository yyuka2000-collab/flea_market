<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image_path'  => ['nullable', 'file', 'image', 'mimes:jpeg,png', 'max:2048'],
            'name'        => ['required', 'max:20'],
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address'     => ['required', 'max:100'],
            'building'    => ['nullable', 'max:100'],
        ];
    }

    // バリデーションメッセージ
    public function messages(): array
    {
        return [
            'image_path.image'     => '画像ファイルをアップロードしてください',
            'image_path.mimes'     => 'プロフィール画像はjpegまたはpng形式でアップロードしてください',
            'image_path.max'       => '画像サイズは2MB以内にしてください',
            'name.required'        => 'ユーザー名を入力してください',
            'name.max'             => 'ユーザー名は20文字以内で入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex'    => '郵便番号はハイフンありの8文字で入力してください',
            'address.required'     => '住所を入力してください',
            'address.max'          => '住所は100文字以内で入力してください',
            'building.max'         => '建物名は100文字以内で入力してください',
        ];
    }
}
