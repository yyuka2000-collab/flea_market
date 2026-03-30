<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name'         => ['required', 'max:100'],
            'brand_name'   => ['nullable', 'max:50'],
            'description'  => ['required', 'string', 'max:255'],
            'image'        => ['required', 'file', 'mimes:jpeg,png', 'max:2048'],
            'category_ids' => ['required'],
            'condition'    => ['required'],
            'price'        => ['required', 'numeric', 'min:50', 'max:9999999'],
        ];
    }

    // バリデーションメッセージ
    public function messages(): array
    {
        return [
            'name.required'         => '商品名を入力してください',
            'name.max'              => '商品名は100文字以内で入力してください',
            'brand_name.max'        => 'ブランド名は50文字以内で入力してください',
            'description.required'  => '商品説明を入力してください',
            'description.max'       => '商品説明は255文字以内で入力してください',
            'image.required'        => '商品画像をアップロードしてください',
            'image.mimes'           => '商品画像はjpegまたはpng形式でアップロードしてください',
            'image.max'             => '画像サイズは2MB以内にしてください',
            'category_ids.required' => '商品のカテゴリーを選択してください',
            'condition.required'    => '商品の状態を選択してください',
            'price.required'        => '販売価格を入力してください',
            'price.numeric'         => '販売価格は数値で入力してください',
            'price.min'             => '販売価格は50円以上で入力してください',
            'price.max'             => '販売価格は9,999,999円以内で入力してください',
        ];
    }
}
