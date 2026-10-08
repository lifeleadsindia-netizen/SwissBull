@extends('member.layouts.main')
@section('title', 'Business Plan PDF')
@section('container')

    <div class="content-body">
        <div class="container-fluid pt-2 pb-5" style="padding-bottom: 80px !important;">
            <!-- Page Header -->
            <div class="page-titles mb-3">
                <div class="welcome-text">
                    <h4 class="text-white font-weight-bold mb-1">Business Plan PDF</h4>
                    <p class="mb-0 text-muted" style="font-size: 13px;">Official multilingual business plans and pitch decks.
                        Preview online or download for presentation and prospect sharing.</p>
                </div>
                <div class="justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/member/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Business Plan PDF</a></li>
                    </ol>
                </div>
            </div>

            <!-- Hero Section -->
            <div class="pdf-hero-card mb-4 p-4">
                <div class="row align-items-center g-3">
                    <div class="col-lg-7 col-md-12">
                        <div class="d-flex align-items-center mb-2 flex-wrap gap-2">
                            <span class="badge pdf-hero-badge">
                                <i class="fas fa-file-pdf me-1"></i> Official Pitch Decks
                            </span>
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1"
                                style="font-size: 11px;">
                                <i class="fas fa-globe me-1"></i> {{ count($pdfs) }} Languages Available
                            </span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"
                                style="font-size: 11px;">
                                <i class="fas fa-eye me-1"></i> In-App Online Viewer
                            </span>
                        </div>
                        <h3 class="text-white fw-bold mb-2">SYNC TRADE Official Business Presentations</h3>
                        <p class="text-light-50 mb-3"
                            style="font-size: 13.5px; line-height: 1.6; color: rgba(255,255,255,0.75);">
                            Access the complete verified business plan in <strong>English</strong>, <strong>Chinese
                                (中文)</strong>, and <strong>Russian (Русский)</strong>. Use these presentations to guide your
                            prospects, explain staking & income plans, and build an international organization.
                        </p>

                        <!-- Quick Referral Link Bar -->
                        <div class="referral-bar-wrapper p-2 rounded-3">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <div class="flex-grow-1 position-relative" style="min-width: 220px;">
                                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted"
                                        style="font-size: 13px;">
                                        <i class="fas fa-link text-warning"></i>
                                    </span>
                                    <input type="text" id="memberReferralInput" class="form-control referral-input ps-5"
                                        value="{{ $referralLink }}" readonly>
                                </div>
                                <button type="button" class="btn btn-warning btn-sm px-3 fw-bold d-flex align-items-center"
                                    onclick="copyReferralLink()">
                                    <i class="far fa-copy me-1"></i> <span id="copyBtnText">Copy Link</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-12 text-center text-lg-end">
                        <div class="pdf-quick-stats p-3 rounded-3 text-start d-inline-block w-100"
                            style="max-width: 420px;">
                            <h6 class="text-white fw-bold mb-2 d-flex align-items-center" style="font-size: 13px;">
                                <i class="fas fa-check-double text-success me-2"></i> What's Inside Each PDF?
                            </h6>
                            <ul class="list-unstyled mb-0 text-white-50" style="font-size: 12px; line-height: 1.8;">
                                <li><i class="fas fa-chevron-right text-primary me-2"></i> <strong>Platform
                                        Overview:</strong> Account activation & secure wallet system.</li>
                                <li><i class="fas fa-chevron-right text-primary me-2"></i> <strong>Income
                                        Programs:</strong> Level Income (Activation & Staking), Single Leg Income (15 Stages).</li>
                                <li><i class="fas fa-chevron-right text-primary me-2"></i> <strong>Team Rewards:</strong> Team Withdrawal Commission, Partnership Income & Promotion Airdrop.</li>
                                <li><i class="fas fa-chevron-right text-primary me-2"></i> <strong>Roadmap &
                                        Vision:</strong> Global launch & decentralized growth.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Language PDFs Section Header -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="text-white fw-bold mb-0">Select Language Edition</h5>
                    <span class="text-muted" style="font-size: 12px;">Choose your preferred language to preview online or
                        download the pitch deck</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-dark border border-secondary px-3 py-2 text-white-50" style="font-size: 12px;">
                        <i class="fas fa-file-invoice text-danger me-1"></i> High-Resolution Vector PDF
                    </span>
                </div>
            </div>

            <!-- Multilingual Cards Grid -->
            <div class="row g-4 mb-5">
                @forelse ($pdfs as $index => $pdf)
                    <div class="col-xl-4 col-lg-6 col-md-12">
                        <div class="pdf-card h-100 d-flex flex-column" style="border-color: {{ $pdf['border_color'] }};">
                            <!-- Card Top Accent Header -->
                            <div class="pdf-card-top p-4" style="background: {{ $pdf['gradient'] }};">
                                <!-- Card Header: Row 1 Badge & Edition -->
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                    <span class="badge pdf-edition-badge"
                                        style="background: rgba(0,0,0,0.5); color: {{ $pdf['badge_color'] }}; border: 1px solid {{ $pdf['badge_color'] }};">
                                        <i class="fas fa-shield-alt me-1"></i> {{ $pdf['badge'] }}
                                    </span>
                                    <span class="text-white-50" style="font-size: 11.5px; font-weight: 500;">
                                        {{ $pdf['language'] }} Edition
                                    </span>
                                </div>

                                <!-- Card Header: Row 2 Flag + Title -->
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="language-flag-badge shadow-sm">{{ $pdf['flag'] }}</span>
                                    <div>
                                        <h4 class="text-white fw-bold mb-0" style="font-size: 18px; line-height: 1.2;">
                                            {{ $pdf['native_language'] }}</h4>
                                    </div>
                                </div>

                                <!-- PDF Graphic Preview Placeholder Card -->
                                <div class="pdf-preview-box rounded-3 p-3 d-flex align-items-center justify-content-between"
                                    onclick="openPdfModal({{ $index }})" style="cursor: pointer;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="pdf-icon-symbol rounded-circle d-flex align-items-center justify-content-center"
                                            style="background: rgba(239, 68, 68, 0.15); width: 46px; height: 46px;">
                                            <i class="fas fa-file-pdf text-danger fa-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-white fw-bold mb-0" style="font-size: 13.5px;">
                                                {{ $pdf['filename'] }}</h6>
                                            <span class="text-white-50" style="font-size: 11px;">
                                                <i class="fas fa-database me-1"></i> {{ $pdf['size'] }} &bull;
                                                {{ $pdf['pages_hint'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="btn btn-sm btn-outline-light rounded-circle p-2"
                                        title="Preview Presentation">
                                        <i class="fas fa-eye text-white"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-3 p-xl-4 flex-grow-1 d-flex flex-column justify-content-between">
                                <div class="mb-3">
                                    <h6 class="text-white fw-bold mb-2" style="font-size: 15px;">{{ $pdf['title'] }}</h6>
                                    <p class="text-white-50 mb-0" style="font-size: 12.5px; line-height: 1.6;">
                                        {{ $pdf['description'] }}
                                    </p>
                                </div>

                                <!-- Card Actions -->
                                <div class="pt-3 border-top border-secondary-subtle">
                                    <div class="d-grid gap-2 mb-2">
                                        <div class="row g-2">
                                            <!-- Preview Online Button -->
                                            <div class="col-6">
                                                <button type="button"
                                                    class="btn btn-preview-online w-100 d-flex align-items-center justify-content-center"
                                                    onclick="openPdfModal({{ $index }})">
                                                    <i class="fas fa-desktop me-1"></i> Preview
                                                </button>
                                            </div>
                                            <!-- Download PDF Button -->
                                            <div class="col-6">
                                                <a href="{{ $pdf['download_url'] }}"
                                                    class="btn btn-download-pdf w-100 d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-download me-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-2 pt-1">
                                        <!-- Open in New Tab -->
                                        <a href="{{ $pdf['url'] }}" target="_blank"
                                            class="btn btn-sm btn-outline-secondary text-white-50 flex-grow-1"
                                            style="font-size: 11.5px;">
                                            <i class="fas fa-external-link-alt me-1"></i> Open in Tab
                                        </a>

                                        <!-- Copy PDF URL -->
                                        <button type="button" class="btn btn-sm btn-outline-secondary text-white-50"
                                            style="font-size: 11.5px;" onclick="copyPdfUrl('{{ $pdf['url'] }}')"
                                            title="Copy Direct PDF Link">
                                            <i class="far fa-copy me-1"></i> Copy Link
                                        </button>

                                        <!-- WhatsApp Share with Prospect -->
                                        <a href="https://wa.me/?text={{ rawurlencode("📊 *SYNC TRADE Official Business Plan ({$pdf['language']} Edition)*\n\nTake a look at the comprehensive presentation deck:\n👉 Direct PDF: " . $pdf['url'] . "\n\n🚀 Join my team using this link:\n👉 " . $referralLink) }}"
                                            target="_blank" class="btn btn-sm btn-success" style="font-size: 11.5px;"
                                            title="Share to WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="p-5 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.15);">
                            <i class="fas fa-file-pdf text-muted mb-3" style="font-size: 40px;"></i>
                            <h5 class="text-white fw-bold mb-2">No Business Plan PDFs Available</h5>
                            <p class="text-white-50 mb-0" style="font-size: 13.5px;">Official business plan presentation documents will appear here once published by the administration.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Generous Bottom Gap Buffer -->
            <div class="pb-5 mb-5" style="height: 60px;"></div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- PDF PREVIEW MODAL -->
    <!-- ============================================================ -->
    <div class="modal fade pdf-preview-modal" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfPreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0">
                <div class="modal-header border-0 pb-2 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span id="modalPdfFlag" class="language-flag-badge shadow-sm"
                            style="width: 32px; height: 32px; font-size: 18px;">🇬🇧</span>
                        <div>
                            <h5 class="modal-title text-white fw-bold mb-0" id="modalPdfTitle">Business Plan Preview</h5>
                            <small class="text-white-50" id="modalPdfFilename">SYNC TRADE English.pdf</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a id="modalNewTabBtn" href="#" target="_blank" class="btn btn-sm btn-outline-light"
                            title="Open Full Screen in New Tab">
                            <i class="fas fa-external-link-alt me-1"></i> New Tab
                        </a>
                        <a id="modalDownloadBtn" href="#" class="btn btn-sm btn-download-pdf fw-bold">
                            <i class="fas fa-download me-1"></i> Download PDF
                        </a>
                        <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body p-2" style="background: #0b0f19;">
                    <!-- PDF Viewer Container -->
                    <div class="pdf-iframe-container rounded-3 overflow-hidden"
                        style="height: 78vh; background: #151b2b;">
                        <iframe id="pdfIframe" src="" class="w-100 h-100"
                            style="border: none; border-radius: 8px;" allow="fullscreen"></iframe>
                    </div>

                    <!-- Fallback / Full tab view link -->
                    <div class="d-flex justify-content-between align-items-center mt-2 px-2 flex-wrap gap-1">
                        <small class="text-white-50" style="font-size: 11.5px;">
                            <i class="fas fa-info-circle text-info me-1"></i> Interactive PDF Presentation Deck
                        </small>
                        <small>
                            <a id="modalMobileFallbackLink" href="#" target="_blank"
                                class="text-warning text-decoration-underline" style="font-size: 11.5px;">
                                <i class="fas fa-external-link-alt me-1"></i> Open in Full Browser Tab
                            </a>
                        </small>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-2 d-flex justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary text-white"
                            onclick="copyCurrentModalPdfUrl()">
                            <i class="far fa-copy me-1"></i> Copy PDF Link
                        </button>
                        <a id="modalWaShareBtn" href="#" target="_blank" class="btn btn-sm btn-success">
                            <i class="fab fa-whatsapp me-1"></i> Share on WhatsApp
                        </a>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close Viewer</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ============================================================
           BUSINESS PLAN PDF STYLES
           ============================================================ */
        .pdf-hero-card {
            background: linear-gradient(135deg, #0C0F17 0%, #1A1408 40%, #362203 80%, #683F06 120%);
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.65), 0 0 20px rgba(245, 158, 11, 0.12);
            position: relative;
            overflow: hidden;
        }

        .pdf-hero-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
            pointer-events: none;
        }

        .pdf-hero-badge {
            background: rgba(245, 158, 11, 0.18);
            color: #FBBF24;
            border: 1px solid rgba(245, 158, 11, 0.35);
            font-size: 11.5px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .referral-bar-wrapper {
            background: rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(245, 158, 11, 0.18);
        }

        .referral-input {
            background: rgba(8, 9, 12, 0.8) !important;
            border: 1px solid rgba(245, 158, 11, 0.25) !important;
            color: #10B981 !important;
            font-size: 13px !important;
            font-weight: 600;
            border-radius: 8px;
        }

        .pdf-quick-stats {
            background: rgba(245, 158, 11, 0.03);
            border: 1px dashed rgba(245, 158, 11, 0.25);
        }

        /* Multilingual Card */
        .pdf-card {
            background: #0C0E14;
            border: 1px solid rgba(245, 158, 11, 0.18);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.3);
        }

        .pdf-card:hover {
            transform: translateY(-6px);
            border-color: rgba(245, 158, 11, 0.38);
            box-shadow: 0 14px 32px rgba(0, 0, 0, 0.65), 0 0 20px rgba(245, 158, 11, 0.1);
        }

        .language-flag-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 22px;
        }

        .pdf-edition-badge {
            font-size: 10.5px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .pdf-preview-box {
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(245, 158, 11, 0.15);
            transition: all 0.2s ease;
        }

        .pdf-preview-box:hover {
            background: rgba(0, 0, 0, 0.55);
            border-color: rgba(245, 158, 11, 0.35);
            transform: scale(1.01);
        }

        /* Buttons */
        .btn-preview-online {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            color: #08090C;
            font-size: 13px;
            font-weight: 800;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-preview-online:hover {
            background: linear-gradient(135deg, #FBBF24 0%, #B45309 100%);
            color: #08090C;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
        }

        .btn-download-pdf {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-download-pdf:hover {
            background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        /* Modal Styling */
        .pdf-preview-modal .modal-content {
            background: #0C0E14;
            border: 1px solid rgba(245, 158, 11, 0.25);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.85);
        }

        .pdf-iframe-container {
            height: 75vh;
            background: #000;
        }
    </style>

    <script>
        const staticPdfs = @json($pdfs);
        const memberReferralUrl = "{{ $referralLink }}";
        let activeModalPdfIndex = 0;

        // Automatically convert any URL to active live domain when hosted online
        function getLiveUrl(url) {
            if (!url) return '';
            try {
                const parsed = new URL(url, window.location.origin);
                if (window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                    return window.location.origin + parsed.pathname + parsed.search + parsed.hash;
                }
                return parsed.href;
            } catch (e) {
                return url;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const refInput = document.getElementById('memberReferralInput');
            if (refInput) {
                refInput.value = getLiveUrl(refInput.value);
            }
            if (window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                const liveOrigin = window.location.origin;
                const encodedLiveOrigin = encodeURIComponent(liveOrigin);
                document.querySelectorAll('a[href*="wa.me"]').forEach(link => {
                    link.href = link.href.replace(/https?%3A%2F%2F(localhost|127\.0\.0\.1)(%3A\d+)?/gi,
                        encodedLiveOrigin);
                    link.href = link.href.replace(/https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?/gi,
                        liveOrigin);
                });
                document.querySelectorAll('a[target="_blank"]:not([href*="wa.me"])').forEach(a => {
                    if (a.href && (a.href.includes('/uassets/') || a.href.includes('/member/register/'))) {
                        a.href = getLiveUrl(a.href);
                    }
                });
            }
        });

        function copyReferralLink() {
            const input = document.getElementById('memberReferralInput');
            const liveLink = getLiveUrl(input.value);
            input.value = liveLink;
            input.select();
            input.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(liveLink).then(() => {
                const btnText = document.getElementById('copyBtnText');
                btnText.innerText = 'Copied!';
                setTimeout(() => {
                    btnText.innerText = 'Copy Link';
                }, 2000);

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Referral link copied to clipboard!',
                        showConfirmButton: false,
                        timer: 2000,
                        background: '#161c2d',
                        color: '#fff'
                    });
                }
            }).catch(err => {
                document.execCommand('copy');
                alert('Referral link copied!');
            });
        }

        function copyPdfUrl(url) {
            const liveUrl = getLiveUrl(url);
            navigator.clipboard.writeText(liveUrl).then(() => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'PDF direct link copied!',
                        showConfirmButton: false,
                        timer: 2000,
                        background: '#161c2d',
                        color: '#fff'
                    });
                } else {
                    alert('PDF link copied to clipboard!');
                }
            });
        }

        function openPdfModal(index) {
            if (index < 0 || index >= staticPdfs.length) return;
            activeModalPdfIndex = index;
            const pdf = staticPdfs[index];
            const livePdfUrl = getLiveUrl(pdf.url);
            const liveReferralUrl = getLiveUrl(memberReferralUrl);

            document.getElementById('modalPdfFlag').innerText = pdf.flag;
            document.getElementById('modalPdfTitle').innerText = pdf.title;
            document.getElementById('modalPdfFilename').innerText = pdf.filename + ' (' + pdf.size + ')';
            document.getElementById('modalNewTabBtn').href = livePdfUrl;
            document.getElementById('modalDownloadBtn').href = pdf.download_url;
            document.getElementById('modalMobileFallbackLink').href = livePdfUrl;

            // WhatsApp share link
            const waText = "📊 *SYNC TRADE Official Business Plan (" + pdf.language +
                " Edition)*\n\nTake a look at the comprehensive presentation deck:\n👉 Direct PDF: " + livePdfUrl +
                "\n\n🚀 Join my team using this link:\n👉 " + liveReferralUrl;
            document.getElementById('modalWaShareBtn').href = "https://wa.me/?text=" + encodeURIComponent(waText);

            // Set iframe src directly with toolbar parameter
            document.getElementById('pdfIframe').src = livePdfUrl + '#toolbar=1&navpanes=0';

            const modalEl = document.getElementById('pdfPreviewModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();

            // Clean up iframe on modal close to free memory
            modalEl.addEventListener('hidden.bs.modal', function onModalHidden() {
                document.getElementById('pdfIframe').src = '';
                modalEl.removeEventListener('hidden.bs.modal', onModalHidden);
            });
        }

        function copyCurrentModalPdfUrl() {
            const pdf = staticPdfs[activeModalPdfIndex];
            if (pdf) {
                copyPdfUrl(pdf.url);
            }
        }
    </script>

@endsection
