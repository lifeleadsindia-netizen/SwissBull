@extends('member.layouts.main')
@section('title', 'Business Plan PDF')
@section('container')

    <div class="content-body">
        <div class="container-fluid pt-2 pb-5" style="padding-bottom: 80px !important;">
            <!-- Page Header -->
            <div class="page-titles mb-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="welcome-text">
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <a href="{{ url('/member/dashboard') }}" class="btn btn-sm btn-outline-warning d-inline-flex align-items-center px-3 py-1 rounded-pill fw-bold" style="font-size: 12px;">
                                <i class="fas fa-arrow-left me-1.5"></i> Back to Dashboard
                            </a>
                            <button type="button" class="btn btn-sm btn-warning text-dark d-inline-flex d-md-none align-items-center px-3 py-1 rounded-pill fw-bold" onclick="$('.nav-control').first().trigger('click');">
                                <i class="fas fa-bars me-1.5"></i> Open Menu
                            </button>
                            <span class="badge pdf-hero-badge px-2 py-1" style="font-size: 11px;">
                                <i class="fas fa-file-pdf me-1"></i> Official PDF Decks
                            </span>
                        </div>
                        <h4 class="text-white font-weight-bold mb-1" style="font-size: clamp(18px, 4vw, 24px);">Business Plan PDF</h4>
                        <p class="mb-0 text-muted" style="font-size: 12.5px; word-break: break-word;">Official multilingual business plans and pitch decks. Preview online, download, or share with prospects.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="{{ url('/member/dashboard') }}" class="btn btn-outline-secondary text-white btn-sm px-3 d-inline-flex align-items-center rounded-pill">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                        <a href="{{ url('/member/business-plan-text') }}" class="btn btn-outline-info btn-sm px-3 d-inline-flex align-items-center rounded-pill">
                            <i class="fas fa-file-alt me-1"></i> Text Plan
                        </a>
                    </div>
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
            <div class="row g-4 mb-5 d-flex align-items-stretch">
                @forelse ($pdfs as $index => $pdf)
                    <div class="col-xl-4 col-lg-6 col-md-12 d-flex">
                        <div class="pdf-card w-100 h-100 d-flex flex-column" style="border-color: {{ $pdf['border_color'] }}; min-width: 0;">
                            <!-- Card Top Accent Header -->
                            <div class="pdf-card-top p-3 p-sm-4" style="background: {{ $pdf['gradient'] }}; min-width: 0;">
                                <!-- Card Header: Row 1 Badge & Edition -->
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2" style="min-width: 0;">
                                    <span class="badge pdf-edition-badge text-wrap text-start"
                                        style="background: rgba(0,0,0,0.5); color: {{ $pdf['badge_color'] }}; border: 1px solid {{ $pdf['badge_color'] }}; word-break: break-word; overflow-wrap: anywhere; max-width: 100%; line-height: 1.3;">
                                        <i class="fas fa-shield-alt me-1"></i> {{ $pdf['badge'] }}
                                    </span>
                                    <span class="text-white-50" style="font-size: 11.5px; font-weight: 500; word-break: break-word;">
                                        {{ $pdf['language'] }} Edition
                                    </span>
                                </div>

                                <!-- Card Header: Row 2 Flag + Title -->
                                <div class="d-flex align-items-center gap-2 mb-3" style="min-width: 0;">
                                    <span class="language-flag-badge shadow-sm flex-shrink-0">{{ $pdf['flag'] }}</span>
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <h4 class="text-white fw-bold mb-0" style="font-size: 18px; line-height: 1.3; word-break: break-word; overflow-wrap: anywhere;">
                                            {{ $pdf['native_language'] }}</h4>
                                    </div>
                                </div>

                                <!-- PDF Graphic Preview Placeholder Card -->
                                <div class="pdf-preview-box rounded-3 p-3 d-flex align-items-center justify-content-between gap-2"
                                    onclick="openPdfModal({{ $index }})" style="cursor: pointer; min-width: 0;">
                                    <div class="d-flex align-items-center gap-3 flex-grow-1" style="min-width: 0;">
                                        <div class="pdf-icon-symbol rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="background: rgba(239, 68, 68, 0.15); width: 44px; height: 44px;">
                                            <i class="fas fa-file-pdf text-danger fa-lg"></i>
                                        </div>
                                        <div class="flex-grow-1" style="min-width: 0; overflow: hidden;">
                                            <h6 class="text-white fw-bold mb-1"
                                                style="font-size: 13.5px; line-height: 1.35; word-break: break-word; overflow-wrap: anywhere;">
                                                {{ $pdf['filename'] }}</h6>
                                            <span class="text-white-50 d-block" style="font-size: 11px; word-break: break-word; overflow-wrap: anywhere;">
                                                <i class="fas fa-database me-1"></i> {{ $pdf['size'] }} &bull;
                                                {{ $pdf['pages_hint'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="btn btn-sm btn-outline-light rounded-circle p-2 flex-shrink-0 ms-2"
                                        title="Preview Presentation"
                                        style="width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-eye text-white" style="font-size: 12px;"></i>
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-3 p-xl-4 flex-grow-1 d-flex flex-column justify-content-between" style="min-width: 0;">
                                <div class="mb-3" style="min-width: 0;">
                                    @if (!empty($pdf['title']) && $pdf['title'] !== $pdf['native_language'])
                                        <h6 class="text-white fw-bold mb-2" style="font-size: 15px; line-height: 1.4; word-break: break-word; overflow-wrap: anywhere;">
                                            {{ $pdf['title'] }}</h6>
                                    @endif
                                    @if (!empty($pdf['description']))
                                        <div class="text-white-50 mb-0 pdf-description-text" style="font-size: 12.5px; line-height: 1.6; word-break: break-word; overflow-wrap: anywhere;">
                                            {!! nl2br(e($pdf['description'])) !!}
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Actions -->
                                <div class="pt-3 border-top border-secondary-subtle mt-auto" style="min-width: 0;">
                                    <div class="d-grid gap-2 mb-2">
                                        <div class="row g-2">
                                            <!-- Preview Online Button -->
                                            <div class="col-6">
                                                <button type="button"
                                                    class="btn btn-preview-online w-100 d-flex align-items-center justify-content-center text-nowrap"
                                                    onclick="openPdfModal({{ $index }})">
                                                    <i class="fas fa-desktop me-1"></i> Preview
                                                </button>
                                            </div>
                                            <!-- Download PDF Button -->
                                            <div class="col-6">
                                                <a href="{{ $pdf['download_url'] }}"
                                                    class="btn btn-download-pdf w-100 d-flex align-items-center justify-content-center text-nowrap">
                                                    <i class="fas fa-download me-1"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between gap-2 pt-1 flex-wrap">
                                        <!-- Open in New Tab -->
                                        <a href="{{ $pdf['url'] }}" target="_blank"
                                            class="btn btn-sm btn-outline-secondary text-white-50 flex-grow-1 text-center text-nowrap"
                                            style="font-size: 11.5px;">
                                            <i class="fas fa-external-link-alt me-1"></i> Open in Tab
                                        </a>

                                        <!-- Copy PDF URL -->
                                        <button type="button" class="btn btn-sm btn-outline-secondary text-white-50 text-nowrap"
                                            style="font-size: 11.5px;" onclick="copyPdfUrl('{{ $pdf['url'] }}')"
                                            title="Copy Direct PDF Link">
                                            <i class="far fa-copy me-1"></i> Copy Link
                                        </button>

                                        <!-- WhatsApp Share with Prospect -->
                                        <a href="https://wa.me/?text={{ rawurlencode("📊 *SYNC TRADE Official Business Plan ({$pdf['language']} Edition)*\n\nTake a look at the comprehensive presentation deck:\n👉 Direct PDF: " . $pdf['url'] . "\n\n🚀 Join my team using this link:\n👉 " . $referralLink) }}"
                                            target="_blank" class="btn btn-sm btn-success flex-shrink-0" style="font-size: 11.5px;"
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

            <!-- Floating Mobile Navigation Widget (Always accessible on mobile) -->
            <div class="mobile-floating-actions d-flex d-md-none align-items-center gap-2">
                <a href="{{ url('/member/dashboard') }}" class="btn btn-sm btn-dark text-warning border-warning shadow-lg px-3 py-2 rounded-pill d-flex align-items-center fw-bold">
                    <i class="fas fa-arrow-left me-1.5"></i> Dashboard
                </a>
                <button type="button" class="btn btn-sm btn-warning text-dark shadow-lg px-3 py-2 rounded-pill d-flex align-items-center fw-bold" onclick="$('.nav-control').first().trigger('click');">
                    <i class="fas fa-bars me-1.5"></i> Menu
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- PDF PREVIEW MODAL -->
    <!-- ============================================================ -->
    <div class="modal fade pdf-preview-modal" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfPreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-md-down modal-dialog-scrollable">
            <div class="modal-content border-0">
                <div class="modal-header border-0 pb-2 d-flex justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2 flex-grow-1" style="min-width: 0;">
                        <button type="button" class="btn btn-warning btn-sm fw-bold px-2.5 py-1 d-flex align-items-center flex-shrink-0" data-bs-dismiss="modal" title="Back to Plans">
                            <i class="fas fa-arrow-left me-1"></i> <span>Back</span>
                        </button>
                        <span id="modalPdfFlag" class="language-flag-badge shadow-sm flex-shrink-0"
                            style="width: 32px; height: 32px; font-size: 16px;">🇬🇧</span>
                        <div class="flex-grow-1" style="min-width: 0;">
                            <h5 class="modal-title text-white fw-bold mb-0 text-truncate" id="modalPdfTitle" style="word-break: break-word;">Business Plan Preview</h5>
                            <small class="text-white-50 d-block text-truncate" id="modalPdfFilename">SYNC TRADE English.pdf</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-1.5 flex-shrink-0">
                        <a id="modalNewTabBtn" href="#" target="_blank" class="btn btn-sm btn-outline-light text-nowrap d-none d-sm-inline-flex align-items-center"
                            title="Open Full Screen in New Tab">
                            <i class="fas fa-external-link-alt me-1"></i> New Tab
                        </a>
                        <a id="modalDownloadBtn" href="#" class="btn btn-sm btn-download-pdf fw-bold text-nowrap d-flex align-items-center">
                            <i class="fas fa-download me-1"></i> <span class="d-none d-sm-inline">Download</span><span class="d-inline d-sm-none">PDF</span>
                        </a>
                        <button type="button" class="btn-close btn-close-white ms-1" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body p-2" style="background: #0b0f19;">
                    <!-- Interactive Touch Toolbar for PDF Navigation & Zoom -->
                    <div id="pdfTouchToolbar" class="pdf-touch-toolbar d-flex align-items-center justify-content-between p-2 mb-2 rounded-3 flex-wrap gap-2"
                        style="background: #121622; border: 1px solid rgba(245, 158, 11, 0.35);">
                        <!-- Page Navigation -->
                        <div class="d-flex align-items-center gap-1.5">
                            <button type="button" class="btn btn-sm btn-dark text-warning border-secondary px-2.5 py-1" onclick="pdfChangePage(-1)" id="btnPdfPrev" title="Previous Page">
                                <i class="fas fa-chevron-left me-1"></i> <span class="d-none d-sm-inline">Prev</span>
                            </button>
                            <span class="badge bg-black text-warning border border-secondary px-2.5 py-1.5 fw-bold" style="font-size: 11.5px; min-width: 90px;" id="pdfPageIndicator">
                                Page 1 / --
                            </span>
                            <button type="button" class="btn btn-sm btn-dark text-warning border-secondary px-2.5 py-1" onclick="pdfChangePage(1)" id="btnPdfNext" title="Next Page">
                                <span class="d-none d-sm-inline">Next</span> <i class="fas fa-chevron-right ms-1"></i>
                            </button>
                        </div>

                        <!-- Zoom Controls & View Mode -->
                        <div class="d-flex align-items-center gap-1.5 flex-wrap">
                            <button type="button" class="btn btn-sm btn-dark text-white border-secondary px-2 py-1" onclick="pdfZoomChange(-0.2)" title="Zoom Out">
                                <i class="fas fa-search-minus"></i>
                            </button>
                            <span class="badge bg-black text-white border border-secondary px-2 py-1.5 fw-bold" style="font-size: 11px; min-width: 48px;" id="pdfZoomBadge">
                                Fit
                            </span>
                            <button type="button" class="btn btn-sm btn-dark text-white border-secondary px-2 py-1" onclick="pdfZoomChange(0.2)" title="Zoom In">
                                <i class="fas fa-search-plus"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning text-dark fw-bold px-2.5 py-1" onclick="pdfFitWidth()" title="Fit to Screen Width">
                                <i class="fas fa-arrows-alt-h me-1"></i> Fit
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary text-white px-2 py-1" onclick="toggleViewerMode()" id="btnViewerMode" title="Switch to Browser Plugin">
                                <i class="fas fa-desktop"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Touch & Scroll Status Hint -->
                    <div class="d-flex align-items-center justify-content-between px-2 pb-1.5 text-white-50 flex-wrap gap-1" style="font-size: 11px;">
                        <span><i class="fas fa-hand-pointer text-warning me-1"></i> Swipe up/down for pages, swipe left/right to pan when zoomed</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-1.5 py-0.5" style="font-size: 10px;">
                            <i class="fas fa-check-circle me-1"></i> Touch Scroll Active
                        </span>
                    </div>

                    <!-- Main Canvas Scrollable Viewport (Supports Vertical AND Horizontal Touch Scroll) -->
                    <div id="pdfCanvasContainer" class="pdf-canvas-container rounded-3 position-relative"
                        style="width: 100%; height: 60vh; min-height: 400px; overflow: auto; -webkit-overflow-scrolling: touch; touch-action: pan-x pan-y; background: #080a10; text-align: center; padding: 10px 4px;">
                        <!-- Loading spinner -->
                        <div id="pdfCanvasLoading" class="py-5 text-center">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-white-50 mt-2 mb-0" style="font-size: 12.5px;">Loading presentation deck...</p>
                        </div>
                        <!-- Canvases list -->
                        <div id="pdfPagesRenderList" class="d-flex flex-column align-items-center gap-3"></div>
                    </div>

                    <!-- Fallback Native Browser Iframe (Toggled via Desktop button) -->
                    <div id="pdfIframeWrapper" class="rounded-3 overflow-hidden position-relative" style="display: none; height: 60vh; background: #000;">
                        <iframe id="pdfIframe" src="" class="w-100 h-100" style="border: none; border-radius: 8px;" allow="fullscreen"></iframe>
                    </div>

                    <!-- Bottom helper link -->
                    <div class="d-flex justify-content-between align-items-center mt-2 px-2 flex-wrap gap-1">
                        <small class="text-white-50" style="font-size: 11px;">
                            <i class="fas fa-info-circle text-info me-1"></i> Multi-page presentation with full touch scroll & zoom
                        </small>
                        <small>
                            <a id="modalMobileFallbackLink" href="#" target="_blank"
                                class="text-warning text-decoration-underline fw-bold" style="font-size: 11.5px;">
                                <i class="fas fa-external-link-alt me-1"></i> Open in Full Browser Tab
                            </a>
                        </small>
                    </div>
                </div>

                <div class="modal-footer border-0 py-2 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: #0d111d;">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" class="btn btn-sm btn-outline-secondary text-white"
                            onclick="copyCurrentModalPdfUrl()">
                            <i class="far fa-copy me-1"></i> <span class="d-none d-sm-inline">Copy</span> Link
                        </button>
                        <a id="modalWaShareBtn" href="#" target="_blank" class="btn btn-sm btn-success">
                            <i class="fab fa-whatsapp me-1"></i> WhatsApp
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-warning d-inline-flex align-items-center" onclick="closePdfModalAndOpenSidebar()">
                            <i class="fas fa-bars me-1"></i> Menu
                        </button>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary d-flex align-items-center" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i> Back to Plans
                    </button>
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
            word-break: break-word;
            overflow-wrap: anywhere;
            min-width: 0;
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
            flex-shrink: 0;
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
            min-width: 0;
            word-break: break-word;
            overflow-wrap: anywhere;
        }

        .pdf-preview-box:hover {
            background: rgba(0, 0, 0, 0.55);
            border-color: rgba(245, 158, 11, 0.35);
            transform: scale(1.01);
        }

        .pdf-description-text {
            word-break: break-word;
            overflow-wrap: anywhere;
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

        /* Floating Mobile Navigation Widget */
        .mobile-floating-actions {
            position: fixed;
            bottom: 24px;
            right: 16px;
            z-index: 1005;
            background: rgba(10, 12, 18, 0.92);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 6px 10px;
            border-radius: 50px;
            border: 1px solid rgba(245, 158, 11, 0.4);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8), 0 0 15px rgba(245, 158, 11, 0.25);
        }

        @media (max-width: 768px) {
            .pdf-iframe-container {
                height: 58vh !important;
                min-height: 380px !important;
            }
            .pdf-preview-modal .modal-body {
                overflow-y: auto !important;
                -webkit-overflow-scrolling: touch !important;
                max-height: calc(100vh - 130px) !important;
            }
            .pdf-preview-modal .modal-header {
                padding: 10px 14px !important;
                background: #0d111d !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
            }
            .pdf-preview-modal .modal-footer {
                padding: 10px 14px !important;
                border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
            }
        }

        /* PDF.js Canvas Viewer & Scrollbars */
        .pdf-canvas-container {
            background: #080a11 !important;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.6);
            user-select: none;
            -webkit-user-select: none;
            cursor: grab;
        }

        .pdf-canvas-container:active {
            cursor: grabbing;
        }

        .pdf-page-card {
            background: #141722;
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: 8px;
            padding: 4px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
            margin: 0 auto;
        }

        .pdf-page-canvas {
            display: block;
            margin: 0 auto;
            border-radius: 6px;
            background: #fff;
        }

        /* Visible Golden Scrollbars for Vertical and Horizontal Scrolling */
        .pdf-canvas-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .pdf-canvas-container::-webkit-scrollbar-track {
            background: #0a0d16;
            border-radius: 4px;
        }

        .pdf-canvas-container::-webkit-scrollbar-thumb {
            background: rgba(245, 158, 11, 0.5);
            border-radius: 4px;
            border: 1px solid rgba(245, 158, 11, 0.8);
        }

        .pdf-canvas-container::-webkit-scrollbar-thumb:hover {
            background: #F59E0B;
        }
    </style>

    <!-- PDF.js Local Library for Mobile Touch Scrolling -->
    <script src="{{ asset('uassets/vendor/pdfjs/pdf.min.js') }}"></script>

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

            // Set iframe src as fallback
            document.getElementById('pdfIframe').src = livePdfUrl + '#toolbar=1&navpanes=0&view=FitH';

            // Show modal
            const modalEl = document.getElementById('pdfPreviewModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();

            // Load Touch-Scrollable PDF.js Canvas Viewer
            loadPdfWithPdfJs(livePdfUrl);

            // Clean up on modal close
            modalEl.addEventListener('hidden.bs.modal', function onModalHidden() {
                document.getElementById('pdfIframe').src = '';
                cancelActiveRenders();
                const listEl = document.getElementById('pdfPagesRenderList');
                if (listEl) listEl.innerHTML = '';
                currentPdfDoc = null;
                modalEl.removeEventListener('hidden.bs.modal', onModalHidden);
            });
        }

        // ============================================================
        // PDF.JS TOUCH-SCROLL ENGINE
        // ============================================================
        let currentPdfDoc = null;
        let currentPdfPage = 1;
        let totalPdfPages = 0;
        let currentPdfScale = 1.0;
        let currentPdfUrl = '';
        let activeRenderTasks = {};
        let isIframeMode = false;

        if (typeof pdfjsLib !== 'undefined') {
            pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('uassets/vendor/pdfjs/pdf.worker.min.js') }}";
        }

        function cancelActiveRenders() {
            Object.keys(activeRenderTasks).forEach(key => {
                try {
                    activeRenderTasks[key].cancel();
                } catch (e) {}
            });
            activeRenderTasks = {};
        }

        function loadPdfWithPdfJs(url) {
            currentPdfUrl = url;
            const loadingEl = document.getElementById('pdfCanvasLoading');
            const listEl = document.getElementById('pdfPagesRenderList');
            const canvasContainer = document.getElementById('pdfCanvasContainer');
            const pageIndicator = document.getElementById('pdfPageIndicator');

            if (loadingEl) loadingEl.style.display = 'block';
            if (listEl) listEl.innerHTML = '';
            currentPdfPage = 1;
            totalPdfPages = 0;
            if (pageIndicator) pageIndicator.innerText = 'Loading...';

            cancelActiveRenders();

            if (typeof pdfjsLib === 'undefined') {
                console.warn('PDF.js not loaded, switching to iframe');
                toggleViewerMode(true);
                return;
            }

            pdfjsLib.getDocument(url).promise.then(function(pdfDoc) {
                currentPdfDoc = pdfDoc;
                totalPdfPages = pdfDoc.numPages;
                if (loadingEl) loadingEl.style.display = 'none';
                updatePageIndicator();

                // Compute initial fit-to-width scale
                pdfDoc.getPage(1).then(function(page1) {
                    const viewport = page1.getViewport({ scale: 1.0 });
                    const containerWidth = canvasContainer.clientWidth > 40 ? canvasContainer.clientWidth - 24 : 360;
                    currentPdfScale = Math.max(0.4, (containerWidth / viewport.width));
                    updateZoomBadge('Fit');

                    buildPagePlaceholders();
                    renderVisiblePages();
                    setupDragToScroll();
                    setupScrollTracking();
                });
            }).catch(function(err) {
                console.warn('PDF.js error, falling back to iframe:', err);
                toggleViewerMode(true);
            });
        }

        function buildPagePlaceholders() {
            const listEl = document.getElementById('pdfPagesRenderList');
            if (!listEl || !currentPdfDoc) return;
            listEl.innerHTML = '';

            for (let pageNum = 1; pageNum <= totalPdfPages; pageNum++) {
                const pageCard = document.createElement('div');
                pageCard.className = 'pdf-page-card';
                pageCard.id = 'pdf-page-wrapper-' + pageNum;
                pageCard.setAttribute('data-page-num', pageNum);
                pageCard.style.position = 'relative';
                pageCard.style.minHeight = '250px';
                pageCard.style.display = 'flex';
                pageCard.style.flexDirection = 'column';
                pageCard.style.alignItems = 'center';
                pageCard.style.justifyContent = 'center';

                const pageLabel = document.createElement('div');
                pageLabel.className = 'pdf-page-label text-white-50 mb-1 fw-bold';
                pageLabel.style.fontSize = '10.5px';
                pageLabel.innerText = 'Page ' + pageNum + ' of ' + totalPdfPages;

                const canvas = document.createElement('canvas');
                canvas.id = 'pdf-canvas-' + pageNum;
                canvas.className = 'pdf-page-canvas shadow-sm';

                pageCard.appendChild(pageLabel);
                pageCard.appendChild(canvas);
                listEl.appendChild(pageCard);
            }
        }

        function renderPage(pageNum) {
            if (!currentPdfDoc || pageNum < 1 || pageNum > totalPdfPages) return;
            const canvas = document.getElementById('pdf-canvas-' + pageNum);
            const wrapper = document.getElementById('pdf-page-wrapper-' + pageNum);
            if (!canvas || !wrapper) return;

            if (activeRenderTasks[pageNum]) {
                try {
                    activeRenderTasks[pageNum].cancel();
                } catch (e) {}
                delete activeRenderTasks[pageNum];
            }

            currentPdfDoc.getPage(pageNum).then(function(page) {
                const dpr = Math.min(window.devicePixelRatio || 1, 2);
                const viewport = page.getViewport({ scale: currentPdfScale });

                canvas.width = Math.floor(viewport.width * dpr);
                canvas.height = Math.floor(viewport.height * dpr);
                canvas.style.width = Math.floor(viewport.width) + 'px';
                canvas.style.height = Math.floor(viewport.height) + 'px';

                wrapper.style.minHeight = Math.floor(viewport.height + 25) + 'px';
                wrapper.style.width = Math.floor(viewport.width + 12) + 'px';

                const ctx = canvas.getContext('2d');
                ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };

                const renderTask = page.render(renderContext);
                activeRenderTasks[pageNum] = renderTask;

                renderTask.promise.then(function() {
                    delete activeRenderTasks[pageNum];
                }).catch(function(err) {
                    if (err.name !== 'RenderingCancelledException') {
                        console.error('Error rendering page ' + pageNum, err);
                    }
                });
            });
        }

        function renderVisiblePages() {
            if (!currentPdfDoc) return;
            // Render first 3 pages immediately
            for (let p = 1; p <= Math.min(3, totalPdfPages); p++) {
                renderPage(p);
            }
            // Render remaining pages progressively
            if (totalPdfPages > 3) {
                let p = 4;
                function renderNext() {
                    if (p <= totalPdfPages && currentPdfDoc) {
                        renderPage(p);
                        p++;
                        setTimeout(renderNext, 70);
                    }
                }
                setTimeout(renderNext, 100);
            }
        }

        function pdfChangePage(delta) {
            let target = currentPdfPage + delta;
            if (target < 1) target = 1;
            if (target > totalPdfPages) target = totalPdfPages;
            if (target === currentPdfPage) return;
            pdfGoToPage(target);
        }

        function pdfGoToPage(pageNum) {
            if (pageNum < 1 || pageNum > totalPdfPages) return;
            currentPdfPage = pageNum;
            updatePageIndicator();
            const targetEl = document.getElementById('pdf-page-wrapper-' + pageNum);
            if (targetEl) {
                targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function updatePageIndicator() {
            const indicator = document.getElementById('pdfPageIndicator');
            if (indicator) {
                indicator.innerText = 'Page ' + currentPdfPage + ' / ' + (totalPdfPages || '--');
            }
            const prevBtn = document.getElementById('btnPdfPrev');
            const nextBtn = document.getElementById('btnPdfNext');
            if (prevBtn) prevBtn.disabled = (currentPdfPage <= 1);
            if (nextBtn) nextBtn.disabled = (currentPdfPage >= totalPdfPages);
        }

        function pdfZoomChange(delta) {
            let newScale = currentPdfScale + delta;
            if (newScale < 0.4) newScale = 0.4;
            if (newScale > 3.0) newScale = 3.0;
            currentPdfScale = Math.round(newScale * 10) / 10;
            updateZoomBadge();
            renderVisiblePages();
        }

        function pdfFitWidth() {
            if (!currentPdfDoc) return;
            const canvasContainer = document.getElementById('pdfCanvasContainer');
            currentPdfDoc.getPage(1).then(function(page1) {
                const viewport = page1.getViewport({ scale: 1.0 });
                const containerWidth = canvasContainer.clientWidth > 40 ? canvasContainer.clientWidth - 24 : 360;
                currentPdfScale = Math.max(0.4, (containerWidth / viewport.width));
                updateZoomBadge('Fit');
                renderVisiblePages();
                canvasContainer.scrollLeft = 0;
            });
        }

        function updateZoomBadge(customText) {
            const badge = document.getElementById('pdfZoomBadge');
            if (badge) {
                if (customText) {
                    badge.innerText = customText;
                } else {
                    badge.innerText = Math.round(currentPdfScale * 100) + '%';
                }
            }
        }

        function setupScrollTracking() {
            const container = document.getElementById('pdfCanvasContainer');
            if (!container) return;

            let scrollTimeout;
            container.addEventListener('scroll', function() {
                if (scrollTimeout) clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function() {
                    const containerTop = container.scrollTop;
                    const containerCenter = containerTop + (container.clientHeight / 3);
                    let closestPage = 1;
                    let minDiff = Infinity;

                    for (let p = 1; p <= totalPdfPages; p++) {
                        const el = document.getElementById('pdf-page-wrapper-' + p);
                        if (el) {
                            const elTop = el.offsetTop - container.offsetTop;
                            const diff = Math.abs(elTop - containerCenter);
                            if (diff < minDiff) {
                                minDiff = diff;
                                closestPage = p;
                            }
                        }
                    }
                    if (closestPage !== currentPdfPage) {
                        currentPdfPage = closestPage;
                        updatePageIndicator();
                    }
                }, 50);
            }, { passive: true });
        }

        function setupDragToScroll() {
            const container = document.getElementById('pdfCanvasContainer');
            if (!container) return;

            let isDragging = false;
            let startX, startY, scrollLeft, scrollTop;

            container.addEventListener('mousedown', function(e) {
                isDragging = true;
                container.style.cursor = 'grabbing';
                startX = e.pageX - container.offsetLeft;
                startY = e.pageY - container.offsetTop;
                scrollLeft = container.scrollLeft;
                scrollTop = container.scrollTop;
            });

            window.addEventListener('mouseup', function() {
                if (isDragging) {
                    isDragging = false;
                    container.style.cursor = 'grab';
                }
            });

            container.addEventListener('mousemove', function(e) {
                if (!isDragging) return;
                e.preventDefault();
                const x = e.pageX - container.offsetLeft;
                const y = e.pageY - container.offsetTop;
                const walkX = (x - startX) * 1.5;
                const walkY = (y - startY) * 1.5;
                container.scrollLeft = scrollLeft - walkX;
                container.scrollTop = scrollTop - walkY;
            });
        }

        function toggleViewerMode(forceIframe) {
            const canvasContainer = document.getElementById('pdfCanvasContainer');
            const touchToolbar = document.getElementById('pdfTouchToolbar');
            const iframeWrapper = document.getElementById('pdfIframeWrapper');
            const toggleBtn = document.getElementById('btnViewerMode');

            if (forceIframe || !isIframeMode) {
                isIframeMode = true;
                if (canvasContainer) canvasContainer.style.display = 'none';
                if (iframeWrapper) {
                    iframeWrapper.style.display = 'block';
                    document.getElementById('pdfIframe').src = currentPdfUrl + '#toolbar=1&navpanes=0&view=FitH';
                }
                if (toggleBtn) {
                    toggleBtn.innerHTML = '<i class="fas fa-mobile-alt me-1"></i> <span class="d-none d-sm-inline">Touch Mode</span>';
                    toggleBtn.className = 'btn btn-sm btn-warning text-dark fw-bold px-2 py-1';
                }
            } else {
                isIframeMode = false;
                if (iframeWrapper) {
                    iframeWrapper.style.display = 'none';
                    document.getElementById('pdfIframe').src = '';
                }
                if (canvasContainer) canvasContainer.style.display = 'block';
                if (toggleBtn) {
                    toggleBtn.innerHTML = '<i class="fas fa-desktop"></i>';
                    toggleBtn.className = 'btn btn-sm btn-outline-secondary text-white px-2 py-1';
                }
                if (!currentPdfDoc && currentPdfUrl) {
                    loadPdfWithPdfJs(currentPdfUrl);
                }
            }
        }

        function closePdfModalAndOpenSidebar() {
            const modalEl = document.getElementById('pdfPreviewModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            }
            setTimeout(() => {
                $('.nav-control').first().trigger('click');
            }, 300);
        }

        function copyCurrentModalPdfUrl() {
            const pdf = staticPdfs[activeModalPdfIndex];
            if (pdf) {
                copyPdfUrl(pdf.url);
            }
        }
    </script>

@endsection
