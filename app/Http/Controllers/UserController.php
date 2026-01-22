<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // ← ADD THIS LINE

class UserController extends Controller
{
    use AuthorizesRequests; // ← ADD THIS LINE (gives $this->authorize())

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('admin.users.edit', compact('user'));
    }

public function update(Request $request, User $user)
{
    $this->authorize('update', $user);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'id_number' => 'required|string|max:20|unique:users,id_number,' . $user->id,
        'role' => 'required|in:student,staff,admin',
        'department' => 'nullable|string|max:100',
        'phone' => 'nullable|string|max:15',
        'gender' => 'nullable|in:male,female,other',
        'year' => 'nullable|string|max:20',
        'password' => 'nullable|confirmed|min:8',
    ]);

    // Debug: see what data is coming in
    \Log::info('User update attempt', [
        'user_id' => $user->id,
        'request_data' => $request->all(),
        'validated' => $validated,
    ]);

    $data = $validated;

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('dashboard')->with('success', 'User updated successfully.');
}
}