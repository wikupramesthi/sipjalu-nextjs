<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class HomeController extends Controller
{
    /**
     * Menampilkan halaman default / landing page
     */
    public function index()
    {
          if (auth()->check()) {
          return redirect('/backend/dashboard');
     }
        return redirect()->away('https://bekasikota.go.id/');
    }

}
