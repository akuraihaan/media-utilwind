<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\AdminAuditLog;

class AdminController extends Controller
{
    /**
     * DASHBOARD ADMIN
     */
    public function dashboard()
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        
        $users = \App\Models\User::orderBy('created_at','desc')->get();

        foreach ($users as $user) {
            if (!empty($user->avatar)) {
                if (Str::startsWith($user->avatar, ['http://', 'https://'])) {
                    $user->avatar_url = $user->avatar;
                } else {
                    $user->avatar_url = asset('uploads/' . $user->avatar) . '?v=' . time(); 
                }
            } else {
                $user->avatar_url = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=06b6d4&color=fff&size=256';
            }
        }

        $auditLogs = AdminAuditLog::with('admin')
            ->latest()
            ->limit(50)
            ->get();

        return view('admin.dashboard', [
            'users'     => $users,
            'auditLogs' => $auditLogs, 
        ]);
    }

    /**
     * BUAT QUIZ BARU
     */
    public function storeQuiz(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Quiz::create([
            'title' => $request->title,
            'slug'  => Str::slug($request->title),
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Quiz berhasil dibuat');
    }

    /**
     * TAMBAH PERTANYAAN QUIZ
     */
    public function storeQuestion(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_index' => 'required|integer',
        ]);

        QuizQuestion::create([
            'quiz_id' => $request->quiz_id,
            'question' => $request->question,
            'options' => json_encode($request->options),
            'correct_index' => $request->correct_index,
        ]);

        return redirect()->back()->with('success', 'Pertanyaan ditambahkan');
    }


    /**
     * UPDATE DATA USER & AVATAR OLEH ADMIN
     */
    public function updateUser(Request $request, User $user)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'class_group'   => 'nullable|string',
            'phone'         => 'nullable|string',
            'institution'   => 'nullable|string',
            'study_program' => 'nullable|string',
            'password'      => 'nullable|string|min:8',
        ]);

        $before = $user->toArray();

        // 1. UPLOAD GAMBAR (KE FOLDER UPLOADS)
        if ($request->hasFile('avatar')) {
            // Hapus gambar lama jika ada
            if (!empty($user->avatar) && File::exists(public_path('uploads/' . $user->avatar))) {
                File::delete(public_path('uploads/' . $user->avatar));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/profile-photos');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            
            $file->move($destinationPath, $filename);
            
            // Simpan secara manual
            $user->avatar = 'profile-photos/' . $filename;
        }

        // 2. SIMPAN SEMUA FIELD SECARA MANUAL
        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->has('class_group')) {
            $user->class_group = $request->class_group;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->has('institution')) {
            $user->institution = $request->institution;
        }
        if ($request->has('study_program')) {
            $user->study_program = $request->study_program;
        }
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Eksekusi Simpan ke Database (Mengatasi masalah tidak berubah)
        $user->save();
        
        AdminAuditLog::create([
            'admin_id'    => auth()->id(),
            'action'      => 'update_user',
            'target_type' => 'User',
            'target_id'   => $user->id,
            'before'      => $before,
            'after'       => $user->toArray(),
        ]);
        
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'user' => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ]
            ]);
        }

        // Redirect kembali agar halaman me-refresh dan memuat foto terbaru
        return back()->with('success', 'Data siswa berhasil diperbarui!');
    }


    /**
     * HAPUS USER
     */
    public function deleteUser(User $user)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menghapus akun sendiri'
            ], 403);
        }

        $before = $user->only(['name','email','role']);
        
        // Bersihkan file foto profil saat user dihapus
        if (!empty($user->avatar) && File::exists(public_path('uploads/' . $user->avatar))) {
            File::delete(public_path('uploads/' . $user->avatar));
        }

        $user->delete();

        AdminAuditLog::create([
            'admin_id'    => auth()->id(),
            'action'      => 'delete_user',
            'target_type' => 'User',
            'target_id'   => $user->id,
            'before'      => $before,
            'after'       => null,
        ]);

        if (!request()->expectsJson()) {
            return redirect()->route('admin.dashboard')->with('success', 'Siswa berhasil dihapus!');
        }

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}