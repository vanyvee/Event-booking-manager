<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckEvent extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'location'      => 'required|string|max:255',
            'start_date'    => 'required|date|after:now',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'total_seats'   => 'required|integer|min:1',
        ];
    }
}