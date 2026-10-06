@extends('admin.layouts.main')
@section('title', 'Set Roi Rate')

@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-trending-up bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Set Roi Rate') }}</h5>
                            <span>{{ __('Update Roi Rate') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('hdgteyusjasget/dashboard') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ url('hdgteyusjasget/dashboard') }}">{{ __('Admin') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Set Roi Rate') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                @if (session()->has('successMsg'))
                    <div class="alert alert-primary" role="alert">
                        {{ session('successMsg') }}
                    </div>
                @endif
                @if (session()->has('failedMsg'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('failedMsg') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('Set New Roi Rate') }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setRoiRate') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>{{ __('Rate') }}</label>
                                <input type="text" class="form-control" name="roiRate" value="{{ $data['roi_rate'] }}">
                                @error('roiRate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <button type="submit"
                                    class="btn btn-primary float-right">{{ __('Update RoiRate') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('Rate History') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data_table" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('S.No') }}</th>
                                        <th>{{ __('Rate') }}</th>
                                        <th>{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($data as $list)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ number_format((float) $list->rate, 4) }}</td>
                                        <td>{{ optional($list->created_at)->format('d-m-Y h:i A') }}</td>
                                    </tr>
                                    @php $i++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>
    </div>

    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/form-components.js') }}"></script>
    @endpush
@endsection