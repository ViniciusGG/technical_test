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
            'data_scheduled' => ['nullable', 'date', 'required_if:scheduled,true'],
        ];
    }

    public function messages()
    {
        return [
            'sender.different' => 'The sender and receiver must be different.',
            'data_scheduled.required_if' => 'The scheduled date is required when scheduling a transaction.'
        ];
    }
}
