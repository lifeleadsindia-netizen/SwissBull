@extends('admin.layouts.main')
@section('title', 'WhatsApp Referral Report')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-bar-chart bg-blue"></i>
                    <div class="d-inline">
                        <h5>{{ __('WhatsApp Referral Report')}}</h5>
                        <span>{{ __('Track referrals and PEPE Token rewards distribution')}}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('WhatsApp Reports')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Summary Stats Cards -->
    <div class="row">
        <div class="col-xl-6 col-md-6 col-12">
            <div class="card card-sm bg-primary text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0 font-weight-bold">{{ number_format($totalReferrals) }}</h3>
                        <span>Total Referrals Sent</span>
                    </div>
                    <i class="ik ik-send ik-3x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12">
            <div class="card card-sm bg-success text-white">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0 font-weight-bold">{{ number_format($totalPepeDistributed, 0) }} PEPE</h3>
                        <span>Total PEPE Distributed</span>
                    </div>
                    <i class="ik ik-award ik-3x opacity-50"></i>
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
                            <div class="form-group col-md-3">
                                <label>{{ __('Date From') }}</label>
                                <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label>{{ __('Date To') }}</label>
                                <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label>{{ __('Member ID') }}</label>
                                <input type="text" class="form-control" name="member_id" value="{{ request('member_id') }}" placeholder="Search Member ID">
                            </div>
                            <div class="form-group col-md-3">
                                <label>{{ __('Mobile Number') }}</label>
                                <input type="text" class="form-control" name="mobile_number" value="{{ request('mobile_number') }}" placeholder="Search Mobile">
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
                    <h3>{{ __('Referral History') }}</h3>
                </div>
                <div class="card-body px-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('S.No') }}</th>
                                    <th>{{ __('Member ID') }}</th>
                                    <th>{{ __('Recipient Mobile') }}</th>
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
                                        <td>{{ $item->mobile_number }}</td>
                                        <td>
                                            <span class="badge badge-success">
                                                +{{ number_format($item->reward_amount, 0) }} PEPE
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $item->status }}</span>
                                        </td>
                                        <td>{{ $item->created_at ? $item->created_at->format('d-m-Y h:i A') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            No referral records found matching criteria.
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
