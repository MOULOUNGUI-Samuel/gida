<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemandeRequest extends FormRequest
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
            'titre' => 'required|string|max:255',
            'categorie' => 'required|string|max:255',
            'priorite' => 'required|string|max:255',
            'date_limite' => 'nullable|date',
            'reference' => 'nullable|string|max:255',
            'nom' => 'required|string|max:255',
            'fonction' => 'required|string|max:255',
            'societe' => 'required|string|max:255',
            'description' => 'required|string',
            'mail' => 'required|email|max:255',
            'infos_supplementaires' => 'nullable|string',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,html,htm|max:10240',
            'fichiers' => 'nullable|array',
            'fichiers.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,html,htm|max:10240',
        ];
    }
}
