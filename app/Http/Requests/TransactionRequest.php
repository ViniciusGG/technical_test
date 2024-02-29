<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'amount' => ['required', 'numeric'],
            'sender' => ['required', 'numeric', 'exists:bank_accounts,id', 'different:receiver'],
            'receiver' => ['required', 'numeric', 'exists:bank_accounts,id'],
            'scheduled' => ['nullable', 'boolean'],
        ];
    }
}
