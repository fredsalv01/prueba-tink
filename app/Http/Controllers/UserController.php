<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::paginate(10)->users;
        $users = User::paginate(10)->groups;
        return response()->json([
            'groups' => $groups,
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    public function listUsers()
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate $request
        $rules = [
            'name' => 'required|string',
            'lastname' => 'required|string',
            'dni' => 'required|string|unique:users',
            'birthdate' => 'required|date',
            'phone' => 'required|string',
        ];
        $messages = [
            'name.required' => 'El nombre es requerido',
            'lastname.required' => 'El apellido es requerido',
            'dni.required' => 'El DNI es requerido',
            'dni.unique' => 'El DNI ya existe',
            'birthdate.required' => 'La fecha de nacimiento es requerida',
            'phone.required' => 'El teléfono es requerido',
        ];
        $validate = Validator::make($request->all(), $rules, $messages);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validate->errors()
            ], 422);
        }
        // create user
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // show user with id
        $user = User::find($id);
        if (!$user)
            return response()->json([
                'message' => 'User not found'
            ], 404);
        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validate $request
        $rules = [
            'name' => 'required|string',
            'lastname' => 'required|string',
            'dni' => ['required', 'string', 'unique:users,dni,' . $id],
            'birthdate' => 'required|date',
            'phone' => 'required|string',
        ];
        $messages = [
            'name.required' => 'El nombre es requerido',
            'lastname.required' => 'El apellido es requerido',
            'dni.required' => 'El DNI es requerido',
            'dni.unique' => 'El DNI ya existe',
            'birthdate.required' => 'La fecha de nacimiento es requerida',
            'phone.required' => 'El teléfono es requerido',
        ];
        $validate = Validator::make($request->all(), $rules, $messages);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validate->errors()
            ], 422);
        }
        // update user
        $user = User::find($id);
        if (!$user)
            return response()->json([
                'message' => 'User not found'
            ], 404);
        $user->update($request->all());
        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // delete user
        $user = User::find($id);
        if (!$user)
            return response()->json([
                'message' => 'User not found'
            ], 404);
        $user->delete();
        return response()->json(null, 204);
    }
}