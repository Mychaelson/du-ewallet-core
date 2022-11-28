<?php

namespace App\Http\Controllers\Media;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;

class AssetController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:webp|max:2048',
        ]);
 
        $fileName = $request->file->getClientOriginalName();
        $path = Storage::disk('s3')->put($fileName, file_get_contents($request->file));
        $path = Storage::disk('s3')->url($path);
        dd($path);
 
 
    }
}
