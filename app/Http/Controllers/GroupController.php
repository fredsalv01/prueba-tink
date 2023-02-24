<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $group = Group::paginate(10)->users;
        return response()->json([
            'groups' => $group
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function listGroups()
    {
        $groups = Group::all();
        foreach ($groups as $group) {
            $group->male = User::find($group->male_id);
            $group->female = User::find($group->female_id);
        }
        return response()->json($groups, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // store users group
        $rules = [
            'name' => 'required|string',
            'description' => 'required|string',
            'male_id' => 'required|unique:groups',
            'female_id' => 'required|unique:groups'
        ];
        $messages = [
            'name.required' => 'El nombre es requerido',
            'description' => 'La descripcion es requerida',
            'male_id.required' => 'El id del varon es requerido',
            'male_id.unique' => 'El id del varon es unico',
            'female_id.required' => 'El id de la mujer es requerido',
            'female_id.required' => 'El id de la mujer es unico'
        ];
        $validate = Validator::make($request->all(), $rules, $messages);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validate->errors()
            ], 422);
        }

        $group = new Group();
        $group->name = $request->name;
        $group->description = $request->description;
        $group->male_id = $request->male_id;
        $group->female_id = $request->female_id;
        $group->save();

        $male = User::find($group->male_id);
        $female = User::find($group->female_id);

        $group->male = $male;
        $group->female = $female;

        return response()->json([
            'message' => 'Group created successfully',
            'group' => $group
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // show group with users
        $group = Group::find($id);
        $group->male = User::find($group->male_id);
        $group->female = User::find($group->female_id);
        return response()->json([
            'group' => $group
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name' => 'required|string',
            'description' => 'string|default:null',
            'male_id' => ['required','unique:groups,male_id', $id],
            'female_id' => ['required', 'unique:groups,female_id', $id]
        ];
        $messages = [
            'name.required' => 'El nombre es requerido',
            'description' => 'La descripcion es requerida',
            'male_id.required' => 'El id del varon es requerido',
            'male_id.unique' => 'El id del varon es unico',
            'female_id.required' => 'El id de la mujer es requerido',
            'female_id.unique' => 'El id de la mujer es unico',
        ];
        $validate = Validator::make($request->all(), $rules, $messages);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validate->errors()
            ], 422);
        }

        $group = Group::find($id);
        if (!$group)
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        $group->update($request->all());
        $group->save();

        $group->male = User::find($group->male_id);;
        $group->female = User::find($group->female_id);
        return response()->json([
            'message' => 'Group created successfully',
            'group' => $group
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        // delete group
        $group = Group::find($group->id);
        if (!$group)
            return response()->json([
                'message' => 'Group not found'
            ], 404);
        $group->delete();
        return response()->json([
            'message' => 'Group deleted successfully'
        ]);
    }
}