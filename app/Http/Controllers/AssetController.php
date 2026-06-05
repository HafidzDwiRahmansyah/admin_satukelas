<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index()
    {
        $assets = Asset::where('bucket', 'thumbnails')->orderBy('id', 'desc')->paginate(10);
        return view('assets.index', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048',
        ]);

        $file     = $request->file('file');
        $ext      = $file->getClientOriginalExtension();
        $filename = uniqid() . '.' . $ext;

        // $conn = ftp_connect('storage.satukelas.co', 21, 10);
        // ftp_login($conn, 'ftpuser', 'solusiti2');
        // ftp_pasv($conn, false); // ❌ disable passive, force active

        // file_put_contents(base_path("local_test.txt"), "Hello FTP test");
        // $res = ftp_put($conn, "test_put.txt", base_path("local_test.txt"), FTP_ASCII);

        // dd($res);

        // dd($file, $ext, $filename);

        Storage::disk('storagevps')->put($filename, file_get_contents($file));

        Asset::create([
            'url'       => 'http://storage.satukelas.co/' . $filename,
            'bucket'    => 'thumbnails',
            'file_name' => $filename,
            'extension' => $ext,
        ]);

        return redirect()->route('assets.index')->with('success', 'File uploaded!');
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $request->validate([
            'file_name' => 'required|string|max:255',
        ]);

        // Lokasi file saat ini di server
        $oldFilePath = public_path('storage/' . $asset->file_name);
        $newFilePath = public_path('storage/' . $request->file_name);

        // Cek kalau file lama ada, rename
        if (file_exists($oldFilePath)) {
            rename($oldFilePath, $newFilePath);
        }

        // Update database
        $asset->update([
            'file_name' => $request->file_name,
            'url'       => 'http://storage.satukelas.co/' . $request->file_name,
        ]);

        return redirect()->route('assets.index')->with('success', 'File updated!');
    }

    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);

        Storage::disk('storagevps')->delete($asset->file_name);
        $asset->delete();

        return redirect()->route('assets.index')->with('success', 'File deleted!');
    }
}
