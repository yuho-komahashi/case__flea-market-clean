<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment_method' => ['required'],
            'shipping_postcode' => ['required'],
            'shipping_address' => ['required'],
        ];
        //表示がreadonlyのため、入力形式のチェックはなし、存在確認のみ
    }

    public function messages()
    {
        return[
            'payment_method.required' => '支払い方法を選択してください',
            'shipping_postcode.required' => '配送先の郵便番号が取得できませんでした',
            'shipping_address.required' => '配送先の住所が取得できませんでした',
        ];
        //入力チェックはなしなので、アプリ側で表示すべき情報を取得できなかった場合に表示

    }
}
