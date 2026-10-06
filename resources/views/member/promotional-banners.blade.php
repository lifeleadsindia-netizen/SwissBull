@extends('member.layouts.main')
@section('title', 'Promotion Banners')
@section('container')

<div class="content-body">
    <div class="container-fluid pt-2 pb-5" style="padding-bottom: 80px !important;">
        <!-- Page Header -->
        <div class="page-titles mb-3">
            <div class="welcome-text">
                <h4 class="text-white font-weight-bold mb-1">Promotion Banners</h4>
                <p class="mb-0 text-muted" style="font-size: 13px;">Official high-quality promotional creatives to share on WhatsApp, Telegram & social media with your referral link.</p>
            </div>
            <div class="justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/member/dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Promotion Banners</a></li>
                </ol>
            </div>
        </div>

        <!-- Hero & Referral Link Section -->
        <div class="promo-hero-card mb-4 p-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-7 col-md-12">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge promo-badge me-2">
                            <i class="fas fa-bullhorn me-1"></i> Marketing Kit
                        </span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 11px;">
                            <i class="fas fa-check-circle me-1"></i> {{ count($banners) }} Official Banners
                        </span>
                    </div>
                    <h3 class="text-white fw-bold mb-2">Boost Your Referrals with Official Banners</h3>
                    <p class="text-light-50 mb-3" style="font-size: 13.5px; line-height: 1.6; color: rgba(255,255,255,0.75);">
                        Download any of the official promotional posters below, post them in your WhatsApp groups, Telegram channels, Facebook, and Instagram stories, and share your personal referral link to earn commissions!
                    </p>

                    <!-- Referral Link Bar -->
                    <div class="referral-bar-wrapper p-2 rounded-3">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <div class="flex-grow-1 position-relative" style="min-width: 220px;">
                                <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted" style="font-size: 13px;">
                                    <i class="fas fa-link text-warning"></i>
                                </span>
                                <input type="text" id="memberReferralInput" class="form-control referral-input ps-5" value="{{ $referralLink }}" readonly>
                            </div>
                            <button type="button" class="btn btn-warning btn-sm px-3 fw-bold d-flex align-items-center" onclick="copyReferralLink()">
                                <i class="far fa-copy me-1"></i> <span id="copyBtnText">Copy Link</span>
                            </button>
                            <a href="https://wa.me/?text={{ rawurlencode("🔥 Discover Math Wallet - The Next-Gen Decentralized Platform! 🚀\n\nJoin my team and start earning rewards today:\n👉 " . $referralLink . "\n\nRegister now and start your journey!") }}" 
                               target="_blank" class="btn btn-success btn-sm px-3 fw-bold d-flex align-items-center">
                                <i class="fab fa-whatsapp me-1"></i> Share
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 col-md-12 text-center text-lg-end">
                    <div class="promo-quick-tips p-3 rounded-3 text-start d-inline-block w-100" style="max-width: 420px;">
                        <h6 class="text-white fw-bold mb-2 d-flex align-items-center" style="font-size: 13px;">
                            <i class="fas fa-lightbulb text-warning me-2"></i> Quick Sharing Guide
                        </h6>
                        <ul class="list-unstyled mb-0 text-white-50" style="font-size: 12px; line-height: 1.8;">
                            <li><i class="fas fa-check text-success me-1"></i> <strong>Download</strong> the banner in full HD resolution.</li>
                            <li><i class="fas fa-check text-success me-1"></i> <strong>Attach</strong> image with your referral link on social media.</li>
                            <li><i class="fas fa-check text-success me-1"></i> <strong>Share</strong> directly to WhatsApp with pre-written message.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banners Grid Section Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <h5 class="text-white fw-bold mb-0">Official Banners Gallery</h5>
                <span class="text-muted" style="font-size: 12px;">Showing all {{ count($banners) }} official high-resolution banners</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-dark border border-secondary px-3 py-2 text-white-50" style="font-size: 12px;">
                    <i class="fas fa-images text-info me-1"></i> Format: JPEG / Ultra HD
                </span>
            </div>
        </div>

        <!-- Banners Grid -->
        <div class="row g-4 mb-5">
            @forelse ($banners as $index => $banner)
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                    <div class="promo-banner-card h-100 d-flex flex-column">
                        <!-- Image Container with Hover Action -->
                        <div class="promo-img-wrapper position-relative overflow-hidden" onclick="openLightbox({{ $index }})">
                            <img src="{{ $banner['url'] }}" alt="{{ $banner['title'] }}" class="img-fluid promo-banner-img" loading="lazy">
                            
                            <!-- Badges -->
                            <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-1">
                                <span class="badge badge-tag">{{ $banner['tag'] }}</span>
                            </div>
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge badge-number">#{{ $banner['id'] }}</span>
                            </div>

                            <!-- Overlay on Hover -->
                            <div class="promo-img-overlay d-flex align-items-center justify-content-center gap-2">
                                <button type="button" class="btn btn-light btn-sm rounded-circle p-2 shadow" title="Preview Fullscreen" onclick="event.stopPropagation(); openLightbox({{ $index }});">
                                    <i class="fas fa-search-plus text-dark"></i>
                                </button>
                                <a href="{{ $banner['download_url'] }}" class="btn btn-success btn-sm rounded-circle p-2 shadow" title="Download Banner" onclick="event.stopPropagation();">
                                    <i class="fas fa-download text-white"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="promo-card-body p-3 flex-grow-1 d-flex flex-column justify-content-between">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="text-white fw-bold mb-0" style="font-size: 15px;">{{ $banner['title'] }}</h6>
                                    <small class="text-muted" style="font-size: 11px;">{{ $banner['filename'] }}</small>
                                </div>
                                <p class="text-muted mb-0" style="font-size: 12px;">High-resolution promotional banner for community sharing and invitations.</p>
                            </div>

                            <!-- Actions Row -->
                            <div class="promo-card-actions pt-2 border-top border-secondary-subtle">
                                <div class="d-flex align-items-center gap-2">
                                    <!-- Direct Download Button -->
                                    <a href="{{ $banner['download_url'] }}" class="btn btn-download flex-grow-1 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-download me-1"></i> Download HD
                                    </a>

                                    <!-- Direct WhatsApp Share Button with Referral Link -->
                                    <a href="https://wa.me/?text={{ rawurlencode("🔥 *Math Wallet Official Update* 🚀\n\nTake your crypto portfolio to the next level with our decentralized system!\n\n👉 *Join using my referral link:* " . $referralLink . "\n\nCheck out our official banner: " . $banner['url']) }}" 
                                       target="_blank" 
                                       class="btn btn-wa-share" 
                                       title="Share to WhatsApp with Referral Link">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>

                                    <!-- Copy Image Link -->
                                    <button type="button" 
                                            class="btn btn-copy-url" 
                                            title="Copy Banner Image URL" 
                                            onclick="copyImageUrl('{{ $banner['url'] }}', this)">
                                        <i class="fas fa-link"></i>
                                    </button>

                                    <!-- Preview Button -->
                                    <button type="button" 
                                            class="btn btn-preview-btn" 
                                            title="Preview Fullscreen" 
                                            onclick="openLightbox({{ $index }})">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.15);">
                        <i class="fas fa-images text-muted mb-3" style="font-size: 40px;"></i>
                        <h5 class="text-white fw-bold mb-2">No Promotional Banners Available</h5>
                        <p class="text-white-50 mb-0" style="font-size: 13.5px;">New promotional creatives will appear here once published by the administration.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- LIGHTBOX MODAL -->
<!-- ============================================================ -->
<div class="modal fade promo-lightbox-modal" id="bannerLightboxModal" tabindex="-1" aria-labelledby="bannerLightboxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge badge-tag" id="lightboxTag">Math Wallet</span>
                    <h5 class="modal-title text-white fw-bold mb-0" id="lightboxTitle">Banner Preview</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body text-center p-3 position-relative">
                <!-- Navigation Arrows -->
                <button type="button" class="lightbox-nav-btn prev-btn" onclick="prevBanner()" title="Previous Banner">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="lightbox-img-frame mx-auto">
                    <img id="lightboxImage" src="" alt="Banner Preview" class="img-fluid rounded-3 shadow-lg">
                </div>

                <button type="button" class="lightbox-nav-btn next-btn" onclick="nextBanner()" title="Next Banner">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="modal-footer border-0 pt-0 d-flex justify-content-between flex-wrap gap-2">
                <div class="text-start">
                    <span class="text-white-50" style="font-size: 12px;">Banner <span id="lightboxCounter">1</span> of {{ count($banners) }}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-light" onclick="copyLightboxImageUrl()">
                        <i class="fas fa-link me-1"></i> Copy Image Link
                    </button>
                    <a id="lightboxWaBtn" href="#" target="_blank" class="btn btn-sm btn-success fw-bold">
                        <i class="fab fa-whatsapp me-1"></i> WhatsApp Share
                    </a>
                    <a id="lightboxDownloadBtn" href="#" class="btn btn-sm btn-download fw-bold">
                        <i class="fas fa-download me-1"></i> Download Banner
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================================
   Promotion Banners STYLES
   ============================================================ */
.promo-hero-card {
    background: linear-gradient(135deg, #0C0F17 0%, #1A1408 40%, #362203 80%, #683F06 120%);
    border: 1px solid rgba(245, 158, 11, 0.35);
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.65), 0 0 20px rgba(245, 158, 11, 0.12);
    position: relative;
    overflow: hidden;
}

.promo-hero-card::before {
    content: '';
    position: absolute;
    top: -50px;
    right: -50px;
    width: 250px;
    height: 250px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
    pointer-events: none;
}

.promo-badge {
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

.promo-quick-tips {
    background: rgba(245, 158, 11, 0.03);
    border: 1px dashed rgba(245, 158, 11, 0.25);
}

/* Banner Card */
.promo-banner-card {
    background: #0C0E14;
    border: 1px solid rgba(245, 158, 11, 0.18);
    border-radius: 14px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
}

.promo-banner-card:hover {
    transform: translateY(-5px);
    border-color: rgba(245, 158, 11, 0.4);
    box-shadow: 0 12px 28px rgba(245, 158, 11, 0.15), 0 6px 18px rgba(0, 0, 0, 0.6);
}

.promo-img-wrapper {
    background: #08090C;
    min-height: 260px;
    max-height: 320px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.promo-banner-img {
    width: 100%;
    height: 280px;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.promo-banner-card:hover .promo-banner-img {
    transform: scale(1.04);
}

.badge-tag {
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(8px);
    color: #10B981;
    border: 1px solid rgba(16, 185, 129, 0.3);
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 600;
}

.badge-number {
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(8px);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    font-weight: 700;
}

.promo-img-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(8, 9, 12, 0.6);
    backdrop-filter: blur(2px);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.promo-img-wrapper:hover .promo-img-overlay {
    opacity: 1;
}

/* Card Actions */
.btn-download {
    background: linear-gradient(135deg, #F59E0B 0%, #10B981 100%);
    color: #08090C;
    font-size: 12.5px;
    font-weight: 800;
    border: none;
    padding: 7px 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn-download:hover {
    background: linear-gradient(135deg, #FBBF24 0%, #34D399 100%);
    color: #08090C;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
}

.btn-wa-share {
    background: rgba(37, 211, 102, 0.15);
    color: #25d366;
    border: 1px solid rgba(37, 211, 102, 0.35);
    padding: 7px 12px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn-wa-share:hover {
    background: #25d366;
    color: #ffffff;
    border-color: #25d366;
    transform: translateY(-1px);
}

.btn-copy-url, .btn-preview-btn {
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.8);
    border: 1px solid rgba(255, 255, 255, 0.12);
    padding: 7px 11px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn-copy-url:hover, .btn-preview-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.25);
    transform: translateY(-1px);
}

/* Lightbox Modal */
.promo-lightbox-modal .modal-content {
    background: #111726;
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
}

.lightbox-img-frame {
    max-height: 65vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #090d16;
    border-radius: 12px;
    padding: 8px;
    overflow: hidden;
}

.lightbox-img-frame img {
    max-height: 62vh;
    width: auto;
    object-fit: contain;
}

.lightbox-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.65);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.2s ease;
}

.lightbox-nav-btn:hover {
    background: rgba(0, 230, 118, 0.8);
    color: #000;
    border-color: #00e676;
}

.prev-btn {
    left: 12px;
}

.next-btn {
    right: 12px;
}
</style>

<script>
// Static Banners Data passed safely to JS
const staticBanners = @json($banners);
const memberReferralUrl = "{{ $referralLink }}";
let currentLightboxIndex = 0;

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
            link.href = link.href.replace(/https?%3A%2F%2F(localhost|127\.0\.0\.1)(%3A\d+)?/gi, encodedLiveOrigin);
            link.href = link.href.replace(/https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?/gi, liveOrigin);
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

function copyImageUrl(url, btnElement) {
    const liveUrl = getLiveUrl(url);
    navigator.clipboard.writeText(liveUrl).then(() => {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Banner image link copied!',
                showConfirmButton: false,
                timer: 2000,
                background: '#161c2d',
                color: '#fff'
            });
        } else {
            alert('Banner link copied to clipboard!');
        }
    });
}

function openLightbox(index) {
    if (index < 0 || index >= staticBanners.length) return;
    currentLightboxIndex = index;
    updateLightboxUI();

    const modalEl = document.getElementById('bannerLightboxModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function updateLightboxUI() {
    const banner = staticBanners[currentLightboxIndex];
    if (!banner) return;

    document.getElementById('lightboxTitle').innerText = banner.title;
    document.getElementById('lightboxTag').innerText = banner.tag || 'Math Wallet';
    document.getElementById('lightboxImage').src = getLiveUrl(banner.url);
    document.getElementById('lightboxCounter').innerText = currentLightboxIndex + 1;
    document.getElementById('lightboxDownloadBtn').href = getLiveUrl(banner.download_url);

    // WhatsApp Share Link with Live URL
    const liveBannerUrl = getLiveUrl(banner.url);
    const liveRefUrl = getLiveUrl(memberReferralUrl);
    const waText = "🔥 *Math Wallet Official Update* 🚀\n\nTake your crypto portfolio to the next level with our decentralized system!\n\n👉 *Join using my referral link:* " + liveRefUrl + "\n\nCheck out our official banner: " + liveBannerUrl;
    document.getElementById('lightboxWaBtn').href = "https://wa.me/?text=" + encodeURIComponent(waText);
}

function prevBanner() {
    currentLightboxIndex = (currentLightboxIndex - 1 + staticBanners.length) % staticBanners.length;
    updateLightboxUI();
}

function nextBanner() {
    currentLightboxIndex = (currentLightboxIndex + 1) % staticBanners.length;
    updateLightboxUI();
}

function copyLightboxImageUrl() {
    const banner = staticBanners[currentLightboxIndex];
    if (banner) {
        copyImageUrl(banner.url);
    }
}

// Keyboard arrow navigation for lightbox
document.addEventListener('keydown', function(e) {
    const modalEl = document.getElementById('bannerLightboxModal');
    if (modalEl && modalEl.classList.contains('show')) {
        if (e.key === 'ArrowLeft') {
            prevBanner();
        } else if (e.key === 'ArrowRight') {
            nextBanner();
        }
    }
});
</script>

@endsection
