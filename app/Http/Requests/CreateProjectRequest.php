<?php

namespace App\Http\Requests;

use App\Enum\Priority;
use App\Enum\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProjectRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ['required'], 
            "client" => ["required"], 
            "description" => "nullable", 
            "status" => ["required", Rule::enum(Status::class)], 
            "priority" => ["required", Rule::enum(Priority::class)], 
            "startDate" => ["required", 'date'], 
            "dueDate" => ["required", 'date', 'after_or_equal:startDate']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The project name field is required.',
            'client.required' => 'The client name field is required.',
            'status.required' => 'Please select a project status.',
            'status.Illuminate\Validation\Rules\Enum' => 'The selected status is invalid.',
            'priority.required' => 'Please select a priority level.',
            'priority.Illuminate\Validation\Rules\Enum' => 'The selected priority level is invalid.',
            'startDate.required' => 'The start date is required.',
            'startDate.date' => 'The start date must be a valid date.',
            'dueDate.required' => 'The due date is required.',
            'dueDate.date' => 'The due date must be a valid date.',
            'dueDate.after_or_equal' => 'The due date cannot be earlier than the start date.',
        ];
    }
}
