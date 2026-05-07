<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\QuizAttempt;
use App\Models\LabHistory;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        // Ekstraksi ID untuk memastikan query builder aman dan efisien di hosting
        $userId = Auth::id();

        // --- 1. MENGHITUNG INSIGHTS (STATISTIK) ---
        
        // A. Data Kuis (Hanya yg sudah selesai)
        $quizAttempts = QuizAttempt::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->get();
        
        $quizzesPassed = $quizAttempts->where('score', '>=', 70)->count();
        $quizTotalScore = $quizAttempts->sum('score');
        
        // B. Data Lab (Hanya yg lulus/selesai)
        $labsCompleted = LabHistory::where('user_id', $userId)
            ->where('final_score', '>=', 50)
            ->count();
        $labTotalScore = LabHistory::where('user_id', $userId)->sum('final_score');

        // C. Kalkulasi Total XP & Rata-rata
        $totalActivities = $quizAttempts->count() + $labsCompleted;
        $grandTotalScore = $quizTotalScore + $labTotalScore;
        
        $stats = [
            'xp' => $grandTotalScore * 10,
            'avg_score' => $totalActivities > 0 ? round($grandTotalScore / $totalActivities, 1) : 0,
            'labs_done' => $labsCompleted,
            'quizzes_passed' => $quizzesPassed
        ];

        // Memastikan $user adalah instance Model utuh (menghindari error property pada interface Authenticatable)
        $user = User::find($userId);

        return view('profile.edit', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $userId = Auth::id();
        $user = User::find($userId);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'phone' => 'nullable|string|max:20',
            'institution' => 'nullable|string|max:100',
            'study_program' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle File Upload secara spesifik menggunakan disk 'public'
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
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
        // Memanfaatkan rules 'current_password' bawaan Laravel
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        User::find(Auth::id())->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}