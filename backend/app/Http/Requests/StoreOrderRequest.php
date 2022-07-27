<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StoreOrderRequest extends FormRequest
{
    public function authorize(Request $request)
    {
        if ($request->user_id) {
            return true;
        }
    }


    public function rules()
    {
        return [
            'user_id' => 'required',
            'products' => 'required',
        ];
    }
}
