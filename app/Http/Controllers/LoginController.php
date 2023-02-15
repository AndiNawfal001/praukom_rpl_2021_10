<?php

namespace App\Http\Controllers;

use app\Models\PenggunaModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login()
    {

        return view('login.index');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // dd($request->all());

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            //dd(Auth::user()->username);
            $request->session()->regenerate();
            //ddd($request);
            flash()->addSuccess('Berhasil Login.');
            return redirect()->intended('/dashboard');
        }
        flash()->addError('Wrong username or password');
        return back();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    //syntax suntuk membuat innerjoin
    // public function innerjoin(){

    //     $result = DB::table('level_user')
    //     ->join('pengguna', 'level_user.id_level', '=' , 'pengguna.' . auth()->user()->id_level)
    //     ->select('level_user.nama_level','pengguna.' . auth()->user()->id_level)
    //     ->get();
    //     return $result;
    // }


}
