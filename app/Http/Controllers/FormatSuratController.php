<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormatSuratController extends Controller
{
    public function index()
    {
        // Membaca file yang ada di folder storage/app/public/format-surat
        $files = Storage::disk('public')->files('format-surat');
        
        return view('format-surat.index', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_format' => 'required|mimes:docx,doc,pdf,xlsx,xls|max:10240',
        ]);

        if ($request->hasFile('file_format')) {
            $file = $request->file('file_format');
            $filename = $file->getClientOriginalName();
            $file->storeAs('public/format-surat', $filename);
        }

        return redirect()->back()->with('success', 'Format surat berhasil diunggah!');
    }

    public function destroy($filename)
    {
        Storage::disk('public')->delete('format-surat/' . $filename);
        return redirect()->back()->with('success', 'Format surat berhasil dihapus!');
    }
}