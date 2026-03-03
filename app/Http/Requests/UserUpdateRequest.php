<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $profileId = $this->route('user');
        $userId = \App\Models\UserProfile::findOrFail($profileId)->user_id;

        return [
            'first_name'     => ['required', 'string', 'max:255'],
            'middle_name'    => ['nullable', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'contact_number' => ['required', 'string', 'max:20'],
            'email'          => ['required', 'email', 'max:255', "unique:users,email,{$userId}"],
            'password'       => ['nullable', 'string', 'min:8'],
            'status'         => ['required', 'boolean'],
        ];
    }
}
