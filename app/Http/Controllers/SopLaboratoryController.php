<?php

namespace App\Http\Controllers;

use App\Models\SopLaboratory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SopLaboratoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laboratories = [
            'Biologi', 'Fisika', 'Bahasa', 
            'Komputer 1', 'Komputer 2', 'Komputer 3', 'Komputer 4'
        ];

        $sopLaboratories = SopLaboratory::all();

        return view('admin.sop-laboratories.index', compact('laboratories', 'sopLaboratories'));
    }

    /**
     * Store or update the SOP for a specific lab.
     */
    public function store(Request $request)
    {
        $request->validate([
            'laboratorium' => ['required', Rule::in(['Biologi', 'Fisika', 'Bahasa', 'Komputer 1', 'Komputer 2', 'Komputer 3', 'Komputer 4'])],
            'file' => ['required', 'file', 'mimes:pdf', 'max:5120'], // Max 5MB PDF
        ]);

        $sop = SopLaboratory::where('laboratorium', $request->laboratorium)->first();

        // Check if there is already an existing file to delete
        if ($sop && $sop->file_path && Storage::disk('public')->exists($sop->file_path)) {
            Storage::disk('public')->delete($sop->file_path);
        }

        // Upload new file
        $fileName = 'sop_' . str_replace(' ', '_', strtolower($request->laboratorium)) . '_' . time() . '.pdf';
        $path = $request->file('file')->storeAs('sops', $fileName, 'public');

        // Create or update record
        SopLaboratory::updateOrCreate(
            ['laboratorium' => $request->laboratorium],
            ['file_path' => $path]
        );

        return redirect()->back()->with('success', 'SOP untuk Lab ' . $request->laboratorium . ' berhasil diunggah.');
    }

    /**
     * Delete the SOP for a specific lab.
     */
    public function destroy(SopLaboratory $sopLaboratory)
    {
        if ($sopLaboratory->file_path && Storage::disk('public')->exists($sopLaboratory->file_path)) {
            Storage::disk('public')->delete($sopLaboratory->file_path);
        }

        $sopLaboratory->delete();

        return redirect()->back()->with('success', 'File SOP berhasil dihapus.');
    }

    /**
     * Get the SOP PDF URL for a specific lab (AJAX endpoint).
     */
    public function getSopUrl(Request $request)
    {
        $lab = $request->query('lab');
        $sop = SopLaboratory::where('laboratorium', $lab)->first();

        if ($sop && $sop->file_path && Storage::disk('public')->exists($sop->file_path)) {
            if ($request->has('json')) {
                $path = Storage::disk('public')->path($sop->file_path);
                return response()->json([
                    'exists' => true,
                    'data' => base64_encode(file_get_contents($path)),
                    'url' => asset('storage/' . $sop->file_path)
                ]);
            }

            return response()->json([
                'exists' => true,
                'url' => asset('storage/' . $sop->file_path)
            ]);
        }

        return response()->json(['exists' => false]);
    }
}

