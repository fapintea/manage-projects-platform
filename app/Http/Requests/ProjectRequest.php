<?php

namespace Diploma\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Config;

/**
 *  @author Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && (Auth::user()->isTeacher() || Auth::user()->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:3|max:100',
            'description' => 'required|min:0|max:10000',
            'nr_students' => 'required|integer'
        ];
    }

    public function messages() {
        return [
            'title.required' => 'Câmpul temă este obligatoriu !',
            'title.min' => 'Câmpul temă trebuie să conţină cel puţin 3 caractere !',
            'title.max' => 'Câmpul temă trebuie să conţină cel mult 100 caractere !',
            'description.required' => 'Câmpul descriere este obligatoriu !',
            'description.min' => 'Câmpul descriere trebuie să conţină cel puţin 1 caracter !',
            'description.max' => 'Câmpul descriere trebuie să conţină cel mult 10000 de caractere !',
            'nr_students.required' => 'Câmpul număr studenţi este obligatoriu !',
            'nr_students.integer' => 'Câmpul număr studenţi trebuie să fie un număr întreg !'
        ];
    }
}
