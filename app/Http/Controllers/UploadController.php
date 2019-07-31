<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Upload;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
    }

    public function index()
    {
        $files = Upload::all();
        return view('pages.upload-index', compact('files'));
    }

    public function store(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $files = $request->file('file');

                foreach ($files as $file) {
                    $fileExtension  = $file->getClientOriginalExtension();
                    $mimeType       = $file->getClientMimeType();
                    $fileSize       = $file->getClientSize();
                    $newName        = uniqid() . '.' . $fileExtension;

                    Storage::disk('dropbox')->putFileAs('public/upload', $file, $newName);
                    $this->dropbox->createSharedLinkWithSettings('public/upload' . $newName);

                    Upload::create([
                        'upload_name'   => $newName,
                        'upload_ext'    => $mimeType,
                        'upload_size'   => $fileSize
                    ]);

                    return redirect('upload');
                }
            }
        } catch (Exception $e) {
            return "Message: {$e->getMessage()}";
        }
    }

    public function show($fileTitle)
    {
        try {
            $link       = $this->dropbox->listSharedLinks('public/upload' . $fileTitle);
            $raw        = expload("?", $link[0]['url']);
            $path       = $raw[0] . '?raw=1';
            $tempPath   = tempnam(sys_get_temp_dir(), $path);
            $copy       = copy($path, $tempPath);

            return response()->file($tempPath);
        } catch (Exception $e) {
            return abort(404);
        }
    }

    public function download($fileTitle)
    {
        try {
            return Storage::disk('dropbox')->download('public/upload' . $fileTitle);
        } catch (Exception $e) {
            return abort(404);
        }
    }

    public function destroy($id)
    {
        try {
            $file   = Upload::find($id);
            Storage::disk('dropbox')->delete('public/upload' . $file->upload_name);
            $file->delete();

            return redirect('upload');
        } catch (Exception $e) {
            return abort(404);
        }
    }
}
