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
            'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,guru',
            'laboratorium' => 'nullable|in:Biologi,Fisika,Bahasa',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menyimpan data pengguna baru (Fitur Tambah User Manual).
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,guru',
            // Laboratorium wajib jika role = guru, tapi opsional jika admin (bisa null)
            'laboratorium' => 'nullable|in:Biologi,Fisika,Bahasa|required_if:role,guru',
        ], [
            'laboratorium.required_if' => 'Laboratorium wajib dipilih untuk peran Guru.',
        ]);

        // Buat user baru (Password otomatis di-hash oleh model cast atau manual jika perlu)
        // Di Laravel model User biasanya sudah ada cast 'password' => 'hashed', 
        // tapi untuk aman kita hash manual di sini atau andalkan Eloquent mutator.
        // Cek User.php -> cast: 'password' => 'hashed' ada.
        
        // Namun, `create` method bypasses casts for setting attributes? No, casts work on set.
        // Tapi best practice seringkali hash manual di controller atau pakai mutator.
        // Kita pakai Hash::make eksplisit agar aman.
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role' => $validated['role'],
            'laboratorium' => $validated['laboratorium'] ?? null,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
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

    /**
     * Menghapus user dari database.
     */
    public function destroy(User $user)
    {
        // Jaga agar admin tidak bisa menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->withErrors(['message' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', "Pengguna \"{$user->name}\" berhasil dihapus.");
    }
}
