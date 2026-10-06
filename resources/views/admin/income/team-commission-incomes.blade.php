@extends('admin.layouts.main')
@section('title', 'Team Withdrawal Commission Income')
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
                            <h5>{{ __('Team Withdrawal Commission Income')}}</h5>
                            <span>{{ __('Team Withdrawal Commission Income details')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Admin')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Team Withdrawal Commission Income')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                @include('admin.partials.history-date-filter')
                
                @if (session()->has('wMessage'))
                <div class="alert alert-primary">{{session('wMessage')}}</div>
                @endif
                <div class="card">
                    <div class="card-header"><h3>{{ __('Team Withdrawal Commission Income')}}</h3></div>
                    <div class="card-body px-5 " style="overflow: auto">
                        <div class="responsive">
                            @php
                                $withIncomes = \App\Models\WithdrawalIncome::orderby('created_at', 'desc')->get();
                                $incomeList = $withIncomes->isNotEmpty() ? $withIncomes : ((isset($data) && count($data) > 0) ? $data : []);
                            @endphp
                            <table id="data_table" class="table">
                                <thead>
                                     <tr>
                                         <th>{{ __('S.No') }}</th>
                                         <th>{{ __('Date') }}</th>
                                         <th>{{ __('Member ID') }}</th>
                                         <th>{{ __('Level') }}</th>
                                         <th>{{ __('Level ID') }}</th>
                                         <th>{{ __('Name') }}</th>
                                         <th>{{ __('Withdrawal Amount') }}</th>
                                         <th>{{ __('Rate') }}</th>
                                         <th>{{ __('Commission Amount') }}</th>
                                         <th>{{ __('Status') }}</th>
                                     </tr>
                                </thead>
                                <tbody>
                                     @php $i = 1;  @endphp
                                     @foreach ($incomeList as $list)
                                         @php
                                             $item = (array) (is_array($list) ? $list : $list->getAttributes());
                                         @endphp
                                         <tr>
                                             <td>{{ $i }}</td>
                                             <td>{{ date('d-m-Y', strtotime($item['created_at'])) }}</td>
                                             <td>{{ $item['memberid'] }}</td>
                                             <td>Level {{ $item['level'] ?? '' }}</td>
                                             <td>{{ $item['level_id'] ?? '' }}</td>
                                             <td>{{ !empty($item['name']) ? $item['name'] : getName($item['level_id'] ?? '') }}</td>
                                             <td>$ {{ number_format($item['withdrawal_amount'] ?? $item['package'] ?? 0, 2) }}</td>
                                             <td>{{ $item['rate'] ?? 2 }}%</td>
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
