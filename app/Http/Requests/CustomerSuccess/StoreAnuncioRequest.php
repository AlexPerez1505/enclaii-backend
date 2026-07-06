<?php

namespace App\Http\Requests\CustomerSuccess;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnuncioRequest extends FormRequest
{
    public const TIPOS = [
        'anuncios_internos',
        'mejoras',
        'mantenimiento',
        'politicas',
    ];

    public const PUBLICOS = [
        'todos',
        'doctores',
        'administradores',
    ];

    public const CANALES = [
        'web',
        'email',
        'push',
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole('Customer Success') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'contenido' => ['required', 'string'],
            'tipo' => ['required', 'string', 'in:' . implode(',', self::TIPOS)],
            'publico_objetivo' => ['required', 'string', 'in:' . implode(',', self::PUBLICOS)],
            'canales' => ['nullable', 'array'],
            'canales.*' => ['string', 'in:' . implode(',', self::CANALES)],
            'fecha_publicacion' => ['nullable', 'date'],
            'activo' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.in' => 'El tipo de anuncio no es válido.',
            'publico_objetivo.in' => 'El público objetivo no es válido.',
            'canales.*.in' => 'Uno de los canales seleccionados no es válido.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('activo')) {
            $this->merge([
                'activo' => filter_var($this->input('activo'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }
    }
}
