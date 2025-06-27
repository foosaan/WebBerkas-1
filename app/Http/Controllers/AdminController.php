<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $this->_logout($request);

        return redirect('/')->with('success', 'Anda telah logout.');
    }

    /*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Perform the actual logout action.
     *
     * This function is called by the logout() function in this class.
     *
     * @param Request $request
     */
    /*******  c394c1bb-02f6-493a-9415-11451e166028  *******/
    public function _logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }


}
