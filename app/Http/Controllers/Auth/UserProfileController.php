<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    // GET: Show profile page
    public function profile(Request $request)
    {
        $tab = session('tab', 'detail');
        $user = auth()->user();
        // return $user;
        return view('v1-1.user.user-profile', [
            'app' => [
                'title' => 'Pengaturan',
                'desc' => 'Pengaturan Profile',
                'sidebar_header' => $user->name,
                'sidebar_subheader' => 'User Profile ' . $user->username,
            ],
            'user' => $user,
            'tab' => $tab,
        ]);
    }

    // POST: Update profile
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'name' => 'required|string|max:255',
        ];
        if ($request->filled('email')) {
            $rules['email'] = 'email|max:255|unique:users,email,' . $user->id;
        }
        if ($request->filled('phone')) {
            $rules['phone'] = 'string|max:20';
        }
        $validated = Validator::make($request->all(), $rules, [
            'name.required' => 'Nama tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
        ]);
        if ($validated->fails()) {
            return redirect()->back()->withInput()->withErrors($validated)->with('tab', 'detail');
        }
        $user->update($validated->validated());
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    // POST: Change password
    public function changePassword(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:6', 'same:new_password_confirmation'],
            'new_password_confirmation' => ['required', 'string', 'min:6'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.same' => 'Konfirmasi password baru tidak cocok.',
            'new_password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'new_password_confirmation.min' => 'Konfirmasi password minimal 6 karakter.',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator)->with('tab', 'password');
        }
        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->withInput()->withErrors(['current_password' => 'Password lama salah.'])->with('tab', 'password');
        }
        $user->password = bcrypt($request->new_password);
        $user->save();
        return redirect()->back()->with('success', 'Password updated successfully.');
    }
}
