<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Akademik - {{ $user->name }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; color: #333; line-height: 1.5; margin: 20px; }
        
        /* Header Laporan */
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #6366f1; padding-bottom: 15px; }
        .header h1 { margin: 0; color: #0f141e; font-size: 24px; text-transform: uppercase; letter-spacing: 1px;}
        .header p { margin: 5px 0 0; color: #64748b; font-size: 14px;}
        
        /* Section Title */
        .section-title { font-size: 14px; font-weight: bold; background-color: #f1f5f9; padding: 8px 12px; border-left: 4px solid #6366f1; margin-top: 30px; margin-bottom: 10px; }
        
        /* Tabel Standard */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 10px; text-align: left; vertical-align: middle; }
        th { background-color: #f8fafc; font-weight: bold; color: #475569; }
        
        /* Tabel Profil Khusus */
        .profile-table th { width: 25%; background-color: #f8fafc; }
        
        /* Badges */
        .badge-pass { color: #059669; font-weight: bold; }
        .badge-fail { color: #dc2626; font-weight: bold; }
        .badge-warn { color: #d97706; font-weight: bold; }
        
        /* Progress Bar Sederhana */
        .progress-container { width: 100%; background-color: #e2e8f0; border-radius: 4px; overflow: hidden; margin-top: 5px;}
        .progress-bar { height: 12px; background-color: #6366f1; text-align: center; color: white; font-size: 9px; line-height: 12px; font-weight: bold; }

        /* Tabel Tracker Khusus */
        .tracker-chapter { background-color: #e2e8f0; font-weight: bold; font-size: 13px; color: #1e293b; text-transform: uppercase; }
        .tracker-topic td { padding-left: 25px; color: #475569; }
        .tracker-eval td { padding-left: 25px; font-weight: bold; background-color: #f8fafc; }
        
        /* Hilangkan elemen saat di-print */
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
        
        .print-btn { background: #6366f1; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 5px; font-weight: bold; margin-bottom: 20px;}
    </style>
</head>
<body>
    
    <div class="no-print" style="text-align: center;">
        <button class="print-btn" onclick="window.print()">Cetak Dokumen / Simpan ke PDF</button>
    </div>

    <div class="header">
        <h1>Utilwind Academy</h1>
        <p>Transkrip Progres & Evaluasi Siswa</p>
    </div>

    <div class="section-title">A. Ringkasan Profil Akademik</div>
    <table class="profile-table">
        <tr>
            <th>Nama Lengkap</th>
            <td style="font-size: 14px; font-weight: bold; color: #0f141e;">{{ $user->name }}</td>
            <th>Global Progress</th>
            <td rowspan="2" style="vertical-align: top; text-align: center;">
                <div style="font-size: 24px; font-weight: bold; color: #6366f1;">{{ $globalProgress }}%</div>
                <div class="progress-container">
                    <div class="progress-bar" style="width: {{ $globalProgress }}%;"></div>
                </div>
            </td>
        </tr>
        <tr>
            <th>Alamat Email</th>
            <td>{{ $user->email }}</td>
            <th style="border-bottom: none;"></th>
        </tr>
        <tr>
            <th>Grup Kelas</th>
            <td>{{ $user->class_group ?? 'Tidak ada data' }}</td>
            <th>Materi Dibaca</th>
            <td><strong>{{ count($completedLessonIds) }}</strong> / 65 Slide</td>
        </tr>
        <tr>
            <th>Asal Institusi</th>
            <td>{{ $user->institution ?? 'Tidak ada data' }}</td>
            <th>Praktik Selesai</th>
            <td><strong>{{ count($passedLabIds) }}</strong> / 4 Modul</td>
        </tr>
        <tr>
            <th>Tanggal Terdaftar</th>
            <td>{{ $user->created_at->format('d F Y') }}</td>
            <th>Kuis Lulus</th>
            <td><strong>{{ count(array_filter($quizScoresMap, fn($s) => $s >= 70)) }}</strong> / 4 Evaluasi</td>
        </tr>
    </table>

    <div style="page-break-inside: avoid;">
        <div class="section-title">B. Rincian Curriculum Tracker</div>
        
        @php
            // Struktur Kurikulum (Sama seperti di UI)
            $curriculumMap = [
                [
                    'id' => 1, 'number' => '01', 'title' => 'PENDAHULUAN',
                    'lab_id' => 1, 'lab_name' => 'Setup Environment', 'quiz_key' => '1',
                    'topics' => [
                        ['name' => '1.1 Konsep HTML & CSS', 'ids' => range(1, 6)],
                        ['name' => '1.2 Konsep Dasar Tailwind', 'ids' => range(7, 11)],
                        ['name' => '1.3 Latar Belakang & Struktur', 'ids' => range(12, 15)],
                        ['name' => '1.4 Implementasi pada HTML', 'ids' => range(16, 19)],
                        ['name' => '1.5 Keunggulan & Utilitas', 'ids' => range(20, 23)],
                        ['name' => '1.6 Instalasi & Konfigurasi', 'ids' => range(24, 28)],
                    ]
                ],
                [
                    'id' => 2, 'number' => '02', 'title' => 'LAYOUTING',
                    'lab_id' => 2, 'lab_name' => 'Building Grid Layout', 'quiz_key' => '2',
                    'topics' => [
                        ['name' => '2.1 Flexbox Architecture', 'ids' => range(29, 33)],
                        ['name' => '2.2 Grid System Mastery', 'ids' => range(34, 40)],
                        ['name' => '2.3 Layout Management', 'ids' => range(41, 45)],
                    ]
                ],
                [
                    'id' => 3, 'number' => '03', 'title' => 'STYLING',
                    'lab_id' => 3, 'lab_name' => 'Styling Components', 'quiz_key' => '3',
                    'topics' => [
                        ['name' => '3.1 Tipografi & Font', 'ids' => range(46, 51)],
                        ['name' => '3.2 Backgrounds', 'ids' => range(52, 55)],
                        ['name' => '3.3 Borders & Rings', 'ids' => range(56, 59)],
                        ['name' => '3.4 Effects & Filters', 'ids' => range(60, 65)],
                    ]
                ]
            ];
            $allChaptersPassed = true;
        @endphp

        <table>
            <thead>
                <tr>
                    <th>Komponen Kurikulum</th>
                    <th style="text-align: center; width: 15%">Status</th>
                    <th style="text-align: center; width: 25%">Keterangan Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach($curriculumMap as $chapter)
                    <tr class="tracker-chapter">
                        <td colspan="3">BAB {{ $chapter['number'] }}: {{ $chapter['title'] }}</td>
                    </tr>
                    
                    @foreach($chapter['topics'] as $topic)
                        @php 
                            $missingIds = array_diff($topic['ids'], $completedLessonIds);
                            $isTopicDone = empty($missingIds);
                            $partial = count($topic['ids']) - count($missingIds);
                            $total = count($topic['ids']);
                        @endphp
                        <tr class="tracker-topic">
                            <td>Membaca: {{ $topic['name'] }}</td>
                            <td style="text-align: center;">
                                @if($isTopicDone) <span class="badge-pass">Selesai</span>
                                @elseif($partial > 0) <span class="badge-warn">In Progress</span>
                                @else <span style="color:#94a3b8">Belum</span> @endif
                            </td>
                            <td style="text-align: center;">{{ $partial }} / {{ $total }} Slide Dibaca</td>
                        </tr>
                    @endforeach

                    @php
                        $labDone = in_array($chapter['lab_id'], $passedLabIds);
                        $quizScore = $quizScoresMap['quiz_' . $chapter['quiz_key']] ?? null;
                        $quizPass = ($quizScore !== null && $quizScore >= 70);
                        if(!$quizPass) $allChaptersPassed = false;
                    @endphp
                    
                    <tr class="tracker-eval">
                        <td>⚡ Praktikum: {{ $chapter['lab_name'] }}</td>
                        <td style="text-align: center;">
                            {!! $labDone ? '<span class="badge-pass">LULUS</span>' : '<span class="badge-fail">PENDING</span>' !!}
                        </td>
                        <td style="text-align: center;">Wajib Lulus (100%)</td>
                    </tr>
                    <tr class="tracker-eval">
                        <td>📝 Kuis: Evaluasi Bab {{ $chapter['number'] }}</td>
                        <td style="text-align: center;">
                            {!! $quizPass ? '<span class="badge-pass">LULUS</span>' : ($quizScore !== null ? '<span class="badge-fail">GAGAL</span>' : '<span style="color:#94a3b8">BELUM</span>') !!}
                        </td>
                        <td style="text-align: center;">
                            {{ $quizScore !== null ? 'Skor Tertinggi: ' . $quizScore : 'Syarat Kelulusan: 70' }}
                        </td>
                    </tr>
                @endforeach

                @php
                    $isCapstoneDone = in_array(4, $passedLabIds);
                    $finalQuizScore = $quizScoresMap['quiz_99'] ?? null;
                    $isFinalDone = ($finalQuizScore !== null && $finalQuizScore >= 70);
                @endphp
                <tr class="tracker-chapter">
                    <td colspan="3">FINAL PHASE (TAHAP AKHIR)</td>
                </tr>
                <tr class="tracker-eval">
                    <td>🏆 Capstone Project: DevStudio Landing Page</td>
                    <td style="text-align: center;">
                        {!! $isCapstoneDone ? '<span class="badge-pass">LULUS</span>' : '<span class="badge-fail">PENDING</span>' !!}
                    </td>
                    <td style="text-align: center;">Proyek Praktik Akhir</td>
                </tr>
                <tr class="tracker-eval">
                    <td>🎓 Ujian Teori Komprehensif</td>
                    <td style="text-align: center;">
                        {!! $isFinalDone ? '<span class="badge-pass">LULUS</span>' : ($finalQuizScore !== null ? '<span class="badge-fail">GAGAL</span>' : '<span style="color:#94a3b8">BELUM</span>') !!}
                    </td>
                    <td style="text-align: center;">
                        {{ $finalQuizScore !== null ? 'Skor Akhir: ' . $finalQuizScore : 'Syarat Kelulusan: 70' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="page-break-inside: avoid;">
        <div class="section-title">C. Log Aktivitas Laboratorium (Coding)</div>
        <table>
            <thead>
                <tr>
                    <th>Nama Modul</th>
                    <th style="text-align: center; width: 15%">Status</th>
                    <th style="text-align: center; width: 10%">Skor</th>
                    <th style="text-align: center; width: 25%">Waktu Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($labHistories as $lab)
                <tr>
                    <td>{{ $lab->title ?? 'Lab #'.$lab->lab_id }}</td>
                    <td style="text-align: center">
                        <span class="{{ $lab->status == 'passed' ? 'badge-pass' : 'badge-fail' }}">
                            {{ strtoupper($lab->status) }}
                        </span>
                    </td>
                    <td style="text-align: center">{{ $lab->final_score }}</td>
                    <td style="text-align: center">{{ \Carbon\Carbon::parse($lab->created_at)->format('d M Y, H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #94a3b8; font-style: italic;">Belum ada riwayat pengerjaan modul.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="page-break-inside: avoid;">
        <div class="section-title">D. Log Evaluasi Kuis (Pilihan Ganda)</div>
        <table>
            <thead>
                <tr>
                    <th>Nama Evaluasi</th>
                    <th style="text-align: center; width: 15%">Status</th>
                    <th style="text-align: center; width: 10%">Skor</th>
                    <th style="text-align: center; width: 25%">Waktu Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizAttempts as $quiz)
                <tr>
                    <td>{{ $quiz->chapter_id == 99 ? 'Evaluasi Akhir (Final)' : 'Evaluasi Bab ' . $quiz->chapter_id }}</td>
                    <td style="text-align: center">
                        <span class="{{ $quiz->score >= 70 ? 'badge-pass' : 'badge-fail' }}">
                            {{ $quiz->score >= 70 ? 'LULUS' : 'GAGAL' }}
                        </span>
                    </td>
                    <td style="text-align: center">{{ $quiz->score }}</td>
                    <td style="text-align: center">{{ \Carbon\Carbon::parse($quiz->created_at)->format('d M Y, H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: #94a3b8; font-style: italic;">Belum ada riwayat pengerjaan kuis.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="text-align: right; margin-top: 40px; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px;">
        Dokumen dicetak secara otomatis dari sistem Utilwind Academy pada: {{ now()->format('d F Y, H:i:s') }}
    </div>
    
    <script>
        // Otomatis membuka dialog print saat halaman dimuat
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>