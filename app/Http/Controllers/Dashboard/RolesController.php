<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleAbility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Gate::authorize('roles.view');

        $roles = Role::paginate();

        return view('dashboard.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Gate::authorize('roles.create');

        $role = new Role();
        return view('dashboard.roles.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Gate::authorize('roles.create');

        $request->validate([
            'name' => 'required|string',
            'abilities' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $role = Role::create([
                'name' => $request->post('name'),
            ]);

            foreach ($request->abilities as $ability => $value) {

                RoleAbility::create([
                    'role_id' => $role->id,
                    'ability' => $ability,
                    'type' => $value
                ]);
            }

            DB::commit();

            return redirect()->route('dashboard.roles.index')
                ->with('message', 'Role created successfully')
                ->with('type', 'success');
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('roles.view');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        Gate::authorize('roles.update');

        $role_abilities = $role->abilities()->pluck('type', 'ability')->toArray();
        // dd($role_abilities);
        return view('dashboard.roles.edit', compact('role', 'role_abilities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        Gate::authorize('roles.update');

        $request->validate([
            'name' => 'required|string',
            'abilities' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $role->update([
                'name' => $request->post('name'),
            ]);

            foreach ($request->abilities as $ability => $value) {

                RoleAbility::updateOrCreate(
                    [
                        'role_id' => $role->id,
                        'ability' => $ability,
                    ],
                    [
                        'type' => $value
                    ]
                );
            }

            DB::commit();

            return redirect()->route('dashboard.roles.index')
                ->with('message', 'Role updated successfully')
                ->with('type', 'success');

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('roles.delete');

        Role::destroy($id);
    }
}
