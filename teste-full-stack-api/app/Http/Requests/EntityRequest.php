<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EntityRequest extends FormRequest
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
        $rules = [
            'corporate_reason' => 'required|string|max:255',
            'fantasy_name'     => 'required|string|max:255',
            'cnpj'             => [
                'required',
                'string',
                'size:14',
                'regex:/^\d{14}$/',
            ],
            'opening_date'     => 'required|date',
            'active'           => 'boolean',
            'regionalId'       => 'required|exists:regional,id',
            'specialties'      => 'required|array',
        ];

        if ($this->isMethod('post')) {
            $rules['cnpj'][] = Rule::unique('entity', 'cnpj');
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['cnpj'][] = Rule::unique('entity', 'cnpj')->ignore($this->route('id'));
        }
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'corporate_reason.required' => 'A razão social é obrigatória.',
            'corporate_reason.string'   => 'A razão social deve ser um texto.',
            'corporate_reason.max'      => 'A razão social não pode ter mais que 255 caracteres.',

            'fantasy_name.required' => 'O nome fantasia é obrigatório.',
            'fantasy_name.string'   => 'O nome fantasia deve ser um texto.',
            'fantasy_name.max'      => 'O nome fantasia não pode ter mais que 255 caracteres.',

            'cnpj.required' => 'O CNPJ é obrigatório.',
            'cnpj.string'   => 'O CNPJ deve ser uma sequência de números.',
            'cnpj.size'     => 'O CNPJ deve ter exatamente 14 dígitos.',
            'cnpj.regex'    => 'O CNPJ deve conter apenas números.',
            'cnpj.unique'   => 'Este CNPJ já está cadastrado.',

            'opening_date.required' => 'A data de abertura é obrigatória.',
            'opening_date.date'     => 'A data de abertura deve ser uma data válida.',

            'active.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',

            'regionalId.required' => 'O ID da regional é obrigatório.',
            'regionalId.exists'   => 'A regional informada não existe.',

            'specialtyId.required' => 'A especialidade é obrigatório.',
        ];
    }

    public function wantsJson()
    {
        return true;
    }
}
