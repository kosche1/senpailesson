<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function delete(Request $request): RedirectResponse
    {
        // dd($request->all());
        $user = \App\Models\User::find($request->id);
        if ($user) {
            $user->delete();
            return redirect()->back()->with('status', 'user-deleted');
        }
    }

    public function adduser()
    {
        return view('AddUser');
    }
    public function addpost(Request $request)
    {
        // dd($request->all());
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        if ($user) {
            return redirect()->route('dashboard')->with('status', 'user-added');
        }
    }

    public function EditUser(Request $request)
    {

        $id = $request->id;
        $name = $request->name;
        $email = $request->email;

        $user = User::find($id);

        $user->name = $name;
        $user->email = $email;
        $user->save();
        return Redirect::route('dashboard');
    }
}
