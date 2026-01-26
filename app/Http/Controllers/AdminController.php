<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
    

     // AMBIL SEMUA USER KECUALI YANG DIHAPUS (jika ada soft delete)
    $users = \App\Models\User::orderBy('created_at','desc')->get();

    $auditLogs = AdminAuditLog::with('admin')
        ->latest()
        ->limit(50) // batasi agar tidak berat
        ->get();

    return view('admin.dashboard', [
        'users'     => $users,
        'auditLogs'=> $auditLogs, // ⬅️ INI YANG HILANG
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


public function updateUser(Request $request, User $user)
{
    abort_unless(auth()->user()->role === 'admin', 403);

    $data = $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'role'  => 'required|in:admin,student',
    ]);
  // 🔹 SIMPAN DATA SEBELUM
    $before = $user->only(['name','email','role']);

    $user->update($data);
 // 🔹 SIMPAN AUDIT LOG
    AdminAuditLog::create([
        'admin_id'    => auth()->id(),
        'action'      => 'update_user',
        'target_type' => 'User',
        'target_id'   => $user->id,
        'before'      => $before,
        'after'       => $user->only(['name','email','role']),
    ]);
    // 🔥 INI WAJIB
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


public function deleteUser(User $user)
{
    abort_unless(auth()->user()->role === 'admin', 403);

    // proteksi admin tidak hapus diri sendiri
    if ($user->id === auth()->id()) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak bisa menghapus akun sendiri'
        ], 403);
    }

    // 🔹 SIMPAN DATA SEBELUM DIHAPUS
        $before = $user->only(['name','email','role']);

        $user->delete();

        // 🔹 SIMPAN AUDIT LOG
        AdminAuditLog::create([
            'admin_id'    => auth()->id(),
            'action'      => 'delete_user',
            'target_type' => 'User',
            'target_id'   => $user->id,
            'before'      => $before,
            'after'       => null,
        ]);

    return response()->json([
        'success' => true,
        'message' => 'User berhasil dihapus'
    ]);
}

}
