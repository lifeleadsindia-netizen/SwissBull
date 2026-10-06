@extends('admin.layouts.main')
@section('title', 'Promotion Airdrop & PEPE Reward Report')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-bar-chart bg-blue"></i>
                    <div class="d-inline">
                        <h5>{{ __('Promotion Airdrop & PEPE Reward Report')}}</h5>
                        <span>{{ __('Track WhatsApp messages, direct registrations, and direct activations PEPE Token distribution')}}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('hdgteyusjasget/dashboard') }}"><i class="ik ik-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">{{ __('Marketing')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Promotion Reports')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Summary Stats Cards (All 3 Rules + Grand Total) -->
    <div class="row">
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card card-sm bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-white-50 d-block text-uppercase font-weight-bold">Rule 1: Messages</small>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($msgCount ?? 0) }}</h4>
                        <small>{{ number_format($msgTokens ?? 0, 0) }} PEPE</small>
                    </div>
                    <i class="ik ik-send ik-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card card-sm bg-info text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-white-50 d-block text-uppercase font-weight-bold">Rule 2: Direct Reg</small>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($regCount ?? 0) }}</h4>
                        <small>{{ number_format($regTokens ?? 0, 0) }} PEPE</small>
                    </div>
                    <i class="ik ik-user-plus ik-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card card-sm bg-warning text-dark">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-dark d-block text-uppercase font-weight-bold">Rule 3: Direct Act</small>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($actCount ?? 0) }}</h4>
                        <small>{{ number_format($actTokens ?? 0, 0) }} PEPE</small>
                    </div>
                    <i class="ik ik-zap ik-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card card-sm bg-success text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-white-50 d-block text-uppercase font-weight-bold">Total Distributed</small>
                        <h4 class="mb-0 font-weight-bold">{{ number_format($totalPepeDistributed ?? 0, 0) }} PEPE</h4>
                        <small>{{ number_format($totalReferrals ?? 0) }} total events</small>
                    </div>
                    <i class="ik ik-award ik-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Actions Card -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('Filter Reports') }}</h3>
                    <a href="{{ route('admin.whatsapp.exportReports', request()->all()) }}" class="btn btn-success btn-sm">
                        <i class="ik ik-download me-1"></i> Export to CSV
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.whatsapp.reports') }}" method="get">
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label>{{ __('Date From') }}</label>
                                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="form-group col-md-2">
                                <label>{{ __('Date To') }}</label>
                                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                            </div>
                            <div class="form-group col-md-2">
                                <label>{{ __('Member ID') }}</label>
                                <input type="text" class="form-control" name="member_id" value="{{ request('member_id') }}" placeholder="Search Sponsor ID">
                            </div>
                            <div class="form-group col-md-3">
                                <label>{{ __('Reward Rule / Type') }}</label>
                                <select class="form-control" name="reward_type">
                                    <option value="all" {{ request('reward_type') === 'all' || !request('reward_type') ? 'selected' : '' }}>All Promotion Rules</option>
                                    <option value="message" {{ request('reward_type') === 'message' ? 'selected' : '' }}>Rule 1: WhatsApp Message (500 PEPE)</option>
                                    <option value="direct_registration" {{ request('reward_type') === 'direct_registration' ? 'selected' : '' }}>Rule 2: Direct Registration (500 PEPE)</option>
                                    <option value="direct_activation" {{ request('reward_type') === 'direct_activation' ? 'selected' : '' }}>Rule 3: Direct Activation (500 PEPE)</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>{{ __('Search Details / Mobile') }}</label>
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search Mobile / Referred ID">
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary"><i class="ik ik-filter me-1"></i> {{ __('Filter') }}</button>
                            <a href="{{ route('admin.whatsapp.reports') }}" class="btn btn-secondary mr-2">{{ __('Reset') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Referral History Table -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('Promotion Reward & Referral History') }}</h3>
                </div>
                <div class="card-body px-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">{{ __('S.No') }}</th>
                                    <th>{{ __('Sponsor ID') }}</th>
                                    <th>{{ __('Reward Rule / Type') }}</th>
                                    <th>{{ __('Details / Reference') }}</th>
                                    <th>{{ __('Reward Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date & Time') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($referrals as $index => $item)
                                    <tr>
                                        <td>{{ $referrals->firstItem() + $index }}</td>
                                        <td><strong>{{ $item->member_id }}</strong></td>
                                        <td>
                                            @if(($item->reward_type ?? '') === 'direct_registration')
                                                <span class="badge badge-info py-1 px-2">
                                                    <i class="ik ik-user-plus mr-1"></i> Rule 2: Direct Registration
                                                </span>
                                            @elseif(($item->reward_type ?? '') === 'direct_activation')
                                                <span class="badge badge-warning py-1 px-2">
                                                    <i class="ik ik-zap mr-1"></i> Rule 3: Direct Activation
                                                </span>
                                            @else
                                                <span class="badge badge-success py-1 px-2">
                                                    <i class="fab fa-whatsapp mr-1"></i> Rule 1: WhatsApp Message
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($item->reward_type ?? '') === 'direct_registration')
                                                <span>{{ $item->description ?: 'Referred Member: '.($item->referred_member_id ?? '-') }}</span>
                                            @elseif(($item->reward_type ?? '') === 'direct_activation')
                                                <span>{{ $item->description ?: 'Activated Member: '.($item->referred_member_id ?? '-') }}</span>
                                            @else
                                                <strong>{{ $item->mobile_number }}</strong>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-success font-weight-bold" style="font-size: 13px;">
                                                +{{ number_format($item->reward_amount, 0) }} PEPE
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">{{ $item->status ?? 'Completed' }}</span>
                                        </td>
                                        <td>{{ $item->created_at ? $item->created_at->format('d-m-Y h:i A') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            No promotion airdrop records found matching criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        {{ $referrals->appends(request()->all())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
