@extends('member.layouts.main')
@section('title', 'View Geneology')
@section('container')
    @include('member.income._income-styles')

    @php
        $rootMember = $data;
        $allMembers = \App\Models\MemberDetail::select(
            'id',
            'memberid',
            'sponsorid',
            'name',
            'email',
            'status',
            'created_at',
            'profile_image',
            'downline',
            'wallet',
            'p2p_wallet',
            'pepe_wallet',
            'daily_team_biz',
        )->get();
        $memberMap = [];
        foreach ($allMembers as $member) {
            $memberMap[$member->memberid] = $member;
        }

        $teamCount = [];
        $countTeam = function ($memberId) use (&$countTeam, $memberMap, &$teamCount) {
            if (isset($teamCount[$memberId])) {
                return $teamCount[$memberId];
            }

            $children = [];
            foreach ($memberMap as $candidate) {
                if ($candidate->sponsorid == $memberId) {
                    $children[] = $candidate->memberid;
                }
            }

            $subTeam = 0;
            foreach ($children as $childId) {
                $subTeam += 1 + $countTeam($childId);
            }

            $teamCount[$memberId] = $subTeam;

            return $teamCount[$memberId];
        };

        $genealogyLevels = [];
        $currentParentIds = collect([$rootMember['memberid']]);

        for ($lvl = 1; $lvl <= 20; $lvl++) {
            $levelMembers = $allMembers
                ->filter(function ($member) use ($currentParentIds) {
                    return $currentParentIds->contains($member->sponsorid);
                })
                ->values();

            if ($levelMembers->isEmpty()) {
                break;
            }

            $genealogyLevels[] = [
                'label' => "Level {$lvl}",
                'levelNum' => $lvl,
                'members' => $levelMembers,
            ];

            $currentParentIds = $levelMembers->pluck('memberid');
        }

        $teamTotal = $countTeam($rootMember['memberid']);
        $rootDirects = $allMembers->filter(fn($member) => $member->sponsorid == $rootMember['memberid'])->values();
        $directTotal = $rootDirects->count();
        $levelTotal = count($genealogyLevels);
        $rootStatus = strtolower($rootMember['status'] ?? 'Active');
        $rootAvatarClass =
            $rootStatus === 'active' ? 'status-active' : ($rootStatus === 'temp' ? 'status-temp' : 'status-neutral');
    @endphp

    <div class="content-body inc-page genealogy-page">
        <div class="container-fluid py-4">
            <div class="genealogy-header">
                <div>
                    <h1 class="genealogy-main-title">Level Team</h1>
                    <div class="genealogy-breadcrumb">Dashboard &nbsp;›&nbsp; Network &nbsp;›&nbsp; Level Team</div>
                </div>

                <div class="genealogy-top-actions">
                    <div class="genealogy-filter-box">
                        <label>View By :</label>
                        <select id="genealogyViewBySelect" onchange="applyGenealogyViewBy()">
                            <option value="level" selected>Level (All)</option>
                            <option value="direct">Direct</option>
                        </select>
                    </div>

                    <div class="genealogy-search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="genealogySearchInput" placeholder="Search member by ID or name"
                            aria-label="Search member" />
                    </div>

                    <div class="genealogy-summary-label">Total Members : {{ $teamTotal }}</div>

                    <div class="genealogy-action-group">
                        <a href="{{ url('member/team/geneology') }}" class="genealogy-action-btn" id="genealogy-back-btn"
                            title="Back to My Tree">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                        <button type="button" class="genealogy-action-btn" id="genealogy-zoom-out-btn" title="Zoom Out">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <button type="button" class="genealogy-action-btn" id="genealogy-zoom-in-btn" title="Zoom In">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                        <button type="button" class="genealogy-action-btn" id="genealogy-refresh-btn" title="Refresh">
                            <i class="fa-solid fa-rotate-right"></i>
                        </button>
                        <button type="button" class="genealogy-action-btn" id="genealogy-fullscreen-btn"
                            title="Fullscreen">
                            <i class="fa-solid fa-expand"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="genealogy-tree-shell" id="genealogyTreeShell">
                <div class="genealogy-tree-stage" id="genealogyTreeStage">
                    <div class="genealogy-root-zone">
                        <div class="genealogy-root-card">
                            <div class="genealogy-root-avatar">
                                @if (!empty($rootMember['profile_image']))
                                    <img src="{{ asset('uploads') }}/{{ $rootMember['profile_image'] }}"
                                        alt="{{ $rootMember['name'] }}">
                                @else
                                    <img src="{{ asset('uploads/avatar.jpg') }}" alt="{{ $rootMember['name'] }}">
                                @endif
                            </div>

                            <div class="genealogy-root-id">{{ $rootMember['memberid'] }}</div>
                            <div class="genealogy-root-tag">You</div>
                            <div class="genealogy-root-meta">
                                <div class="genealogy-root-meta-item">
                                    <div class="genealogy-meta-value">{{ $teamTotal }}</div>
                                    <div class="genealogy-meta-label">Total Team</div>
                                </div>
                                <div class="genealogy-root-meta-item">
                                    <div class="genealogy-meta-value">{{ $directTotal }}</div>
                                    <div class="genealogy-meta-label">Direct</div>
                                </div>
                                <div class="genealogy-root-meta-item">
                                    <div class="genealogy-meta-value">{{ $levelTotal }}</div>
                                    <div class="genealogy-meta-label">Level</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach ($genealogyLevels as $level)
                        @if (
                            $level['members'] instanceof \Illuminate\Support\Collection
                                ? $level['members']->isNotEmpty()
                                : (is_array($level['members'])
                                    ? count($level['members']) > 0
                                    : !empty($level['members'])))
                            <div class="genealogy-level-wrap" data-level-num="{{ $level['levelNum'] }}">
                                <div class="genealogy-level-label">{{ $level['label'] }}</div>
                                <div class="genealogy-node-row">
                                    @foreach ($level['members'] as $member)
                                        @php
                                            $memberId = is_object($member)
                                                ? $member->memberid
                                                : $member['memberid'] ?? '';
                                            $memberName = is_object($member)
                                                ? $member->name
                                                : $member['name'] ?? 'Member';
                                            $memberStatus = is_object($member)
                                                ? $member->status ?? 'Active'
                                                : $member['status'] ?? 'Active';
                                            $memberImage = is_object($member)
                                                ? $member->profile_image ?? ''
                                                : $member['profile_image'] ?? '';
                                            $memberWallet = is_object($member)
                                                ? $member->wallet ?? 0
                                                : $member['wallet'] ?? 0;
                                            $memberFundWallet = is_object($member)
                                                ? $member->p2p_wallet ?? 0
                                                : $member['p2p_wallet'] ?? 0;
                                            $memberPepeWallet = is_object($member)
                                                ? $member->pepe_wallet ?? 0
                                                : $member['pepe_wallet'] ?? 0;
                                            $memberDailyTeamBiz = is_object($member)
                                                ? $member->daily_team_biz ?? 0
                                                : $member['daily_team_biz'] ?? 0;
                                            $memberDownline = is_object($member)
                                                ? $member->downline ?? 0
                                                : $member['downline'] ?? 0;
                                            $memberSponsorId = is_object($member)
                                                ? $member->sponsorid ?? ''
                                                : $member['sponsorid'] ?? '';
                                            $memberDirectCount = $allMembers
                                                ->filter(fn($node) => $node->sponsorid == $memberId)
                                                ->count();
                                            $memberTeamCount = isset($teamCount[$memberId]) ? $teamCount[$memberId] : 0;
                                            $lvlNum = $level['levelNum'] ?? 1;
                                            $colorClasses = [
                                                1 => 'level-blue',
                                                2 => 'level-cyan',
                                                3 => 'level-purple',
                                                4 => 'level-pink',
                                                5 => 'level-amber',
                                                6 => 'level-emerald',
                                                7 => 'level-indigo',
                                                8 => 'level-rose',
                                                9 => 'level-teal',
                                            ];
                                            $memberLevelClass = $colorClasses[$lvlNum] ?? 'level-purple';
                                            $memberStatusLower = strtolower(trim((string) $memberStatus));
                                            $memberAvatarStatus =
                                                $memberStatusLower === 'active'
                                                    ? 'status-active'
                                                    : ($memberStatusLower === 'temp'
                                                        ? 'status-temp'
                                                        : 'status-neutral');
                                        @endphp

                                        <a href="{{ url('member/team/view-geneology') }}/{{ is_object($member) ? $member->id ?? $member->memberid : $member['id'] ?? $member['memberid'] }}"
                                            class="genealogy-node-link">
                                            <div class="genealogy-node-card {{ $memberLevelClass }}"
                                                data-member-id="{{ $memberId }}"
                                                data-member-name="{{ $memberName }}">
                                                <div class="genealogy-card-avatar {{ $memberAvatarStatus }}">
                                                    @if (!empty($memberImage))
                                                        <img src="{{ asset('uploads') }}/{{ $memberImage }}"
                                                            alt="{{ $memberName }}">
                                                    @else
                                                        <img src="{{ asset('uploads/avatar.jpg') }}"
                                                            alt="{{ $memberName }}">
                                                    @endif
                                                </div>
                                                <div class="genealogy-card-memberid">{{ $memberId }}</div>
                                                <div class="genealogy-card-label">{{ $level['label'] }}</div>
                                                <div class="genealogy-card-stats">
                                                    <div>
                                                        <span class="genealogy-stat-number">{{ $memberTeamCount }}</span>
                                                        <span class="genealogy-stat-label">Total Team</span>
                                                    </div>
                                                    <div>
                                                        <span class="genealogy-stat-number">{{ $memberDirectCount }}</span>
                                                        <span class="genealogy-stat-label">Direct</span>
                                                    </div>
                                                </div>

                                                <div class="genealogy-member-tooltip">
                                                    <div class="genealogy-tooltip-header">
                                                        <span>{{ $memberName }}</span>
                                                        <em>{{ $memberStatus }}</em>
                                                    </div>
                                                    <div class="genealogy-tooltip-row"><span>Member
                                                            ID</span><strong>{{ $memberId }}</strong></div>
                                                    <div class="genealogy-tooltip-row">
                                                        <span>Wallet</span><strong>${{ number_format((float) $memberWallet, 2) }}</strong>
                                                    </div>
                                                    <div class="genealogy-tooltip-row"><span>Fund
                                                            Wallet</span><strong>${{ number_format((float) $memberFundWallet, 2) }}</strong>
                                                    </div>
                                                    <div class="genealogy-tooltip-row"><span>Pepe
                                                            Tokens</span><strong>{{ number_format((float) $memberPepeWallet, 2) }}
                                                            PEPE</strong></div>
                                                    <div class="genealogy-tooltip-row"><span>Daily Team
                                                            Biz</span><strong>${{ number_format((float) $memberDailyTeamBiz, 2) }}</strong>
                                                    </div>
                                                    <div class="genealogy-tooltip-row">
                                                        <span>Downline</span><strong>{{ $memberDownline }}</strong></div>
                                                    <div class="genealogy-tooltip-row"><span>Sponsor
                                                            ID</span><strong>{{ $memberSponsorId ?: 'N/A' }}</strong></div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <div id="genealogySearchNoResult" class="genealogy-search-no-result">No member found for this search.
                    </div>

                    <div class="genealogy-legend">
                        <span><i class="legend-dot dot-you"></i> You</span>
                        <span><i class="legend-dot dot-level1"></i> Level 1</span>
                        <span><i class="legend-dot dot-level2"></i> Level 2</span>
                        <span><i class="legend-dot dot-level3"></i> Level 3</span>
                        <span class="legend-line-item"><i class="legend-line direct"></i> Direct</span>
                        <span class="legend-line-item"><i class="legend-line indirect"></i> Indirect</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const genealogyTreeShell = document.getElementById('genealogyTreeShell');
        const genealogyTreeStage = document.getElementById('genealogyTreeStage');
        let genealogyScale = 1;

        function applyGenealogyZoom(nextScale) {
            genealogyScale = Math.min(Math.max(nextScale, 0.8), 1.6);
            genealogyTreeStage.style.setProperty('zoom', genealogyScale);
            genealogyTreeStage.style.setProperty('--genealogy-scale', genealogyScale);
            genealogyTreeStage.style.width = (100 / genealogyScale) + '%';
        }

        function applyGenealogySearch() {
            const searchInput = document.getElementById('genealogySearchInput');
            if (!searchInput) {
                return;
            }

            const query = (searchInput.value || '').trim().toLowerCase();
            const levelWraps = document.querySelectorAll('.genealogy-level-wrap');
            let hasVisibleCard = false;

            levelWraps.forEach((wrap) => {
                const cards = wrap.querySelectorAll('.genealogy-node-card');
                let visibleCount = 0;

                cards.forEach((card) => {
                    const memberId = (card.dataset.memberId || '').toLowerCase();
                    const memberName = (card.dataset.memberName || '').toLowerCase();
                    const match = !query || memberId.includes(query) || memberName.includes(query);

                    card.style.display = match ? '' : 'none';
                    if (match) {
                        visibleCount++;
                    }
                });

                const shouldShowLevel = !query || visibleCount > 0;
                wrap.style.display = shouldShowLevel ? '' : 'none';
                if (shouldShowLevel) {
                    hasVisibleCard = true;
                }
            });

            const noResultNode = document.getElementById('genealogySearchNoResult');
            if (noResultNode) {
                noResultNode.style.display = query && !hasVisibleCard ? 'flex' : 'none';
            }
        }

        document.getElementById('genealogy-back-btn')?.addEventListener('click', function() {
            if (window.history.length > 1) {
                window.history.back();
                return;
            }

            window.location.href = '{{ url('member/team/geneology') }}';
        });

        document.getElementById('genealogy-fullscreen-btn')?.addEventListener('click', function() {
            if (!document.fullscreenElement) {
                genealogyTreeShell?.requestFullscreen?.();
            } else {
                document.exitFullscreen?.();
            }
        });

        document.getElementById('genealogy-refresh-btn')?.addEventListener('click', function() {
            window.location.reload();
        });

        document.getElementById('genealogy-zoom-in-btn')?.addEventListener('click', function() {
            applyGenealogyZoom(genealogyScale + 0.1);
        });

        document.getElementById('genealogy-zoom-out-btn')?.addEventListener('click', function() {
            applyGenealogyZoom(genealogyScale - 0.1);
        });

        function applyGenealogyViewBy() {
            const viewBySelect = document.getElementById('genealogyViewBySelect');
            if (!viewBySelect) return;

            const val = viewBySelect.value;
            const levelWraps = document.querySelectorAll('.genealogy-level-wrap');

            levelWraps.forEach((wrap) => {
                const levelNum = wrap.getAttribute('data-level-num');
                if (val === 'direct') {
                    if (levelNum === '1') {
                        wrap.style.display = '';
                    } else {
                        wrap.style.display = 'none';
                    }
                } else {
                    wrap.style.display = '';
                }
            });
        }

        document.getElementById('genealogyViewBySelect')?.addEventListener('change', applyGenealogyViewBy);

        document.getElementById('genealogySearchInput')?.addEventListener('input', applyGenealogySearch);
        applyGenealogyZoom(genealogyScale);
        applyGenealogyViewBy();
        applyGenealogySearch();
    </script>
@endsection
