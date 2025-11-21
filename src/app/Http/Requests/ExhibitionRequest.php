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
            'item_image' => ['required','mimes:jpeg,png,jpg'],
            'category_id' => ['required'],
            'condition_id' => ['required'],
            'item_name' => ['required'],
            'description' => ['required', 'max:255'],
            'price' => ['required','numeric','min:0'],
        ];
    }

    public function messages()
    {
        return[
            'item_image.required' => '画像をアップロードしてください',
            'item_image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'category_id.required' => 'カテゴリーを選択してください',
            'condition_id.required' => '商品の状態を選択してください',
            'item_name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '255文字以内で入力してください',
            'price.required' => '販売価格を入力してください',
            'price.numeric' => '数値で入力してください',
            'price.min' => '0円以上で設定してください',
        ];

    }
}
