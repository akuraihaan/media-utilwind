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
        $userId = Auth::id();
        $user = User::find($userId);

        $quizAttempts = QuizAttempt::where('user_id', $userId)->whereNotNull('completed_at')->get();
        $quizzesPassed = $quizAttempts->where('score', '>=', 70)->count();
        $quizTotalScore = $quizAttempts->sum('score');
        
        $labsCompleted = LabHistory::where('user_id', $userId)->where('final_score', '>=', 50)->count();
        $labTotalScore = LabHistory::where('user_id', $userId)->sum('final_score');

        $totalActivities = $quizAttempts->count() + $labsCompleted;
        
        $stats = [
            'xp' => ($quizTotalScore + $labTotalScore) * 10,
            'avg_score' => $totalActivities > 0 ? round(($quizTotalScore + $labTotalScore) / $totalActivities, 1) : 0,
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
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan foto baru (Laravel akan otomatis membuat folder 'public/uploads/profile-photos' di hosting)
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
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        User::find(Auth::id())->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}