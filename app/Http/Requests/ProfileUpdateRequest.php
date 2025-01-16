<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'lastname'   => ['required', 'string', 'max:255'],
            'firstname'  => ['required', 'string', 'max:255'],
            'middlename' => ['required', 'string', 'max:255'],
            'address'    => ['required', 'string', 'max:255'],

        ];
    }
}
