<?php

namespace App\Http\Controllers;

use App\Helpers\DateFormatter;
use App\Http\Requests\ValidationPersonRequest;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{

    public function first(ValidationPersonRequest $request)
    {
        $person = Person::first();
        if ($person)
            return ["messaje" => "No se permiten mas registros root de persona."];
        return $this->register($request);
    }

    public function register(ValidationPersonRequest $request)
    {
        $person = new Person();
        $person->name = strtolower($request->name);
        $person->first_surname = strtolower($request->first_surname);
        $person->last_surname = strtolower($request->last_surname);
        $person->ci = strtoupper($request->ci);
        $person->birthdate = $request->birthdate;
        $person->created_at = DateFormatter::today();
        $person->updated_at = DateFormatter::today();
        $person->save();
        return $person;
    }

    public function update(string $id, ValidationPersonRequest $request)
    {
        $person = Person::find($id);
        $person->name = strtolower($request->name);
        $person->first_surname = strtolower($request->first_surname);
        $person->last_surname = strtolower($request->last_surname);
        $person->ci = strtoupper($request->ci);
        $person->birthdate = $request->birthdate;
        $person->updated_at = DateFormatter::today();
        $person->save();
        return $person;
    }

    public function show()
    {
        $people = Person::get();
        return $people;
    }

    public function findByCi(string $ci)
    {
        $person = Person::where('ci', $ci)->first();

        if (!$person)
            return ["messaje" => "No existe una persona con el documento: " . $ci];
        return $person;
    }
    public function find(string $id)
    {
        $person = Person::find($id);

        if (!$person)
            return ["messaje" => "La persona buscada no se encuentra registrada."];
        return $person;
    }
}
