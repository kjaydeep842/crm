<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();

        if ($currentUser->isSuperAdmin()) {
            // Superadmin gets everything
            $users = User::with('organization')->where('id', '!=', $currentUser->id)->get();
            $organizations = Organization::all();
            return view('users.index', compact('users', 'organizations'));
        } elseif ($currentUser->isAdmin()) {
            // Org Admin gets only their staff
            $users = User::where('organization_id', $currentUser->organization_id)
                ->where('id', '!=', $currentUser->id)
                ->get();
            $organizations = Organization::where('id', $currentUser->organization_id)->get();
            return view('users.index', compact('users', 'organizations'));
        }

        abort(403, 'Unauthorized access.');
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ];

        if ($currentUser->isSuperAdmin()) {
            $rules['role'] = 'required|in:admin,staff,superadmin';
            $rules['organization_id'] = 'nullable|exists:organizations,id';
            $rules['staff_role'] = 'nullable|string|max:255';
        } else {
            $rules['staff_role'] = 'required|string|max:255';
        }

        $data = $request->validate($rules);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        if ($currentUser->isSuperAdmin()) {
            $user->role = $data['role'];
            $user->organization_id = $data['organization_id'];
            $user->staff_role = $data['staff_role'];
        } else {
            $user->role = 'staff';
            $user->organization_id = $currentUser->organization_id;
            $user->staff_role = $data['staff_role'];
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User successfully created!');
    }

    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        // Security check
        if (!$currentUser->isSuperAdmin()) {
            if ($user->organization_id !== $currentUser->organization_id || $user->role !== 'staff') {
                abort(403, 'Unauthorized action.');
            }
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ];

        if ($currentUser->isSuperAdmin()) {
            $rules['role'] = 'required|in:admin,staff,superadmin';
            $rules['organization_id'] = 'nullable|exists:organizations,id';
            $rules['staff_role'] = 'nullable|string|max:255';
        } else {
            $rules['staff_role'] = 'required|string|max:255';
        }

        $data = $request->validate($rules);

        $user->name = $data['name'];
        $user->email = $data['email'];
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($currentUser->isSuperAdmin()) {
            $user->role = $data['role'];
            $user->organization_id = $data['organization_id'];
            $user->staff_role = $data['staff_role'];
        } else {
            $user->staff_role = $data['staff_role'];
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User successfully updated!');
    }

    public function destroy($id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        // Security check
        if (!$currentUser->isSuperAdmin()) {
            if ($user->organization_id !== $currentUser->organization_id || $user->role !== 'staff') {
                abort(403, 'Unauthorized action.');
            }
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User successfully deleted!');
    }

    public function storeOrg(Request $request)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:organizations',
        ]);

        Organization::create($data);

        return redirect()->route('users.index')->with('success', 'Organization successfully created!');
    }
}
