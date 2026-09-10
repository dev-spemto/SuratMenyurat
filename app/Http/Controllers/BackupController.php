<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    public function create()
    {
        $database = database_path('database.sqlite');

        if (!File::exists($database)) {
            return back()->with(
                'error',
                'Database tidak ditemukan.'
            );
        }

        $folder = storage_path('app/backups');

        if (!File::exists($folder)) {
            File::makeDirectory(
                $folder,
                0755,
                true
            );
        }

        $filename = 'backup_surat_' .
            now()->format('Y-m-d_H-i-s') .
            '.sqlite';

        $destination = $folder . DIRECTORY_SEPARATOR . $filename;

        File::copy(
            $database,
            $destination
        );

        return response()->download(
            $destination,
            $filename
        )->deleteFileAfterSend(false);
    }

    public function restoreForm()
    {
        return view('backup.restore');
    }

    public function restore(Request $request)
    {
        $request->validate([
            'database' => [
                'required',
                'file',
                'extensions:sqlite,db',
                'max:51200',
            ],
        ]);

        $file = $request->file('database');

        $database = database_path('database.sqlite');

        /*
         * Backup database saat ini
         * sebelum melakukan restore.
         */
        $folder = storage_path('app/backups');

        if (!File::exists($folder)) {
            File::makeDirectory(
                $folder,
                0755,
                true
            );
        }

        $safetyBackup =
            $folder .
            DIRECTORY_SEPARATOR .
            'before_restore_' .
            now()->format('Y-m-d_H-i-s') .
            '.sqlite';

        File::copy(
            $database,
            $safetyBackup
        );

        /*
         * Ganti database dengan file backup.
         */
        File::copy(
            $file->getRealPath(),
            $database
        );

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Database berhasil dipulihkan.'
            );
    }
}