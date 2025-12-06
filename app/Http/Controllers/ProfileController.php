<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
    $user = $request->user();

    // 1) Actualizar los campos validados (nombre, email, etc.)
    $user->fill($request->validated());

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    // 2) Si viene una foto nueva, la procesamos
    if ($request->hasFile('foto')) {
    $file = $request->file('foto');
    $filename = time() . '_' . $file->getClientOriginalName();

    // Carpeta public/perfiles
    $destinationPath = public_path('perfiles');

    if (! file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    // Mover el archivo a public/perfiles
    $file->move($destinationPath, $filename);

    // Guardar solo el nombre del archivo en BD
    $user->foto = $filename;
}


    // 3) Guardamos todos los cambios
    $user->save();

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
}
