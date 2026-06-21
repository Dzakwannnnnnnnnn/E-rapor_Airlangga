<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwitchRoleController extends Controller
{
    /**
     * Switch active user role context stored in session.
     */
    public function switch(Request $request)
    {
        $request->validate([
            'role' => 'required|string|in:admin,teacher,parent',
        ]);

        $user = Auth::user();
        $targetRole = $request->role;

        if (!$user || !$user->hasRole($targetRole)) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk role tersebut.');
        }

        // Store active role in session
        session(['active_role' => $targetRole]);

        // Redirect to dashboard redirect handler
        $roleName = match($targetRole) {
            'teacher' => 'Guru',
            'parent' => 'Orang Tua',
            'admin' => 'Admin',
            default => $targetRole
        };

        return redirect()->route('dashboard')->with('success', "Berhasil masuk ke Area {$roleName}.");
    }
}
