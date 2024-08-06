<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|string',
            'name' => 'required|string',
            'address' => 'required|array',
            'address.city' => 'required|string',
            'address.district' => 'required|string',
            'address.street' => 'required|string',
            'price' => 'required|integer',
            'currency' => 'required|string'
        ];
    }

    public function validator() {
        $data = json_decode($this->instance()->getContent(), true);
        return \Validator::make($data, $this->rules(), $this->messages(), $this->attributes());
    }
}
