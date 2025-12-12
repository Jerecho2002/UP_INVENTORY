<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryStoreRequest extends FormRequest
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
            'item_classification_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'invoice' => 'required|string|max:50',
            'fund_source' => 'required|string|max:50',
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'unit_cost' => 'required|numeric',
            'serial_numbers' => 'required|array|min:1',
            'serial_numbers.*' => 'required|string|max:50',
            'property_number' => 'required|string|max:50',
            'pr_number' => 'required|string|max:50',
            'po_number' => 'required|string|max:50',
            'remarks' => 'required|string|max:50',
            'date_acquired' => 'required|date',
            'status' => 'nullable|string|max:50',
        ];
    }
}
