<?php

namespace App\Http\Controllers;

use App\Models\CareerQuestion;
use App\Models\CareerResult;
use App\Services\RiasecService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TesMinatController extends Controller
{
    public function __construct(
        protected RiasecService $riasecService
    ) {}

    /**
     * Halaman pengantar Tes Minat Karier (RIASEC + Career Anchors).
     */
    public function index()
    {
        $user = Auth::user();
        $hasTakenTest = $user->is_tes_selesai;
        $careerResult = $user->careerResult;
        $riasecCount = CareerQuestion::active()->riasecSection()->count();
        $anchorCount = CareerQuestion::active()->anchorSection()->count();
        $totalQuestions = $riasecCount + $anchorCount;

        return view('siswa.tes.index', compact('hasTakenTest', 'careerResult', 'totalQuestions', 'riasecCount', 'anchorCount'));
    }

    /**
     * Halaman pengisian kuesioner pertanyaan tes minat (Sesi 1 & Sesi 2).
     */
    public function mulai()
    {
        $user = Auth::user();

        if ($user->is_tes_selesai) {
            return redirect()->route('tes.hasil')->with('info', 'Kamu telah menyelesaikan tes minat karier. Untuk mengulang atau mereset hasil tes, silakan hubungi Guru BK.');
        }

        $riasecQuestions = CareerQuestion::active()->riasecSection()->orderBy('order_num')->get();
        $anchorQuestions = CareerQuestion::active()->anchorSection()->orderBy('order_num')->get();
        $questions = CareerQuestion::active()->orderBy('order_num')->get();

        // Ambil jawaban sebelumnya jika ada (untuk default select)
        $existingAnswers = $user->careerAnswers()->pluck('score', 'question_id')->toArray();

        return view('siswa.tes.mulai', compact('questions', 'riasecQuestions', 'anchorQuestions', 'existingAnswers'));
    }

    /**
     * Proses penyimpanan jawaban dan kalkulasi skor RIASEC & Career Anchors.
     */
    public function simpan(Request $request)
    {
        $user = Auth::user();
        $questions = CareerQuestion::active()->get();

        // Validasi: seluruh pertanyaan aktif harus memiliki jawaban skor 1-5
        $rules = [];
        $messages = [];
        foreach ($questions as $q) {
            $rules["answers.{$q->id}"] = 'required|integer|min:1|max:5';
            $messages["answers.{$q->id}.required"] = "Pertanyaan nomor {$q->order_num} belum dijawab.";
        }

        $request->validate($rules, $messages);

        $answers = $request->input('answers', []);

        // Proses kalkulasi dan simpan via service
        $result = $this->riasecService->processAndSaveAnswers($user, $answers);

        return redirect()->route('tes.hasil')->with('success', 'Hasil Tes Minat Karier & Jangkar Karier kamu berhasil dianalisis!');
    }

    /**
     * Halaman visualisasi hasil tes (Radar Chart, Holland Code, Career Anchors, Penjelasan Tipe).
     */
    public function hasil()
    {
        $user = Auth::user();
        $careerResult = $user->careerResult;

        if (!$careerResult) {
            return redirect()->route('tes.index')->with('info', 'Silakan selesaikan tes minat karier terlebih dahulu.');
        }

        $recommendations = $this->riasecService->getRecommendationsForCode($careerResult->holland_code, $user);

        // Decode collaboration narrative if available
        $collaborationReport = null;
        if ($careerResult->collaboration_narrative) {
            $collaborationReport = json_decode($careerResult->collaboration_narrative, true);
        }
        if (!$collaborationReport) {
            $detectedMajor = $this->riasecService->detectStudentMajor($user);
            $scoresMap = $careerResult->scores_map;
            $anchorScoresMap = $careerResult->anchor_scores_map;
            $rScores = array_combine(array_keys($scoresMap), array_column($scoresMap, 'score'));
            $aScores = array_combine(array_keys($anchorScoresMap), array_column($anchorScoresMap, 'score'));
            $collaborationReport = $this->riasecService->synthesizeCollaborationReport($user, $rScores, $aScores, $detectedMajor);
        }

        return view('siswa.tes.hasil', compact('careerResult', 'recommendations', 'user', 'collaborationReport'));
    }

    /**
     * Halaman detail eksplorasi rekomendasi jurusan, profesi, dan bidang usaha.
     */
    public function rekomendasi()
    {
        $user = Auth::user();
        $careerResult = $user->careerResult;

        if (!$careerResult) {
            return redirect()->route('tes.index')->with('info', 'Silakan selesaikan tes minat karier untuk melihat rekomendasi yang disesuaikan.');
        }

        $recommendations = $this->riasecService->getRecommendationsForCode($careerResult->holland_code, $user);

        $collaborationReport = null;
        if ($careerResult->collaboration_narrative) {
            $collaborationReport = json_decode($careerResult->collaboration_narrative, true);
        }
        if (!$collaborationReport) {
            $detectedMajor = $this->riasecService->detectStudentMajor($user);
            $scoresMap = $careerResult->scores_map;
            $anchorScoresMap = $careerResult->anchor_scores_map;
            $rScores = array_combine(array_keys($scoresMap), array_column($scoresMap, 'score'));
            $aScores = array_combine(array_keys($anchorScoresMap), array_column($anchorScoresMap, 'score'));
            $collaborationReport = $this->riasecService->synthesizeCollaborationReport($user, $rScores, $aScores, $detectedMajor);
        }

        return view('siswa.tes.rekomendasi', compact('careerResult', 'recommendations', 'user', 'collaborationReport'));
    }
}
