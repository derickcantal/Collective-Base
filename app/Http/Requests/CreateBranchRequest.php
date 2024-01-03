<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\branch;

class CreateBranchRequest extends FormRequest
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
            'branchname' => ['required', 'string', 'max:255', 'unique:'.branch::class],
            'branchaddress' => ['required', 'string', 'max:255'],
            'branchcontact' => ['required', 'string', 'max:255'],
            'branchemail' => ['required', 'string', 'max:255', 'email'],
            'cabinetcount' => ['required', 'string', 'max:255'],
        ];
    }
}
