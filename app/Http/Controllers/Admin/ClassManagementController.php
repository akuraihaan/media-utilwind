<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassManagementController extends Controller
{
    public function index()
    {
        $classes = ClassGroup::withCount('students')
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'major' => $class->major,
                    'token' => $class->token,
                    'is_active' => $class->is_active,
                    'students_count' => (int) $class->students_count,
                ];
            })
            ->toArray();

        $totalClasses = count($classes);
        $totalActive = collect($classes)->where('is_active', 1)->count();
        $totalStudents = collect($classes)->sum('students_count');

        return view('admin.class_management', compact('classes', 'totalClasses', 'totalActive', 'totalStudents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:class_groups,name|max:255',
            'major' => 'nullable|string|max:255',
        ]);

        ClassGroup::create([
            'name' => $request->name,
            'major' => $request->major,
            'token' => $this->generateUniqueToken(),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan beserta Token barunya!');
    }

    public function update(Request $request, $id)
    {
        $class = ClassGroup::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:class_groups,name,'.$id,
            'major' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        // Begitu kode ini dieksekusi, Model ClassGroup akan otomatis
        // menjalankan fungsi booted() di atas dan meng-update tabel users!
        $class->update([
            'name' => trim($request->name),
            'major' => $request->major,
            'is_active' => $request->is_active,
        ]);

        return redirect()->back()->with('success', 'Data kelas berhasil diperbarui dan disinkronkan!');
    }

    public function destroy($id)
    {
        $class = ClassGroup::findOrFail($id);
        
        // Begitu kode delete() dieksekusi, user yang ada di kelas ini 
        // otomatis akan di-NULL-kan class_group-nya
        $class->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus!');
    }

    public function regenerateToken($id)
    {
        $class = ClassGroup::findOrFail($id);
        $class->update([
            'token' => $this->generateUniqueToken()
        ]);

        return redirect()->back()->with('success', 'Token keamanan berhasil diperbarui!');
    }

    // Private helper untuk generate token 6 karakter alphanumeric kapital
    private function generateUniqueToken()
    {
        do {
            $token = strtoupper(Str::random(6));
        } while (ClassGroup::where('token', $token)->exists());
        
        return $token;
    }
}
