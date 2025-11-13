<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookEventRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'ticket_id' => 'required|integer|exists:tickets,id',
            'quantity'  => 'required|integer|min:1',
        ];
    }
}