@extends('admin.layouts.main')
@section('title', 'Partnership Income')
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-edit bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Partnership Income') }}</h5>
                            <span>{{ __('Partnership Income details') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Admin') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Partnership Income') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                @include('admin.partials.history-date-filter', ['showMemberIdFilter' => true])
                
                @if (session()->has('wMessage'))
                    <div class="alert alert-primary">{{ session('wMessage') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('Partnership Income') }}</h3>
                    </div>
                    <div class="card-body px-5 " style="overflow: auto">
                        <div class="responsive">
                            @php
                                $incomeList = $data ?? [];
                            @endphp
                            <table id="data_table" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('S.No') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Member ID') }}</th>
                                        <th>{{ __('Rank') }}</th>
                                        <th>{{ __('Total Investment') }}</th>
                                        <th>{{ __('Rate') }}</th>
                                        <th>{{ __('Daily Team Biz') }}</th>
                                        <th>{{ __('Total Capping') }}</th>
                                        <th>{{ __('Total Earn') }}</th>
                                        <th>{{ __('Last Earn') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1;  @endphp
                                    @foreach ($incomeList as $list)
                                        @php
                                            $item = (array) (is_array($list) ? $list : $list->getAttributes());
                                            $invAmt = (float) ($item['total_investment'] ?? ($item['package'] ?? 0));
                                            $investId = $item['invest_id'] ?? null;
                                            $detail = $investId ? \App\Models\PartnershipDetail::find($investId) : null;
                                            if ($detail && (float) $detail->capping > 0) {
                                                $totalCapping = (float) $detail->capping;
                                            } else {
                                                $rankLower = strtolower((string) ($item['rank'] ?? ($detail?->rank ?? '')));
                                                if ($rankLower === 'silver' || $invAmt == 1000) {
                                                    $cappingMult = 2;
                                                } elseif ($rankLower === 'gold' || $invAmt == 5000) {
                                                    $cappingMult = 4;
                                                } elseif ($rankLower === 'platinum' || $invAmt == 10000) {
                                                    $cappingMult = 6;
                                                } elseif ($rankLower === 'diamond' || $invAmt == 25000) {
                                                    $cappingMult = 8;
                                                } else {
                                                    $cappingMult = 2;
                                                }
                                                $totalCapping = $invAmt * $cappingMult;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ date('d-m-Y', strtotime($item['date'] ?? $item['created_at'])) }}</td>
                                            <td>{{ $item['memberid'] }}</td>
                                            <td>
                                                @if (!empty($item['rank']))
                                                    <span class="badge badge-info">{{ $item['rank'] }}</span>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>$ {{ number_format($item['total_investment'] ?? $item['package'] ?? 0, 2) }}</td>
                                            <td>{{ $item['rate'] ?? 0 }}%</td>
                                            <td>$ {{ $item['daily_team_biz'] ?? 0 }}</td>
                                            <td>$ {{ number_format($totalCapping, 2) }}</td>
                                            <td>$ {{ number_format($item['achieved'] ?? 0, 2) }}</td>
                                            <td>$ {{ number_format($item['amount'] ?? 0, 2) }}</td>
                                            <td>
                                                @if (($item['status'] ?? '') == 'Paid')
                                                    <label class="badge badge-success">{{ $item['status'] }}</label>
                                                @else
                                                    <label class="badge badge-danger">{{ $item['status'] ?? 'Unpaid' }}</label>
                                                @endif
                                            </td>
                                        </tr>
                                        @php $i++;  @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
    @endpush
@endsection
