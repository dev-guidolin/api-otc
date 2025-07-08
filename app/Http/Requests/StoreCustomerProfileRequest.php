<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->user();

        // Aqui, vamos buscar o profile atual do usuário (ou null)
        $profile = $user ? $user->customerProfile : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'size:14'],
            'birth' => ['required', 'date'],
            'owner_address' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'company_document' => ['required', 'string', 'size:18'],

            // Se já existe arquivo, não é obrigatório enviar outro
            'company_document_file' => [
                $profile && $profile->company_document_file ? 'nullable' : 'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
            ],

            'company_social_contract_file' => [
                $profile && $profile->company_social_contract_file ? 'nullable' : 'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
            ],

            'owner_selfie' => [
                $profile && $profile->owner_selfie ? 'nullable' : 'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required.',
            'name.string' => 'Full name must be a valid string.',
            'name.max' => 'Full name may not be greater than 255 characters.',

            'cpf.required' => 'CPF is required.',
            'cpf.string' => 'CPF must be a string.',
            'cpf.size' => 'CPF must follow the format 000.000.000-00.',

            'birth.required' => 'Date of birth is required.',
            'birth.date' => 'Date of birth must be a valid date.',

            'owner_address.required' => 'Address is required.',
            'owner_address.string' => 'Address must be a string.',
            'owner_address.max' => 'Address may not be greater than 255 characters.',

            'company_name.required' => 'Company name is required.',
            'company_name.string' => 'Company name must be a string.',
            'company_name.max' => 'Company name may not be greater than 255 characters.',

            'company_document.required' => 'CNPJ is required.',
            'company_document.string' => 'CNPJ must be a string.',
            'company_document.size' => 'CNPJ must follow the format 00.000.000/0000-00.',

            'company_document_file.required' => 'Company document file is required.',
            'company_document_file.file' => 'Company document must be a valid file.',
            'company_document_file.mimes' => 'Company document must be a PDF or image (jpg, jpeg, png).',

            'company_social_contract_file.required' => 'Social contract file is required.',
            'company_social_contract_file.file' => 'Social contract must be a valid file.',
            'company_social_contract_file.mimes' => 'Social contract must be a PDF or image (jpg, jpeg, png).',

            'owner_selfie.required' => 'Selfie with document is required.',
            'owner_selfie.file' => 'Selfie must be a valid file.',
            'owner_selfie.mimes' => 'Selfie must be an image in jpg, jpeg, or png format.',
        ];
    }
}
