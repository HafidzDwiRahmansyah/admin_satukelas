<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $visibleTemplate = function ($query) {
            $query->whereNull('course_id')->orWhereHas('course');
        };
        $query = CertificateTemplate::with(['course', 'asset'])
            ->where($visibleTemplate)
            ->latest('id');

        if ($request->filled('certificate_type') && $request->certificate_type !== 'all') {
            $query->where('certificate_type', $request->certificate_type);
        }

        if ($request->filled('template_status')) {
            if ($request->template_status === 'complete') {
                $query->whereNotNull('course_id')
                    ->whereNotNull('asset_id')
                    ->whereHas('course', function ($q) {
                        $q->whereNotNull('title')->where('title', '<>', '');
                    })
                    ->whereHas('asset', function ($q) {
                        $q->whereNotNull('file_name')->where('file_name', '<>', '');
                    });
            }

            if ($request->template_status === 'incomplete') {
                $query->where(function ($q) {
                    $q->whereNull('course_id')
                        ->orWhereNull('asset_id')
                        ->orWhereHas('course', function ($courseQuery) {
                            $courseQuery->whereNull('title')->orWhere('title', '');
                        })
                        ->orWhereHas('asset', function ($assetQuery) {
                            $assetQuery->whereNull('file_name')->orWhere('file_name', '');
                        });
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_type', 'ILIKE', "%{$search}%")
                    ->orWhereHas('course', function ($courseQuery) use ($search) {
                        $courseQuery->where('title', 'ILIKE', "%{$search}%");
                    })
                    ->orWhereHas('asset', function ($assetQuery) use ($search) {
                        $assetQuery->where('file_name', 'ILIKE', "%{$search}%")
                            ->orWhere('url', 'ILIKE', "%{$search}%");
                    });
            });
        }

        $templates = $query
            ->paginate(24)
            ->appends($request->only('certificate_type', 'template_status', 'search'));

        $totalTemplateCount = (clone CertificateTemplate::query())->where($visibleTemplate)->count();
        $participationTemplateCount = (clone CertificateTemplate::query())->where($visibleTemplate)->where('certificate_type', 'Partisipasi')->count();
        $competenceTemplateCount = (clone CertificateTemplate::query())->where($visibleTemplate)->where('certificate_type', 'Kompetensi')->count();
        $completeTemplateCount = (clone CertificateTemplate::query())->where($visibleTemplate)->whereNotNull('course_id')
            ->whereNotNull('asset_id')
            ->whereHas('course', function ($q) {
                $q->whereNotNull('title')->where('title', '<>', '');
            })
            ->whereHas('asset', function ($q) {
                $q->whereNotNull('file_name')->where('file_name', '<>', '');
            })
            ->count();
        $incompleteTemplateCount = $totalTemplateCount - $completeTemplateCount;

        return view('assets.index', compact(
            'templates',
            'totalTemplateCount',
            'participationTemplateCount',
            'competenceTemplateCount',
            'completeTemplateCount',
            'incompleteTemplateCount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,pdf,ppt,pptx|max:10240',
            'bucket' => 'required|in:thumbnails,certificate_templates,lessons',
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
            'bucket'    => $request->bucket,
            'file_name' => $filename,
            'extension' => $ext,
        ]);

        return redirect()->route('assets.index')->with('success', 'File uploaded!');
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::findOrFail($id);

        $request->validate([
            'bucket' => 'required|in:thumbnails,certificate_templates,lessons,generated_certificate,certificate_template',
        ]);

        $asset->update(['bucket' => $request->bucket]);

        return redirect()->route('assets.index')->with('success', 'File updated!');
    }

    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);

        if ($asset->certificates()->exists()) {
            return redirect()->route('assets.index')
                ->with('error', 'Asset tidak dapat dihapus karena masih digunakan oleh certificate.');
        }

        if ($asset->certificateTemplates()->exists()) {
            return redirect()->route('assets.index')
                ->with('error', 'Asset tidak dapat dihapus karena masih digunakan oleh certificate_template.');
        }

        Storage::disk('storagevps')->delete($asset->file_name);
        $asset->delete();

        return redirect()->route('assets.index')->with('success', 'File deleted!');
    }
}
