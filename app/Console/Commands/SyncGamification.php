<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserLessonProgress;
use App\Models\LabHistory;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\DB;

class SyncGamification extends Command
{
    protected $signature = 'gamification:sync';
    protected $description = 'Sinkronisasi XP dan Evaluasi Syarat Badges Tailwind CSS';

    public function handle()
    {
        $users = User::where('role', 'student')->get();
        $allBadges = DB::table('badges')->get();

        if ($allBadges->isEmpty()) {
            $this->error("Tabel badges kosong! Tolong jalankan seeder: php artisan db:seed --class=BadgeSeeder");
            return;
        }

        $this->info("Mengevaluasi rekam jejak untuk {$users->count()} siswa...");

        foreach ($users as $user) {
            // ==========================================
            // 1. REKALKULASI XP (EXPERIENCE POINTS)
            // ==========================================
            $lessonXP = UserLessonProgress::where('user_id', $user->id)->where('completed', true)->count() * 10;
            
            $passedLabsCount = LabHistory::where('user_id', $user->id)
                            ->where('final_score', '>=', 70)
                            ->distinct('lab_id')
                            ->count('lab_id');
            $labXP = $passedLabsCount * 50;

            $quizXP = QuizAttempt::where('user_id', $user->id)->where('score', '>=', 70)->sum('score');
            
            $totalXP = $lessonXP + $labXP + $quizXP;
            $user->xp = $totalXP;
            $user->save(); // Simpan XP ke database

            // ==========================================
            // 2. EVALUASI SYARAT BADGE SANGAT SPESIFIK
            // ==========================================
            $awardedBadgeIds = [];

            // Kumpulkan histori belajar user ini
            $passedQuizChap1 = QuizAttempt::where('user_id', $user->id)->where('chapter_id', 1)->where('score', '>=', 70)->exists();
            $passedQuizChap2 = QuizAttempt::where('user_id', $user->id)->where('chapter_id', 2)->where('score', '>=', 70)->exists();
            $passedQuizChap3 = QuizAttempt::where('user_id', $user->id)->where('chapter_id', 3)->where('score', '>=', 70)->exists();
            $hasPerfectQuiz  = QuizAttempt::where('user_id', $user->id)->where('score', 100)->exists();

            foreach ($allBadges as $badge) {
                $shouldAward = false;

                // LOGIKA PEMBERIAN BADGE
                switch ($badge->name) {
                    case 'First Render':
                        $shouldAward = $passedQuizChap1; 
                        break;
                    case 'Sandbox Explorer':
                        $shouldAward = $passedLabsCount >= 1; 
                        break;
                    case 'Flexbox Master':
                        $shouldAward = $passedQuizChap2; 
                        break;
                    case 'Typography Artisan':
                        $shouldAward = $passedQuizChap3; 
                        break;
                    case 'Grid Architect':
                        $shouldAward = $passedLabsCount >= 2; 
                        break;
                    case 'Responsive Ninja':
                        $shouldAward = $totalXP >= 1000; 
                        break;
                    case 'Arbitrary Hacker':
                        $shouldAward = $passedLabsCount >= 3; 
                        break;
                    case 'Pixel Perfect':
                        $shouldAward = $hasPerfectQuiz; 
                        break;
                    case 'Consistency King':
                        $shouldAward = $totalXP >= 2500; 
                        break;
                    case 'Tailwind Architect':
                    case 'Tailwind Legend':
                        // Syarat Ultimate: Lulus bab 1,2,3 + Lulus 3 Lab + XP 4000
                        $shouldAward = ($passedQuizChap1 && $passedQuizChap2 && $passedQuizChap3 && $passedLabsCount >= 3 && $totalXP >= 4000); 
                        break;
                }

                if ($shouldAward) {
                    $awardedBadgeIds[] = $badge->id;
                }
            }

            // ==========================================
            // 3. MASUKKAN BADGE KE PIVOT TABLE
            // ==========================================
            $existingBadges = DB::table('user_badges')->where('user_id', $user->id)->pluck('badge_id')->toArray();
            
            foreach ($awardedBadgeIds as $bId) {
                // Jika belum punya badge ini, berikan!
                if (!in_array($bId, $existingBadges)) {
                    DB::table('user_badges')->insert([
                        'user_id' => $user->id,
                        'badge_id' => $bId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $this->line("Data sinkron -> [{$user->name}] | XP: {$totalXP} | Lencana Dibuka: " . count($awardedBadgeIds));
        }

        $this->info("\n✅ Selesai! Semua data Gamifikasi telah di-sync secara retroaktif.");
    }
}