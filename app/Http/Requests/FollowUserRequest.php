<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'followed_user_id'      =>  ['required', 'exists:users,id']
        ];
    }

    public function messages()
    {
        return [
            'followed_user_id.required' =>  'Please specify the user you want to follow.',
            'followed_user_id.exists'   =>  'The user you are trying to follow does not exist.'
        ];
    }
}
