@extends('layouts.app')

@section('title', 'Mengerjakan Tes Minat Karier')

@push('styles')
<style>
    /* Question Card Styling */
    .question-card {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: var(--radius-lg);
        padding: 22px;
        margin-bottom: 18px;
        transition: all 0.25s ease;
    }

    .question-card.answered {
        border-color: #93c5fd;
        background: #f8fafc;
    }

    .question-card.has-error {
        border-color: #f87171 !important;
        background: #fef2f2 !important;
        animation: pulseError 0.4s ease;
    }

    @keyframes pulseError {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }

    .question-number-badge {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eff6ff;
        color: #2563eb;
        font-weight: 800;
        font-size: 0.88rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .question-card.answered .question-number-badge {
        background: #2563eb;
        color: #ffffff;
    }

    /* Rating Scale Options */
    .rating-scale-group {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
        margin-top: 16px;
    }

    @media (max-width: 576px) {
        .rating-scale-group {
            grid-template-columns: repeat(5, 1fr);
            gap: 5px;
        }
        .rating-label {
            padding: 8px 2px !important;
            min-height: 58px !important;
        }
        .rating-label .rating-num {
            font-size: 1rem !important;
        }
        .rating-label .rating-text {
            font-size: 0.62rem !important;
        }
    }

    .rating-option {
        position: relative;
    }

    .rating-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .rating-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 10px 4px;
        border: 1.5px solid #e2e8f0;
        border-radius: var(--radius-md);
        background: #ffffff;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s ease;
        min-height: 64px;
        user-select: none;
    }

    .rating-label .rating-num {
        font-size: 1.15rem;
        font-weight: 800;
        color: #64748b;
        line-height: 1;
        margin-bottom: 4px;
    }

    .rating-label .rating-text {
        font-size: 0.68rem;
        font-weight: 600;
        color: #94a3b8;
        line-height: 1.1;
    }

    .rating-option input[type="radio"]:checked + .rating-label {
        background: #eff6ff;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .rating-option input[type="radio"]:checked + .rating-label .rating-num {
        color: #2563eb;
        transform: scale(1.12);
    }

    .rating-option input[type="radio"]:checked + .rating-label .rating-text {
        color: #1e40af;
        font-weight: 700;
    }

    .rating-label:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }

    /* Sticky Progress Header */
    .sticky-test-tracker {
        position: sticky;
        top: 64px;
        z-index: 990;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 12px 0;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 20px;
    }

    /* Step Navigation Pills */
    .step-pill-bar {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
    }
    .step-pill-bar::-webkit-scrollbar {
        display: none;
    }

    .step-pill-btn {
        flex: 1;
        min-width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        font-size: 0.78rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .step-pill-btn.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
    }

    .step-pill-btn.completed {
        background: #dcfce7;
        color: #166534;
        border-color: #86efac;
    }

    /* Animations for Step Switch */
    .test-step {
        animation: fadeInStep 0.3s ease;
    }

    @keyframes fadeInStep {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-9">

        @php
            $chunkSize = 6;
            $chunks = $questions->chunk($chunkSize);
            $totalSteps = $chunks->count();
        @endphp

        {{-- Sticky Progress Tracker --}}
        <div class="sticky-test-tracker">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div>
                    <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1 me-1" id="stepBadgeInfo" style="font-size:0.75rem;">
                        Tahap 1 dari {{ $totalSteps }}
                    </span>
                    <span id="trackerCounter" style="font-size: 0.88rem; font-weight: 800; color: #0f172a;">
                        0 / {{ $questions->count() }} Soal
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted" style="font-size:0.75rem;">Selesai:</span>
                    <span id="trackerPercentage" style="font-size: 0.88rem; font-weight: 800; color: #2563eb;">
                        0%
                    </span>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="progress mb-2" style="height: 6px; border-radius: 4px; background: #e2e8f0;">
                <div id="progressBar" class="progress-bar bg-primary" role="progressbar" style="width: 0%; border-radius: 4px; transition: width 0.3s ease;"></div>
            </div>

            {{-- Step Navigation Quick Pills --}}
            <div class="step-pill-bar">
                @foreach($chunks as $cIdx => $cQuestions)
                    <button type="button"
                            class="step-pill-btn {{ $cIdx === 0 ? 'active' : '' }}"
                            id="pill-step-{{ $cIdx + 1 }}"
                            onclick="goToStep({{ $cIdx + 1 }})"
                            title="Tahap {{ $cIdx + 1 }} (Soal {{ $cQuestions->first()->order_num }} - {{ $cQuestions->last()->order_num }})">
                        {{ $cIdx + 1 }}
                    </button>
                @endforeach
            </div>
        </div>

        <form action="{{ route('tes.simpan') }}" method="POST" id="formTesMinat">
            @csrf

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert" style="border-radius:var(--radius-md);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                        <div>
                            <strong>Terdapat soal yang belum dijawab!</strong> Mohon lengkapi seluruh pertanyaan sebelum mengirimkan kuesioner.
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Steps Container --}}
            @foreach($chunks as $cIdx => $cQuestions)
                @php
                    $stepNum = $cIdx + 1;
                    $startNum = $cQuestions->first()->order_num;
                    $endNum = $cQuestions->last()->order_num;
                @endphp

                <div class="test-step {{ $stepNum === 1 ? '' : 'd-none' }}" id="step-container-{{ $stepNum }}" data-step="{{ $stepNum }}">
                    {{-- Step Header Info --}}
                    @php
                        $isAnchorStep = $cQuestions->first()->section === 'career_anchor';
                    @endphp
                    <div class="d-flex align-items-center justify-content-between p-3 mb-3 rounded-3" style="background: {{ $isAnchorStep ? '#fffbeb' : '#f1f5f9' }}; border:1px solid {{ $isAnchorStep ? '#fcd34d' : '#e2e8f0' }};">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge {{ $isAnchorStep ? 'bg-warning text-dark' : 'bg-primary' }} px-2 py-1" style="font-size:0.75rem;">
                                {{ $isAnchorStep ? 'Sesi 2: Career Anchors' : 'Sesi 1: RIASEC' }} — Bagian {{ $stepNum }}
                            </span>
                            <span style="font-size:0.85rem; font-weight:700; color:#1e293b;">Soal Nomor {{ $startNum }} s/d {{ $endNum }}</span>
                        </div>
                        <span class="text-muted" style="font-size:0.75rem;">
                            {{ $cQuestions->count() }} Pertanyaan
                        </span>
                    </div>

                    {{-- Render Questions for this Step --}}
                    @foreach($cQuestions as $q)
                        @php
                            $ansValue = old("answers.{$q->id}", $existingAnswers[$q->id] ?? null);
                            $isAnchor = $q->section === 'career_anchor';
                        @endphp
                        <div class="question-card {{ $ansValue ? 'answered' : '' }}" id="card-q-{{ $q->id }}" data-qid="{{ $q->id }}" data-section="{{ $q->section }}">
                            <div class="d-flex align-items-start gap-3">
                                <div class="question-number-badge" style="{{ $isAnchor ? 'background:#fef3c7; color:#b45309;' : '' }}">
                                    {{ $q->order_num }}
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge {{ $isAnchor ? 'bg-warning text-dark' : 'bg-secondary' }}" style="font-size:0.68rem;">
                                            {{ $isAnchor ? 'Sesi 2: Motivasi Kerja (' . $q->type_anchor . ')' : 'Sesi 1: Minat (' . $q->type_riasec . ')' }}
                                        </span>
                                    </div>
                                    <div style="font-size: 0.96rem; font-weight: 600; color: #0f172a; line-height: 1.55;">
                                        {{ $q->question }}
                                    </div>
                                </div>
                            </div>

                            {{-- Likert 1-5 Group --}}
                            <div class="rating-scale-group">
                                {{-- 1 --}}
                                <div class="rating-option">
                                    <input type="radio"
                                           name="answers[{{ $q->id }}]"
                                           id="opt_{{ $q->id }}_1"
                                           value="1"
                                           {{ (string)$ansValue === '1' ? 'checked' : '' }}
                                           required
                                           onchange="handleAnswerChange({{ $q->id }})">
                                    <label for="opt_{{ $q->id }}_1" class="rating-label">
                                        <span class="rating-num">1</span>
                                        <span class="rating-text">{{ $isAnchor ? 'Sangat Tidak Sesuai' : 'Sangat Tidak Suka' }}</span>
                                    </label>
                                </div>

                                {{-- 2 --}}
                                <div class="rating-option">
                                    <input type="radio"
                                           name="answers[{{ $q->id }}]"
                                           id="opt_{{ $q->id }}_2"
                                           value="2"
                                           {{ (string)$ansValue === '2' ? 'checked' : '' }}
                                           required
                                           onchange="handleAnswerChange({{ $q->id }})">
                                    <label for="opt_{{ $q->id }}_2" class="rating-label">
                                        <span class="rating-num">2</span>
                                        <span class="rating-text">{{ $isAnchor ? 'Tidak Sesuai' : 'Tidak Suka' }}</span>
                                    </label>
                                </div>

                                {{-- 3 --}}
                                <div class="rating-option">
                                    <input type="radio"
                                           name="answers[{{ $q->id }}]"
                                           id="opt_{{ $q->id }}_3"
                                           value="3"
                                           {{ (string)$ansValue === '3' ? 'checked' : '' }}
                                           required
                                           onchange="handleAnswerChange({{ $q->id }})">
                                    <label for="opt_{{ $q->id }}_3" class="rating-label">
                                        <span class="rating-num">3</span>
                                        <span class="rating-text">Cukup / Netral</span>
                                    </label>
                                </div>

                                {{-- 4 --}}
                                <div class="rating-option">
                                    <input type="radio"
                                           name="answers[{{ $q->id }}]"
                                           id="opt_{{ $q->id }}_4"
                                           value="4"
                                           {{ (string)$ansValue === '4' ? 'checked' : '' }}
                                           required
                                           onchange="handleAnswerChange({{ $q->id }})">
                                    <label for="opt_{{ $q->id }}_4" class="rating-label">
                                        <span class="rating-num">4</span>
                                        <span class="rating-text">{{ $isAnchor ? 'Sesuai' : 'Suka' }}</span>
                                    </label>
                                </div>

                                {{-- 5 --}}
                                <div class="rating-option">
                                    <input type="radio"
                                           name="answers[{{ $q->id }}]"
                                           id="opt_{{ $q->id }}_5"
                                           value="5"
                                           {{ (string)$ansValue === '5' ? 'checked' : '' }}
                                           required
                                           onchange="handleAnswerChange({{ $q->id }})">
                                    <label for="opt_{{ $q->id }}_5" class="rating-label">
                                        <span class="rating-num">5</span>
                                        <span class="rating-text">{{ $isAnchor ? 'Sangat Sesuai' : 'Sangat Suka' }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            {{-- Navigation Action Bar --}}
            <div class="card-pro p-3 p-md-4 mt-3 mb-5" style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: var(--radius-lg);">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div>
                        <button type="button"
                                class="btn btn-outline-secondary px-3 py-2 fw-bold d-none"
                                id="btnPrevStep"
                                onclick="prevStep()"
                                style="border-radius: var(--radius-md); font-size: 0.88rem;">
                            <i class="bi bi-arrow-left me-1"></i> Sebelumnya
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted d-none d-sm-inline" style="font-size:0.8rem;" id="stepProgressHint">
                            Isi 6 soal di atas untuk lanjut
                        </span>

                        <button type="button"
                                class="btn btn-primary px-4 py-2 fw-bold"
                                id="btnNextStep"
                                onclick="nextStep()"
                                style="border-radius: var(--radius-md); font-size: 0.88rem;">
                            <span>Lanjut</span> <i class="bi bi-arrow-right ms-1"></i>
                        </button>

                        <button type="submit"
                                class="btn-brand-primary px-4 py-2 d-none"
                                id="btnSubmitTest"
                                style="border-radius: var(--radius-md); font-size: 0.88rem;">
                            <i class="bi bi-check-circle-fill me-1"></i> Analisis & Lihat Hasil Tes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const totalQuestions = {{ $questions->count() }};
    const totalSteps = {{ $totalSteps }};
    let currentStep = 1;

    function handleAnswerChange(qId) {
        const card = document.getElementById('card-q-' + qId);
        if (card) {
            card.classList.add('answered');
            card.classList.remove('has-error');
        }
        recalcProgress();
        checkStepCompletion(currentStep);
    }

    function checkStepCompletion(stepNum) {
        const stepContainer = document.getElementById('step-container-' + stepNum);
        if (!stepContainer) return false;

        const cards = stepContainer.querySelectorAll('.question-card');
        let isComplete = true;

        cards.forEach(card => {
            const qId = card.getAttribute('data-qid');
            const checked = card.querySelector(`input[name="answers[${qId}]"]:checked`);
            if (!checked) {
                isComplete = false;
            }
        });

        // Update pill status
        const pill = document.getElementById('pill-step-' + stepNum);
        if (pill) {
            if (isComplete) {
                pill.classList.add('completed');
            } else {
                pill.classList.remove('completed');
            }
        }

        return isComplete;
    }

    function goToStep(targetStep) {
        if (targetStep < 1 || targetStep > totalSteps) return;

        // Hide current step
        const currentContainer = document.getElementById('step-container-' + currentStep);
        if (currentContainer) {
            currentContainer.classList.add('d-none');
        }

        // Deactivate old pill
        const oldPill = document.getElementById('pill-step-' + currentStep);
        if (oldPill) oldPill.classList.remove('active');

        // Show target step
        currentStep = targetStep;
        const targetContainer = document.getElementById('step-container-' + currentStep);
        if (targetContainer) {
            targetContainer.classList.remove('d-none');
        }

        // Activate new pill
        const newPill = document.getElementById('pill-step-' + currentStep);
        if (newPill) newPill.classList.add('active');

        // Update badge text
        const badgeInfo = document.getElementById('stepBadgeInfo');
        if (badgeInfo) {
            badgeInfo.textContent = `Tahap ${currentStep} dari ${totalSteps}`;
        }

        // Navigation button states
        const btnPrev = document.getElementById('btnPrevStep');
        const btnNext = document.getElementById('btnNextStep');
        const btnSubmit = document.getElementById('btnSubmitTest');
        const stepHint = document.getElementById('stepProgressHint');

        if (currentStep > 1) {
            btnPrev.classList.remove('d-none');
        } else {
            btnPrev.classList.add('d-none');
        }

        if (currentStep === totalSteps) {
            btnNext.classList.add('d-none');
            btnSubmit.classList.remove('d-none');
            if (stepHint) stepHint.textContent = 'Tahap terakhir! Pastikan seluruh soal terisi.';
        } else {
            btnNext.classList.remove('d-none');
            btnSubmit.classList.add('d-none');
            if (stepHint) stepHint.textContent = `Langkah ${currentStep} dari ${totalSteps}`;
        }

        // Smooth scroll to top of form
        window.scrollTo({ top: 120, behavior: 'smooth' });
    }

    function nextStep() {
        const isComplete = validateCurrentStep();
        if (!isComplete) {
            return;
        }

        if (currentStep < totalSteps) {
            goToStep(currentStep + 1);
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            goToStep(currentStep - 1);
        }
    }

    function validateCurrentStep() {
        const stepContainer = document.getElementById('step-container-' + currentStep);
        if (!stepContainer) return true;

        const cards = stepContainer.querySelectorAll('.question-card');
        let firstUnanswered = null;

        cards.forEach(card => {
            const qId = card.getAttribute('data-qid');
            const checked = card.querySelector(`input[name="answers[${qId}]"]:checked`);
            if (!checked) {
                card.classList.add('has-error');
                if (!firstUnanswered) {
                    firstUnanswered = card;
                }
            } else {
                card.classList.remove('has-error');
            }
        });

        if (firstUnanswered) {
            firstUnanswered.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        return true;
    }

    function recalcProgress() {
        const answeredCount = document.querySelectorAll('input[type="radio"]:checked').length;
        const pct = Math.round((answeredCount / totalQuestions) * 100);

        const counterEl = document.getElementById('trackerCounter');
        const pctEl = document.getElementById('trackerPercentage');
        const barEl = document.getElementById('progressBar');

        if (counterEl) counterEl.textContent = `${answeredCount} / ${totalQuestions} Soal`;
        if (pctEl) pctEl.textContent = `${pct}%`;
        if (barEl) barEl.style.width = `${pct}%`;

        // Check completion on all steps for pills
        for (let s = 1; s <= totalSteps; s++) {
            checkStepCompletion(s);
        }
    }

    /**
     * Algoritma Fisher-Yates Shuffle untuk mengacak urutan TAMPILAN soal Sesi 1 (RIASEC, #1-48).
     * ID Soal dan Tag Dimensi di database/backend tetap aman dan tidak berubah.
     * Soal Sesi 2 (Career Anchors, #49-72) tetap dikunci pada posisinya.
     */
    function shuffleRiasecQuestions() {
        const riasecCards = Array.from(document.querySelectorAll('.question-card[data-section="riasec"]'));
        if (riasecCards.length === 0) return;

        // Implementasi Algoritma Fisher-Yates Shuffle
        for (let i = riasecCards.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [riasecCards[i], riasecCards[j]] = [riasecCards[j], riasecCards[i]];
        }

        // Distribusikan kartu yang telah diacak ke dalam 8 Step Container (6 soal per step)
        riasecCards.forEach((card, index) => {
            // Update nomor urut visual di layar (1 s/d 48)
            const badge = card.querySelector('.question-number-badge');
            if (badge) {
                badge.textContent = index + 1;
            }

            // Hitung kontainer tahap tujuan (step 1 s/d 8)
            const targetStepNum = Math.floor(index / 6) + 1;
            const container = document.getElementById('step-container-' + targetStepNum);
            if (container) {
                container.appendChild(card);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Acak susunan tampilan soal Sesi 1 (RIASEC) sebelum ditampilkan ke layar
        shuffleRiasecQuestions();

        recalcProgress();

        // Check if there are errors from backend, go to the first unanswered step
        const firstUnansweredRadio = document.querySelector('.test-step input[type="radio"]:not(:checked)');
        // Ensure starting on step 1
        goToStep(1);
    });
</script>
@endpush
