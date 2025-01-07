<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'dni' => 'nullable|string|max:20',
            'is_admin' => 'sometimes|boolean',
            'is_technician' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'is_seller' => 'sometimes|boolean',
            'can_add_products' => 'sometimes|boolean',
        ]);

        try {
            DB::beginTransaction();

            $user = new User();
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->username = $validatedData['username'];
            $user->password = Hash::make($validatedData['password']);
            $user->dni = $validatedData['dni'];
            $user->is_admin = $request->has('is_admin');
            $user->is_technician = $request->has('is_technician');
            $user->is_active = $request->has('is_active', true);
            $user->is_seller = $request->has('is_seller');
            $user->can_add_products = $request->has('is_seller');
            $user->save();

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the user. Please try again.');
        }
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'dni' => 'nullable|string|max:20',
            'is_admin' => 'sometimes|boolean',
            'is_technician' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'is_seller' => 'sometimes|boolean',
            'can_add_products' => 'sometimes|boolean',
        ]);

        try {
            DB::beginTransaction();

            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->dni = $validatedData['dni'];
            
            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }
            
            $user->is_admin = $request->has('is_admin');
            $user->is_technician = $request->has('is_technician');
            $user->is_active = $request->has('is_active');
            $user->is_seller = $request->has('is_seller');
            $user->can_add_products = $request->has('is_seller');
            $user->save();

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the user. Please try again.');
        }
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        try {
            DB::beginTransaction();
            $user->delete();
            DB::commit();
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the user. Please try again.');
        }
    }

    public function toggleActive(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'You cannot change the status of your own account.');
        }

        try {
            DB::beginTransaction();
            $user->is_active = !$user->is_active;
            $user->save();
            DB::commit();

            $status = $user->is_active ? 'activated' : 'deactivated';
            return redirect()->route('users.index')->with('success', "User {$status} successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error toggling user active status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while changing the user status. Please try again.');
        }
    }

    public function deactivateNonAdmins()
    {
        if (!Auth::user()->is_admin) {
            return redirect()->route('users.index')->with('error', 'You do not have permission to perform this action.');
        }

        try {
            DB::beginTransaction();
            User::where('is_admin', false)->update(['is_active' => false]);
            DB::commit();
            return redirect()->route('users.index')->with('success', 'All non-admin users have been deactivated.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deactivating non-admin users: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deactivating non-admin users. Please try again.');
        }
    }

    public function activateNonAdmins()
    {
        if (!Auth::user()->is_admin) {
            return redirect()->route('users.index')->with('error', 'You do not have permission to perform this action.');
        }

        try {
            DB::beginTransaction();
            User::where('is_admin', false)->update(['is_active' => true]);
            DB::commit();
            return redirect()->route('users.index')->with('success', 'All non-admin users have been activated.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error activating non-admin users: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while activating non-admin users. Please try again.');
        }
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('users.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        

        try {
            DB::beginTransaction();


            if ($request->hasFile('profile_picture')) {
                if ($user->profile_picture) {
                    Storage::disk('public')->delete($user->profile_picture);
                }
                
                $path = $request->file('profile_picture')->store('profile-pictures', 'public');
                $user->profile_picture = $path;
            }

            $user->save();
            DB::commit();
            return redirect()->route('users.edit-profile')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating profile: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating your profile. Please try again.');
        }
    }
}

