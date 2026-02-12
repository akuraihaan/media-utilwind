<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\QuizAttempt;
use App\Models\LabHistory; // Menggunakan LabHistory sesuai konteks sebelumnya
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // --- 1. MENGHITUNG INSIGHTS (STATISTIK) ---
        
        // A. Data Kuis (Hanya yg sudah selesai)
        $quizAttempts = QuizAttempt::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->get();
        
        $quizzesPassed = $quizAttempts->where('score', '>=', 70)->count();
        $quizTotalScore = $quizAttempts->sum('score');
        
        // B. Data Lab (Hanya yg lulus/selesai)
        // Asumsi menggunakan LabHistory atau LabSession yg status='completed'
        $labsCompleted = LabHistory::where('user_id', $user->id)
            ->where('final_score', '>=', 50)
            ->count();
        $labTotalScore = LabHistory::where('user_id', $user->id)->sum('final_score');

        // C. Kalkulasi Total XP & Rata-rata
        $totalActivities = $quizAttempts->count() + $labsCompleted;
        $grandTotalScore = $quizTotalScore + $labTotalScore;
        
        $stats = [
            'xp' => $grandTotalScore * 10, // Asumsi 1 point = 10 XP
            'avg_score' => $totalActivities > 0 ? round($grandTotalScore / $totalActivities, 1) : 0,
            'labs_done' => $labsCompleted,
            'quizzes_passed' => $quizzesPassed
        ];

        return view('profile.edit', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:100',
            'study_program' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle File Upload
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada (dan bukan default)
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Simpan foto baru
            $path = $request->file('avatar')->store('profile-photos', 'public');
            $user->avatar = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->institution = $request->institution;
        $user->study_program = $request->study_program;
        
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}