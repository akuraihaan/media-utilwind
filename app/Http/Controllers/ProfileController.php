<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File; // Facade File untuk manajemen folder dan file
use App\Models\QuizAttempt;
use App\Models\LabHistory;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        $userId = Auth::id();
        $user = User::find($userId);

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

        if ($request->hasFile('avatar')) {
            
            // 1. Hapus foto lama jika ada
            if (!empty($user->avatar) && File::exists(public_path($user->avatar))) {
                File::delete(public_path($user->avatar));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('profile-photos');

            // 2. CEK DAN BUAT FOLDER OTOMATIS
            // Jika folder 'profile-photos' belum ada di dalam folder 'public', Laravel akan membuatnya dengan permission 755
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }
            
            // 3. Pindahkan file
            $file->move($destinationPath, $filename);
            
            // 4. Simpan ke database
            $user->avatar = 'profile-photos/' . $filename;
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
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        User::find(Auth::id())->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}