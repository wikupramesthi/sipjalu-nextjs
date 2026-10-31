<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Services\FileUploadService;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $kecamatans = Kecamatan::all();

        $isMobile = preg_match(
            '/Mobile|Android|iPhone|iPad|iPod/i',
            $request->header('User-Agent')
        );

        if ($user->hasRole('user') && $isMobile) {
            return view('pages.mobile.profile-update', [
                'user' => $user,
                'kecamatans' => $kecamatans,
            ]);
        }

        return view('profile.update', [
            'user' => $user,
            'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Update the user's kelurahan information.
     */
    public function getKelurahan($kecamatan_id)
    {
        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatan_id)->orderBy('nama')->pluck('nama', 'id');
        return response()->json($kelurahans);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        $validated = $request->validate([
            'avatar'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|email|unique:users,email,' . $user->uuid . ',uuid',
            'no_hp'          => 'required|unique:users,no_hp,' . $user->uuid . ',uuid',
            'alamat'         => 'nullable|string',
            'kecamatan_id'   => 'nullable|exists:kecamatans,id',
            'kelurahan_id'   => 'nullable|exists:kelurahans,id',
            'tempat_lahir'   => 'nullable|string|max:100',
            'tanggal_lahir'  => 'nullable|date',
            'jenis_kelamin'  => 'nullable|in:L,P',
            'agama'          => 'nullable|string|max:50',
        ]);

        $user->fill([
            'name'           => $validated['name'],
            'email'          => $validated['email'] ?? $user->email,
            'no_hp'          => $validated['no_hp'],
            'alamat'         => $validated['alamat'] ?? null,
            'kecamatan_id'   => $validated['kecamatan_id'] ?? null,
            'kelurahan_id'   => $validated['kelurahan_id'] ?? null,
            'tempat_lahir'   => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir'  => $validated['tanggal_lahir'] ?? null,
            'jenis_kelamin'  => $validated['jenis_kelamin'] ?? null,
            'agama'          => $validated['agama'] ?? null,
        ]);

        if ($request->hasFile('avatar')) {
            $user->avatar = FileUploadService::replace($user->avatar, $request->file('avatar'), 'avatars');
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
