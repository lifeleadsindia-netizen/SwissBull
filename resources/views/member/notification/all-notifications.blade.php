@extends('member.layouts.main')
@section('title', 'Notifications')
@section('container')

    <style>
        .notif-main-card {
            background: #0C0E14 !important;
            border: 1px solid rgba(245, 158, 11, 0.20) !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6) !important;
            overflow: hidden;
        }

        .notif-header-banner {
            background: linear-gradient(135deg, #0C0F17 0%, #1A1408 50%, #2A1904 100%);
            border-bottom: 1px solid rgba(245, 158, 11, 0.18);
            padding: 20px 24px;
        }

        .notif-tab-btn {
            background: rgba(255, 255, 255, 0.06);
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .notif-tab-btn:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
        }

        .notif-tab-btn.active {
            background: linear-gradient(135deg, #F59E0B, #D97706);
            color: #08090C;
            border-color: #F59E0B;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4);
        }

        .notif-card-item {
            border-radius: 12px;
            padding: 18px 20px;
            margin-bottom: 16px;
            transition: all 0.25s ease;
            position: relative;
        }

        .notif-card-item.unread {
            background: #141722 !important;
            border: 1px solid rgba(245, 158, 11, 0.35) !important;
            border-left: 5px solid #F59E0B !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.45);
        }

        .notif-card-item.read {
            background: #0A0C10 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-left: 5px solid #10B981 !important;
            opacity: 0.92;
        }

        .notif-card-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.45);
        }

        .notif-card-title {
            color: #ffffff !important;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.2px;
            margin: 0;
        }

        .notif-card-message {
            color: #e2e8f0 !important;
            font-size: 14.5px;
            line-height: 1.65;
            margin-top: 10px;
            margin-bottom: 14px;
            white-space: pre-line;
            word-break: break-word;
        }

        .notif-card-meta {
            color: #94a3b8 !important;
            font-size: 12.5px;
        }

        .badge-all-users {
            background: rgba(245, 158, 11, 0.15) !important;
            color: #FBBF24 !important;
            border: 1px solid rgba(245, 158, 11, 0.35) !important;
            font-size: 11.5px;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .badge-specific-member {
            background: rgba(239, 68, 68, 0.15) !important;
            color: #FCA5A5 !important;
            border: 1px solid rgba(239, 68, 68, 0.35) !important;
            font-size: 11.5px;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .badge-unread-status {
            background: #EF4444 !important;
            color: #ffffff !important;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .badge-read-status {
            background: rgba(16, 185, 129, 0.15) !important;
            color: #10B981 !important;
            border: 1px solid rgba(16, 185, 129, 0.35) !important;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .btn-mark-all {
            background: linear-gradient(135deg, #F59E0B, #D97706);
            color: #08090C;
            border: none;
            font-weight: 800;
            font-size: 13px;
            padding: 8px 18px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-mark-all:hover:not(:disabled) {
            background: #d97706;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-mark-all:disabled {
            background: rgba(255, 255, 255, 0.1);
            color: #64748b;
            cursor: not-allowed;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>

    <div class="content-body">
        <div class="container-fluid pt-2 pb-5" style="padding-bottom: 80px !important;">
            <!-- Page Header -->
            <div class="page-titles mb-3">
                <div class="welcome-text">
                    <h4 class="text-white font-weight-bold mb-1">
                        <i class="fas fa-bell me-2 text-warning"></i> Notifications
                    </h4>
                    <p class="mb-0 text-muted" style="font-size: 13px;">
                        Stay updated with system announcements, personal notices, and account alerts.
                    </p>
                </div>
                <div class="justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/member/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Notifications</a></li>
                    </ol>
                </div>
            </div>

            <!-- Success feedback toast/alert (hidden by default) -->
            <div id="actionAlert" class="alert alert-success d-none mb-3" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.4); color: #4ade80;">
                <i class="fas fa-check-circle me-2"></i> <span id="actionAlertMsg"></span>
            </div>

            @php
                $totalCount = count($notifications);
                $unreadTotal = $unreadCount ?? $notifications->reject(fn($n) => in_array((int)$n->id, $readIds, true))->count();
                $readTotal = $totalCount - $unreadTotal;
            @endphp

            <div class="row">
                <div class="col-12">
                    <div class="card notif-main-card">
                        <!-- Card Header Banner -->
                        <div class="notif-header-banner d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <button type="button" class="notif-tab-btn active" id="tabAllBtn" onclick="filterNotifs('all')">
                                    All Notifications (<span id="countTotal">{{ $totalCount }}</span>)
                                </button>
                                <button type="button" class="notif-tab-btn" id="tabUnreadBtn" onclick="filterNotifs('unread')">
                                    Unread (<span id="countUnread">{{ $unreadTotal }}</span>)
                                </button>
                                <button type="button" class="notif-tab-btn" id="tabReadBtn" onclick="filterNotifs('read')">
                                    Read (<span id="countRead">{{ $readTotal }}</span>)
                                </button>
                            </div>

                            <div>
                                @if($totalCount > 0)
                                    <button type="button" 
                                            id="btnMarkAllPage" 
                                            onclick="markAllReadPage()" 
                                            class="btn btn-mark-all" 
                                            {{ $unreadTotal == 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-check-double me-1"></i> 
                                        <span id="btnMarkAllText">{{ $unreadTotal == 0 ? 'All Caught Up' : 'Mark All as Read' }}</span>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-3 p-md-4">
                            @if($totalCount > 0)
                                <div id="notifsContainer">
                                    @foreach($notifications as $n)
                                        @php
                                            $isRead = in_array((int)$n->id, $readIds, true) || in_array((string)$n->id, $readIds, true);
                                            $isSpecific = (strtolower(trim($n->type ?? '')) === 'specific member' || !empty($n->memberid));
                                        @endphp

                                        <div class="notif-card-item {{ $isRead ? 'read' : 'unread' }}" 
                                             id="notif-card-{{ $n->id }}"
                                             data-read="{{ $isRead ? '1' : '0' }}">
                                            
                                            <!-- Top row: Title + Badges -->
                                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge {{ $isSpecific ? 'badge-specific-member' : 'badge-all-users' }}">
                                                        <i class="fas {{ $isSpecific ? 'fa-user-tag' : 'fa-bullhorn' }} me-1"></i>
                                                        {{ $isSpecific ? 'Specific Member' : 'All Users' }}
                                                    </span>
                                                    <h5 class="notif-card-title">{{ $n->title }}</h5>
                                                </div>

                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge {{ $isRead ? 'badge-read-status' : 'badge-unread-status' }}" id="status-badge-{{ $n->id }}">
                                                        <i class="fas {{ $isRead ? 'fa-check' : 'fa-circle' }} me-1" style="font-size: {{ $isRead ? '10px' : '7px' }};"></i>
                                                        {{ $isRead ? 'Read' : 'Unread' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Message content -->
                                            <div class="notif-card-message">
                                                {{ $n->message }}
                                            </div>

                                            <!-- Bottom row: Date & Actions -->
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pt-2 border-top" style="border-color: rgba(255, 255, 255, 0.08) !important;">
                                                <div class="notif-card-meta">
                                                    <i class="far fa-clock me-1 text-warning"></i> 
                                                    {{ date('d-m-Y h:i A', strtotime($n->created_at)) }}
                                                </div>

                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="{{ url('/member/notification/' . $n->id) }}" class="btn btn-xs btn-outline-info text-info border-info" style="font-weight: 600; padding: 4px 10px; border-radius: 6px;">
                                                        <i class="fas fa-external-link-alt me-1"></i> View Detail
                                                    </a>

                                                    @if(!$isRead)
                                                        <button type="button" 
                                                                class="btn btn-xs btn-outline-success text-success border-success" 
                                                                id="read-btn-{{ $n->id }}" 
                                                                onclick="markSingleRead({{ $n->id }})"
                                                                style="font-weight: 600; padding: 4px 10px; border-radius: 6px;">
                                                            <i class="fas fa-check me-1"></i> Mark Read
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Filter Empty Placeholder -->
                                <div id="filterEmptyPlaceholder" class="text-center py-5 d-none">
                                    <i class="far fa-bell-slash fa-3x text-muted mb-3"></i>
                                    <h5 class="text-white">No Matching Notifications</h5>
                                    <p class="text-muted" id="filterEmptyText">No notifications found in this tab.</p>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 70px; height: 70px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                                        <i class="far fa-bell-slash fa-2x text-muted"></i>
                                    </div>
                                    <h5 class="text-white fw-bold">No Notifications Found</h5>
                                    <p class="text-muted" style="max-width: 360px; margin: 0 auto; font-size: 13.5px;">
                                        There are currently no announcements or notifications sent to your account.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Filter tabs: All, Unread, Read
        var currentFilter = 'all';
        function filterNotifs(type) {
            currentFilter = type;
            document.querySelectorAll('.notif-tab-btn').forEach(btn => btn.classList.remove('active'));
            if(type === 'all') document.getElementById('tabAllBtn')?.classList.add('active');
            if(type === 'unread') document.getElementById('tabUnreadBtn')?.classList.add('active');
            if(type === 'read') document.getElementById('tabReadBtn')?.classList.add('active');

            var items = document.querySelectorAll('.notif-card-item');
            var visibleCount = 0;

            items.forEach(function(el) {
                var isRead = el.getAttribute('data-read') === '1';
                var show = false;
                if (type === 'all') show = true;
                else if (type === 'unread' && !isRead) show = true;
                else if (type === 'read' && isRead) show = true;

                if (show) {
                    el.style.display = 'block';
                    visibleCount++;
                } else {
                    el.style.display = 'none';
                }
            });

            var emptyBox = document.getElementById('filterEmptyPlaceholder');
            if (emptyBox) {
                if (visibleCount === 0 && items.length > 0) {
                    emptyBox.classList.remove('d-none');
                    var txt = document.getElementById('filterEmptyText');
                    if (txt) {
                        txt.innerText = type === 'unread' ? 'You have no unread notifications.' : 'You have no read notifications yet.';
                    }
                } else {
                    emptyBox.classList.add('d-none');
                }
            }
        }

        // Helper to update counters
        function recalculateCounters() {
            var allItems = document.querySelectorAll('.notif-card-item');
            var unreadCount = 0;
            var readCount = 0;

            allItems.forEach(function(item) {
                if (item.getAttribute('data-read') === '1') {
                    readCount++;
                } else {
                    unreadCount++;
                }
            });

            var unreadSpan = document.getElementById('countUnread');
            if (unreadSpan) unreadSpan.innerText = unreadCount;
            var readSpan = document.getElementById('countRead');
            if (readSpan) readSpan.innerText = readCount;

            // Update Mark All button
            var btnMarkAll = document.getElementById('btnMarkAllPage');
            var btnText = document.getElementById('btnMarkAllText');
            if (btnMarkAll && btnText) {
                if (unreadCount === 0) {
                    btnMarkAll.disabled = true;
                    btnText.innerText = 'All Caught Up';
                } else {
                    btnMarkAll.disabled = false;
                    btnText.innerText = 'Mark All as Read';
                }
            }

            // Re-apply filter in case an item was changed while inside 'unread' tab
            if (currentFilter !== 'all') {
                filterNotifs(currentFilter);
            }
        }

        // Mark Single Read
        function markSingleRead(id) {
            var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

            fetch("{{ route('member.notifications.read') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({ id: id })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    var card = document.getElementById('notif-card-' + id);
                    if (card) {
                        card.classList.remove('unread');
                        card.classList.add('read');
                        card.setAttribute('data-read', '1');
                    }

                    var badge = document.getElementById('status-badge-' + id);
                    if (badge) {
                        badge.className = 'badge badge-read-status';
                        badge.innerHTML = '<i class="fas fa-check me-1" style="font-size: 10px;"></i> Read';
                    }

                    var btn = document.getElementById('read-btn-' + id);
                    if (btn) btn.remove();

                    // Update header bell
                    var bellBadge = document.querySelector('.notification_dropdown .nav-link span.notif-badge');
                    if (bellBadge) {
                        var current = parseInt(bellBadge.innerText) || 0;
                        if (current > 1) {
                            bellBadge.innerText = (current - 1);
                        } else {
                            bellBadge.remove();
                        }
                    }

                    recalculateCounters();
                }
            })
            .catch(console.error);
        }

        // Mark All as Read
        function markAllReadPage() {
            var btn = document.getElementById('btnMarkAllPage');
            var btnText = document.getElementById('btnMarkAllText');
            if (btn) btn.disabled = true;
            if (btnText) btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Marking...';

            var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "{{ csrf_token() }}";

            fetch("{{ route('member.notifications.readAll') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                }
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    // Update all cards on page
                    document.querySelectorAll('.notif-card-item').forEach(function(card) {
                        card.classList.remove('unread');
                        card.classList.add('read');
                        card.setAttribute('data-read', '1');
                    });

                    // Update all badges
                    document.querySelectorAll('.notif-card-item .badge-unread-status').forEach(function(badge) {
                        badge.className = 'badge badge-read-status';
                        badge.innerHTML = '<i class="fas fa-check me-1" style="font-size: 10px;"></i> Read';
                    });

                    // Remove all individual read buttons
                    document.querySelectorAll('.notif-card-item button[id^="read-btn-"]').forEach(function(b) {
                        b.remove();
                    });

                    // Remove header bell badge
                    var bellBadge = document.querySelector('.notification_dropdown .nav-link span.notif-badge');
                    if (bellBadge) bellBadge.remove();

                    // Update header dropdown list if open
                    var headerList = document.getElementById('headerNotifList');
                    if (headerList) {
                        headerList.innerHTML = '<div class="header-notif-item"><div class="header-notif-title">No notifications</div><div class="header-notif-msg">You have no unread notifications.</div></div>';
                    }

                    // Show success feedback banner
                    var alertBox = document.getElementById('actionAlert');
                    var alertMsg = document.getElementById('actionAlertMsg');
                    if (alertBox && alertMsg) {
                        alertMsg.innerText = res.message || 'All notifications marked as read successfully.';
                        alertBox.classList.remove('d-none');
                        setTimeout(function() {
                            alertBox.classList.add('d-none');
                        }, 4000);
                    }

                    recalculateCounters();
                } else {
                    if (btn) btn.disabled = false;
                    if (btnText) btnText.innerText = 'Mark All as Read';
                }
            })
            .catch(err => {
                console.error(err);
                if (btn) btn.disabled = false;
                if (btnText) btnText.innerText = 'Mark All as Read';
            });
        }

        // Header sync callback
        window.onHeaderMarkAllRead = function() {
            document.querySelectorAll('.notif-card-item').forEach(function(card) {
                card.classList.remove('unread');
                card.classList.add('read');
                card.setAttribute('data-read', '1');
            });
            document.querySelectorAll('.notif-card-item .badge-unread-status').forEach(function(badge) {
                badge.className = 'badge badge-read-status';
                badge.innerHTML = '<i class="fas fa-check me-1" style="font-size: 10px;"></i> Read';
            });
            document.querySelectorAll('.notif-card-item button[id^="read-btn-"]').forEach(function(b) {
                b.remove();
            });
            recalculateCounters();
        };
    </script>

@endsection
