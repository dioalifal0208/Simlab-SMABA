<?php

    namespace App\Http\Controllers;

    use App\Models\User;
    use Illuminate\Http\Request;

    // --- PENAMBAHAN UNTUK FITUR IMPOR ---
    use Maatwebsite\Excel\Facades\Excel;
    use App\Imports\UserImport;
    use Maatwebsite\Excel\Validators\ValidationException;
// ------------------------------------

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,guru,siswa',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * PENAMBAHAN: Menangani file upload dan menjalankan impor user.
     * Dibuat untuk merespons AJAX dan mengembalikan JSON.
     */
    public function handleImport(Request $request)
    {
        // Otorisasi: Pastikan hanya admin yang bisa melakukan ini
        // (Kita asumsikan ini sudah ditangani di routes/web.php)
        
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);

        try {
            // Jalankan impor
            Excel::import(new UserImport, $request->file('file'));
        
        } catch (ValidationException $e) {
            // Tangkap error validasi dari file Excel
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                // Gunakan atribut kustom jika ada
                $attribute = $failure->attribute();
                $errorMessages[] = "Baris " . $failure->row() . ": " . $failure->errors()[0];
            }
            // Kembalikan error sebagai JSON
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Periksa baris berikut:',
                'errors' => $errorMessages
            ], 422);
        
        } catch (\Exception $e) {
            // Tangkap error umum lainnya
            return response()->json([
                'success' => false,
                'message' => 'Terjadi error: ' . $e->getMessage()
            ], 500);
        }

        // Kembalikan pesan sukses sebagai JSON
        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diimpor.'
        ]);
    }
}