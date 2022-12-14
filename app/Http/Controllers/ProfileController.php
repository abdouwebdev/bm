<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected function messages()
    {
        return [
            'password.confirmed' => 'confirmation password is not the same!'
        ];
    }

    public function changePassword()
    {
        return view('profile.changepassword');
    }

    public function updatePassword()
    {
        $this->validate(request(), [
            'old_password' => 'required',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], $this->messages());
        $currentPassword = auth()->user()->password;
        $old_password = request('old_password');

        if (Hash::check($old_password, $currentPassword)) {
            auth()->user()->update([
                'password' => Hash::make(request('password'))
            ]);
            return redirect()->route('profile.setting');
        } else {
            return back();
        }
    }

    public function edit()
    {
        return view('profile.setting');
    }

    public function update(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required|min:3|max:50|string',
            'username' => 'required',
            'avatar' => 'mimes:png,jpg,jpeg,ico,svg',
        ], $this->messages());


        $file = $request->avatar;
        $namaGambar = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $namaGambar = $namaGambar . '_' . time() . '.' . $file->extension();

        $user = auth()->user();
        if ($user->avatar == null) {
            if (request()->file('avatar')) {
                // $thumbnail = request()->file('avatar')->storeAs($file);
                $file->storeAs('img/avatar',  $namaGambar);
            } else {
                $namaGambar = null;
            }
        } else {
            if (request()->file('avatar')) {
                Storage::delete($user->avatar);
                // $thumbnail = request()->file('avatar')->storeAs("avatar");
                $file->storeAs('img/avatar',  $namaGambar);
            } else {
                $namaGambar = $user->avatar;
            }
        }

        $user->update([
            'name' => request('name'),
            'avatar' => $namaGambar,
            'username' => request('username')
        ]);
        return back()->with('success', 'Account successfully updated');
    }
}
