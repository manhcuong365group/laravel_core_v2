<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditorController extends Controller
{
    /**
     * Handle image upload from CKEditor
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');

            // Validate file
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            ]);

            $fileName = time() . '_' . $file->getClientOriginalName();

            // Store the file in public/uploads/editor
            $path = $file->storeAs('editor', $fileName, 'public');

            $url = Storage::url($path);

            // CKEditor 4 expects a specific response format
            $response = "<script>window.parent.CKEDITOR.tools.callFunction({$request->CKEditorFuncNum}, '{$url}', 'Tải lên thành công');</script>";

            @header('Content-Type: text/html; charset=utf-8');
            echo $response;
        }
    }
}

