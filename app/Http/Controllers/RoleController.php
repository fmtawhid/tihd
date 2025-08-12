<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    
    public function __construct()
    {
        $this->module_title = 'Role';
        $this->module_name = 'role';
        $this->module_icon = 'fa fa-users';
        $this->module_action = 'List';
    }

    public function index()
    {
        $roles = Role::whereNotIn('name', ['admin', 'user'])->paginate(10);

        return view('backend.roles.index', [
            'roles' => $roles,
            'module_title' => $this->module_title,
            'module_name' => $this->module_name,
            'module_icon' => $this->module_icon,
            'module_action' => 'List',
        ]);
    }

    public function create()
    {
        return view('backend.roles.create', [
            'module_title' => $this->module_title,
            'module_name' => $this->module_name,
            'module_icon' => $this->module_icon,
            'module_action' => 'Create',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:roles,title',
            'name'  => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create([
            'title' => $request->title,
            'name'  => $request->name,
        ]);

        return redirect()->route('backend.role.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        return view('backend.roles.edit', [
            'role' => $role,
            'module_title' => $this->module_title,
            'module_name' => $this->module_name,
            'module_icon' => $this->module_icon,
            'module_action' => 'Edit',
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:roles,title,' . $role->id,
            'name'  => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update([
            'title' => $request->title,
            'name'  => $request->name,
        ]);

        return redirect()->route('backend.role.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if (env('IS_DEMO')) {
            return response()->json(['status' => false, 'message' => 'Permission denied in demo.']);
        }

        $role->delete();

        return response()->json(['status' => true, 'message' => 'Role deleted successfully.']);
    }
    
}
