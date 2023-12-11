<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use Illuminate\Validation\Rules;

class UserTableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                'avatar' => ['string', 'max:255'],
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'firstname' => ['required', 'string', 'max:255'],
                'middlename' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'birthdate' => ['required', 'date', 'max:255'],
                'branchid' => ['integer', 'max:255'],
                'branchname' => ['required', 'string', 'max:255'],
                'accesstype' => ['required', 'string', 'max:255'],
                'status' => ['string', 'max:255'],
        ];
    }
}
