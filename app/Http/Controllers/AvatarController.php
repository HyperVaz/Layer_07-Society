<?php

namespace App\Http\Controllers;

use App\Models\Avatar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AvatarController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        $file = $request->file('avatar');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('avatars', $filename, 'public');


        $avatar = new Avatar();
        $avatar->user_id = Auth::id();
        $avatar->filename = $filename;
        $avatar->path = $path;
        $avatar->disk = 'public';
        $avatar->save();


        return response()->json(['message' => 'Аватар успешно загружен', 'path' => asset('storage/'.$path)], 201);
    }
}
