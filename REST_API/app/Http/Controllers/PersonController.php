<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return Person::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'email' => 'required|string|email|max:255|unique:people',
                'latitude' => 'required|regex:/^-?\d+(\.\d{1,16})?$/',
                'longitude' => 'required|regex:/^-?\d+(\.\d{1,16})?$/',
            ],
            [
                'required' => 'El campo :attribute es requerido.',
                'string' => 'El campo debe ser una cadena de texto.',
                'max' => 'El campo :attribute supera la cantidad de caracteres permitidos.',
                'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
                'unique' => 'El campo :attribute debe ser único, ya hay otra persona con este.',
                'integer' => 'El campo :attribute debe ser un número entero.',
                'min' => 'El campo :attribute debe tener al menos :min caracteres.',
                'phone.regex' => 'El campo :attribute debe ser un número de teléfono válido.',
                'double' => 'El campo :attribute debe ser un número decimal.',
                'latitude.regex' => 'La latitud debe ser un número decimal con máximo 16 decimales.',
                'latitude.regex' => 'La longitud debe ser un número decimal con máximo 16 decimales.'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        return response()->json(Person::create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person) {
        return $person;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Person $person) {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'nullable|string|max:255',
                'phone' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'email' => 'nullable|string|email|max:255|unique:people' . $person->id,
                'latitude' => 'nullable|regex:/^-?\d+(\.\d{1,16})?$/',
                'longitude' => 'nullable|regex:/^-?\d+(\.\d{1,16})?$/',
            ],
            [
                'string' => 'El campo debe ser una cadena de texto.',
                'max' => 'El campo :attribute supera la cantidad de caracteres permitidos.',
                'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
                'unique' => 'El campo :attribute debe ser único, ya hay otra persona con este.',
                'integer' => 'El campo :attribute debe ser un número entero.',
                'min' => 'El campo :attribute debe tener al menos :min caracteres.',
                'phone.regex' => 'El campo :attribute debe ser un número de teléfono válido.',
                'latitude.regex' => 'La latitud debe ser un número decimal con máximo 16 decimales.',
                'latitude.regex' => 'La longitud debe ser un número decimal con máximo 16 decimales.'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $person->update($request->all());
        return response()->json($person, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person) {
        $person->delete();
    }

    /**
     * Search for a person by fieldName.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $fieldName, $fieldValue) {
        if (in_array($fieldName, Person::searchableAttributes())) {
            $fieldName = $request->fieldName;
            $fieldValue = $request->fieldValue;
            $people = Person::where($fieldName, 'like', '%' . $fieldValue . '%')->get();
            return response()->json($people, 200);
        } else {
            return response()->json(
                ['error' => "'" . $fieldName . "' " .
                    'no es un campo válido para búsqueda. Campos válidos: ' .
                    implode(', ', Person::searchableAttributes())],
                400
            );
        }
    }
}
