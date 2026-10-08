@extends('member.layouts.main')
@section('title', 'Plan Video')
@section('container')

    <div class="content-body">
        <div class="container-fluid pt-2 pb-5" style="padding-bottom: 80px !important;">
            <!-- Page Header -->
            <div class="page-titles mb-3">
                <div class="welcome-text">
                    <h4 class="text-white font-weight-bold mb-1">Plan Videos</h4>
                    <p class="mb-0 text-muted" style="font-size: 13px;">Official video presentations explaining the complete
                        SYNC TRADE business plan, staking, and ecosystem benefits.</p>
                </div>
                <div class="justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/member/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Plan Video</a></li>
                    </ol>
                </div>
            </div>

            @if (! $selectedVideo)
                <!-- Empty State -->
                <div class="row">
                    <div class="col-12 text-center py-5">
                        <div class="p-5 rounded-4 side-panel-card text-center" style="border: 1px dashed rgba(255,255,255,0.2);">
                            <i class="fas fa-video-slash text-muted mb-3" style="font-size: 48px;"></i>
                            <h4 class="text-white fw-bold mb-2">No Plan Videos Available Yet</h4>
                            <p class="text-white-50 mb-0" style="font-size: 14px;">Official business plan presentation videos will appear here once published by admin.</p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Video Theater & Side Info Section -->
                <div class="row g-4 mb-4" id="videoTheaterSection">
                    <!-- Left: Main Video Player Card -->
                    <div class="col-xl-8 col-lg-7 col-md-12">
                        <div class="video-theater-card p-3 p-md-4">
                            <!-- Video Card Header -->
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge video-badge">
                                        <i class="fas fa-play-circle me-1"></i> <span id="theaterTag">{{ $video['tag'] }}</span>
                                    </span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1"
                                        style="font-size: 11px;">
                                        <i class="fas fa-film me-1"></i> HD 1080p
                                    </span>
                                </div>
                                <span class="text-white-50" style="font-size: 12px;" id="theaterDuration">
                                    <i class="fas fa-clock text-warning me-1"></i> {{ $video['duration'] }}
                                </span>
                            </div>

                            <!-- Video Container (Responsive 16:9 Aspect Ratio) -->
                            <div class="video-player-wrapper rounded-3 overflow-hidden shadow-lg position-relative mb-3" style="background: #000;">
                                @if (!empty($video['is_youtube']) && !empty($video['embed_url']))
                                    <div class="ratio ratio-16x9" style="min-height: 280px; max-height: 520px; aspect-ratio: 16/9;">
                                        <iframe id="mainYoutubeIframe" src="{{ $video['embed_url'] }}" 
                                            title="{{ $video['title'] }}" 
                                            class="w-100 h-100" 
                                            style="border: 0; min-height: 280px;" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                    <video id="mainPlanVideo" controls playsinline preload="metadata"
                                        class="w-100 h-100 main-video-element d-none"
                                        style="max-height: 520px; background: #000; border-radius: 12px;">
                                        <source id="mainVideoSource" src="" type="video/mp4">
                                    </video>
                                @else
                                    <video id="mainPlanVideo" controls playsinline preload="metadata"
                                        poster="{{ $video['thumbnail_url'] ?? ($selectedVideo ? $selectedVideo->thumbnail_url : asset('uassets/images/default-video-thumbnail.png')) }}"
                                        class="w-100 h-100 main-video-element"
                                        style="max-height: 520px; background: #000; display: block; border-radius: 12px; width: 100%;">
                                        <source id="mainVideoSource" src="{{ $video['url'] }}" type="video/mp4">
                                        <p class="text-white p-4 text-center">
                                            Your browser does not support HTML5 video playback.
                                            @if($video['can_download'])
                                                <a href="{{ $video['download_url'] }}" class="btn btn-warning btn-sm ms-2">Download Video</a>
                                            @endif
                                        </p>
                                    </video>
                                    <div class="ratio ratio-16x9 d-none" id="youtubeIframeContainer" style="aspect-ratio: 16/9;">
                                        <iframe id="mainYoutubeIframe" src="" class="w-100 h-100" style="border: 0;" allowfullscreen></iframe>
                                    </div>
                                @endif
                            </div>

                            <!-- Video Title & Description -->
                            <div class="mb-3">
                                <h4 class="text-white fw-bold mb-2" id="theaterTitle" style="font-size: 19px;">{{ $video['title'] }}</h4>
                                <p class="text-white-50 mb-0" id="theaterDesc" style="font-size: 13px; line-height: 1.6;">
                                    {{ $video['description'] }}
                                </p>
                            </div>

                            <!-- Video Action Buttons Row -->
                            <div class="video-actions-bar p-3 rounded-3 mt-3">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <!-- Download HD Video Button (if file upload) -->
                                    <div id="downloadBtnContainer">
                                        @if ($video['can_download'])
                                            <a href="{{ $video['download_url'] }}" id="theaterDownloadBtn"
                                                class="btn btn-download-video d-flex align-items-center fw-bold">
                                                <i class="fas fa-download me-2"></i> Download Video (HD)
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Secondary Actions -->
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <!-- Open in New Tab -->
                                        <a href="{{ $video['url'] }}" id="theaterNewTabBtn" target="_blank"
                                            class="btn btn-sm btn-outline-secondary text-white-50" style="font-size: 12px;">
                                            <i class="fas fa-external-link-alt me-1"></i> Fullscreen Tab
                                        </a>

                                        <!-- Copy Video URL -->
                                        <button type="button" class="btn btn-sm btn-outline-secondary text-white-50"
                                            style="font-size: 12px;" onclick="copyVideoUrl(currentPlayingUrl)">
                                            <i class="far fa-copy me-1"></i> Copy Video Link
                                        </button>

                                        <!-- Share on WhatsApp with Referral Link -->
                                        <a href="https://wa.me/?text={{ rawurlencode("🎬 *Watch SYNC TRADE Official Plan Presentation Video* 🚀\n\nUnderstand the complete system and start earning:\n👉 Watch Video: " . $video['url'] . "\n\n👉 Register & Join my team:\n" . $referralLink) }}"
                                            id="theaterWaShareBtn"
                                            target="_blank" class="btn btn-sm btn-success fw-bold d-flex align-items-center"
                                            style="font-size: 12px;">
                                            <i class="fab fa-whatsapp me-1"></i> Share on WhatsApp
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Referral Bar & Video Information Sidebar -->
                    <div class="col-xl-4 col-lg-5 col-md-12">
                        <div class="d-flex flex-column gap-4">
                            <!-- Quick Referral Link Card -->
                            <div class="side-panel-card p-3 p-xl-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="text-white fw-bold mb-0" style="font-size: 14px;">
                                        <i class="fas fa-share-alt text-warning me-2"></i> Your Referral Link
                                    </h6>
                                    <span class="badge bg-warning-subtle text-warning" style="font-size: 10.5px;">Active</span>
                                </div>
                                <p class="text-white-50 mb-3" style="font-size: 12px; line-height: 1.5;">
                                    Send this link along with the video presentation so new members register directly under your
                                    downline.
                                </p>

                                <div class="position-relative mb-2">
                                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted"
                                        style="font-size: 13px;">
                                        <i class="fas fa-link text-warning"></i>
                                    </span>
                                    <input type="text" id="memberReferralInput" class="form-control referral-input ps-5"
                                        value="{{ $referralLink }}" readonly>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="button"
                                        class="btn btn-warning btn-sm fw-bold d-flex align-items-center justify-content-center"
                                        onclick="copyReferralLink()">
                                        <i class="far fa-copy me-1"></i> <span id="copyBtnText">Copy My Referral Link</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Video Library Stats Card -->
                            <div class="side-panel-card p-3 p-xl-4">
                                <h6 class="text-white fw-bold mb-3 d-flex align-items-center" style="font-size: 14px;">
                                    <i class="fas fa-info-circle text-info me-2"></i> Plan Videos Library
                                </h6>
                                <ul class="list-unstyled mb-0 d-flex flex-column gap-2 text-white-50" style="font-size: 12.5px;">
                                    <li class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary-subtle">
                                        <span><i class="fas fa-film me-2 text-muted"></i> Total Plan Videos</span>
                                        <span class="badge bg-primary">{{ count($allVideos) }} Videos</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary-subtle">
                                        <span><i class="fas fa-tv me-2 text-muted"></i> Video Quality</span>
                                        <span class="badge bg-success-subtle text-success">1080p HD Streaming</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center py-1">
                                        <span><i class="fas fa-mobile-alt me-2 text-muted"></i> Mobile Ready</span>
                                        <span class="text-white fw-bold">Touch Optimized</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ALL PLAN VIDEOS GALLERY / PLAYLIST SECTION -->
                <div class="mt-4 pt-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <div>
                            <h5 class="text-white fw-bold mb-0">
                                <i class="fas fa-th-large text-warning me-2"></i> Plan Videos Playlist
                            </h5>
                            <span class="text-muted" style="font-size: 12px;">Showing {{ count($allVideos) }} official presentations & updates. Click any video to play.</span>
                        </div>
                    </div>

                    <!-- Videos Grid -->
                    <div class="row g-3">
                        @foreach ($videos as $v)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                                <div class="side-panel-card video-card-item h-100 d-flex flex-column overflow-hidden {{ $selectedVideo && $selectedVideo->id === $v->id ? 'active-playing-card' : '' }}" 
                                     id="video-card-{{ $v->id }}"
                                     onclick="playVideoDirect({{ json_encode([
                                         'id' => $v->id,
                                         'title' => $v->title,
                                         'tag' => $v->tag ?: 'Official Presentation',
                                         'duration' => $v->duration ?: 'Full Video',
                                         'url' => $v->video_play_url,
                                         'embed_url' => $v->embed_url,
                                         'is_youtube' => $v->isYouTube(),
                                         'download_url' => route('member.plan-video.download', ['filename' => $v->id]),
                                         'description' => $v->description,
                                         'can_download' => $v->canDownload(),
                                         'thumbnail_url' => $v->thumbnail_url,
                                     ]) }})">
                                    <!-- Thumbnail with Play Overlay -->
                                    <div class="position-relative overflow-hidden video-thumb-box" style="aspect-ratio: 16/9; background: #0b0f19;">
                                        <img src="{{ $v->thumbnail_url }}" alt="{{ $v->title }}" class="w-100 h-100 video-card-thumb" style="object-fit: cover;" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('uassets/images/default-video-thumbnail.png') }}';">
                                        
                                        <!-- Play overlay button -->
                                        <div class="video-play-overlay d-flex align-items-center justify-content-center">
                                            <div class="play-circle-icon rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="fas fa-play text-white" style="margin-left: 2px;"></i>
                                            </div>
                                        </div>

                                        <!-- Tag badge -->
                                        @if($v->tag)
                                            <span class="position-absolute top-0 start-0 m-2 badge bg-dark bg-opacity-75 text-white border border-secondary" style="font-size: 10px;">
                                                {{ $v->tag }}
                                            </span>
                                        @endif

                                        <!-- Duration Badge -->
                                        <span class="position-absolute bottom-0 end-0 m-2 badge bg-black bg-opacity-75 text-white" style="font-size: 10.5px;">
                                            <i class="far fa-clock me-1"></i> {{ $v->duration ?: 'Video' }}
                                        </span>
                                    </div>

                                    <!-- Card Content -->
                                    <div class="p-3 d-flex flex-column justify-content-between flex-grow-1">
                                        <div>
                                            <h6 class="text-white fw-bold mb-1 video-card-title" style="font-size: 14px; line-height: 1.4;">
                                                {{ $v->title }}
                                            </h6>
                                            @if ($v->description)
                                                <p class="text-muted mb-2" style="font-size: 12px; line-height: 1.4;">
                                                    {{ \Illuminate\Support\Str::limit($v->description, 75) }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-secondary-subtle mt-2">
                                            @if ($v->video_source === 'url' && $v->isYouTube())
                                                <span class="video-source-pill pill-youtube">
                                                    <i class="fab fa-youtube text-danger"></i> YouTube
                                                </span>
                                            @else
                                                <span class="video-source-pill pill-mp4">
                                                    <i class="fas fa-file-video text-warning"></i> {{ strtoupper($v->video_file ? pathinfo($v->video_file, PATHINFO_EXTENSION) : 'HD') }} Video
                                                </span>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2" style="font-size: 11px; border-radius: 6px;">
                                                <i class="fas fa-play me-1"></i> Watch Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if ($videos->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $videos->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Generous Bottom Gap Buffer -->
            <div class="pb-5 mb-5" style="height: 60px;"></div>
        </div>
    </div>

    <style>
        .video-theater-card {
            background: linear-gradient(135deg, #0C0F17 0%, #1A1408 40%, #362203 80%, #683F06 120%);
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.65), 0 0 20px rgba(245, 158, 11, 0.12);
        }

        .video-badge {
            background: rgba(245, 158, 11, 0.18);
            color: #FBBF24;
            border: 1px solid rgba(245, 158, 11, 0.35);
            font-size: 11.5px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .video-player-wrapper {
            background: #000;
            border: 1px solid rgba(245, 158, 11, 0.20);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.7);
        }

        .main-video-element {
            outline: none;
        }

        .video-actions-bar {
            background: rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(245, 158, 11, 0.18);
        }

        .btn-download-video {
            background: linear-gradient(135deg, #F59E0B 0%, #10B981 100%);
            color: #08090C;
            font-size: 13px;
            font-weight: 800;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-download-video:hover {
            background: linear-gradient(135deg, #FBBF24 0%, #34D399 100%);
            color: #08090C;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4);
        }

        .side-panel-card {
            background: #0C0E14;
            border: 1px solid rgba(245, 158, 11, 0.18);
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
            transition: all 0.2s ease;
        }

        .side-panel-card:hover {
            border-color: rgba(245, 158, 11, 0.4);
        }

        .video-card-item {
            cursor: pointer;
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .video-card-item:hover {
            transform: translateY(-4px);
            border-color: #f59e0b !important;
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.25);
        }

        .active-playing-card {
            border-color: #f59e0b !important;
            background: rgba(245, 158, 11, 0.10) !important;
            box-shadow: 0 0 16px rgba(245, 158, 11, 0.35);
        }

        .video-card-thumb {
            transition: transform 0.3s ease;
        }

        .video-card-item:hover .video-card-thumb {
            transform: scale(1.05);
        }

        .video-play-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            opacity: 0.85;
            transition: opacity 0.2s ease, background 0.2s ease;
        }

        .video-card-item:hover .video-play-overlay {
            opacity: 1;
            background: rgba(0, 0, 0, 0.2);
        }

        .play-circle-icon {
            width: 44px;
            height: 44px;
            background: rgba(245, 158, 11, 0.9);
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.6);
            color: #08090C;
            transition: transform 0.2s ease;
        }

        .video-card-item:hover .play-circle-icon {
            transform: scale(1.15);
            background: #10B981;
            box-shadow: 0 0 15px rgba(16, 185, 129, 0.8);
            color: #08090C;
        }

        .video-card-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .referral-input {
            background: rgba(8, 9, 12, 0.8) !important;
            border: 1px solid rgba(245, 158, 11, 0.25) !important;
            color: #10B981 !important;
            font-size: 13px !important;
            font-weight: 600;
            border-radius: 8px;
        }

        .video-source-pill {
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            line-height: 1 !important;
            padding: 5px 10px !important;
            border-radius: 20px !important;
            white-space: nowrap !important;
            text-decoration: none !important;
            letter-spacing: 0.3px;
        }

        .video-source-pill.pill-youtube {
            background: rgba(239, 68, 68, 0.15) !important;
            border: 1px solid rgba(239, 68, 68, 0.35) !important;
            color: #fca5a5 !important;
        }

        .video-source-pill.pill-mp4 {
            background: rgba(245, 158, 11, 0.15) !important;
            border: 1px solid rgba(245, 158, 11, 0.35) !important;
            color: #FBBF24 !important;
        }
    </style>

    <script>
        const memberReferralUrl = "{{ $referralLink }}";
        let currentPlayingUrl = "{{ $video['url'] ?? '' }}";

        function playVideoDirect(item) {
            currentPlayingUrl = item.url;

            // Update UI Title, Tag, Duration, Desc
            document.getElementById('theaterTitle').innerText = item.title;
            document.getElementById('theaterTag').innerText = item.tag;
            document.getElementById('theaterDuration').innerHTML = '<i class="fas fa-clock text-warning me-1"></i> ' + item.duration;
            document.getElementById('theaterDesc').innerText = item.description || '';

            // Update Active Card Highlights
            document.querySelectorAll('.video-card-item').forEach(c => c.classList.remove('active-playing-card'));
            const activeCard = document.getElementById('video-card-' + item.id);
            if (activeCard) {
                activeCard.classList.add('active-playing-card');
            }

            // Switch Player (YouTube iframe vs HTML5 Video)
            const html5Player = document.getElementById('mainPlanVideo');
            const ytIframe = document.getElementById('mainYoutubeIframe');

            if (item.is_youtube && item.embed_url) {
                if (html5Player) {
                    html5Player.pause();
                    html5Player.classList.add('d-none');
                }
                if (ytIframe) {
                    ytIframe.src = item.embed_url + (item.embed_url.includes('?') ? '&autoplay=1' : '?autoplay=1');
                    ytIframe.classList.remove('d-none');
                    if (document.getElementById('youtubeIframeContainer')) {
                        document.getElementById('youtubeIframeContainer').classList.remove('d-none');
                    }
                }
            } else {
                if (ytIframe) {
                    ytIframe.src = '';
                    ytIframe.classList.add('d-none');
                    if (document.getElementById('youtubeIframeContainer')) {
                        document.getElementById('youtubeIframeContainer').classList.add('d-none');
                    }
                }
                if (html5Player) {
                    html5Player.classList.remove('d-none');
                    const source = document.getElementById('mainVideoSource');
                    source.src = item.url;
                    if (item.thumbnail_url) {
                        html5Player.poster = item.thumbnail_url;
                    }
                    html5Player.load();
                    html5Player.play().catch(e => console.log('Autoplay deferred.'));
                }
            }

            // Update Download Button
            const dlContainer = document.getElementById('downloadBtnContainer');
            if (item.can_download) {
                dlContainer.innerHTML = '<a href="' + item.download_url + '" class="btn btn-download-video d-flex align-items-center fw-bold"><i class="fas fa-download me-2"></i> Download Video (HD)</a>';
            } else {
                dlContainer.innerHTML = '';
            }

            // Update Secondary Action Links
            document.getElementById('theaterNewTabBtn').href = item.url;
            const waShareText = encodeURIComponent("🎬 *Watch SYNC TRADE Official Plan Video* 🚀\n\n" + item.title + "\n👉 Watch Video: " + item.url + "\n\n👉 Register & Join my team:\n" + memberReferralUrl);
            document.getElementById('theaterWaShareBtn').href = "https://wa.me/?text=" + waShareText;

            // Smoothly scroll up to theater if on mobile
            document.getElementById('videoTheaterSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function copyReferralLink() {
            const input = document.getElementById('memberReferralInput');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value).then(() => {
                const btnText = document.getElementById('copyBtnText');
                btnText.innerText = 'Copied!';
                setTimeout(() => {
                    btnText.innerText = 'Copy My Referral Link';
                }, 2000);
            }).catch(err => {
                document.execCommand('copy');
                alert('Referral link copied!');
            });
        }

        function copyVideoUrl(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('Video link copied to clipboard!');
            }).catch(err => {
                prompt('Copy link:', url);
            });
        }
    </script>

@endsection
