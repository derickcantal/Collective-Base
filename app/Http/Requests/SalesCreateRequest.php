<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesCreateRequest extends FormRequest
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
            'salesname',
            'salesavatar',
            'cabid',
            'cabinetname',
            'productname',
            'qty',
            'origprice',
            'srp',
            'total',
            'grandtotal',
            'userid',
            'username',
            'accesstype',
            'branchid',
            'branchname',
            'collected_status',
            'returned',
            'snotes',
            'created_by',
            'updated_by',
            'posted',
            'mod',
            'status',
        ];
    }
}
