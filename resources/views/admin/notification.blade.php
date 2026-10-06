@extends('admin.layouts.main') 
@section('title', 'Notification')
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
                        <h5>{{ __('Notification')}}</h5>
                        <span>{{ __('All Notification')}}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Notification')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if (session()->has('successMsg'))
                <div class="alert alert-success" role="alert">
                    {{session('successMsg')}}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('Notification Form')}}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('saveNotification') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>{{ __('Notify Type') }}</label>
                                <select name="type" id="notify_type" class="form-control">
                                    <option value="All Users">{{ __('All Users') }}</option>
                                    <option value="Specific Member">{{ __('Specific Member') }}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4" id="memberid_group" style="display:none;">
                                <label>{{ __('Member ID') }}</label>
                                <input type="text" name="memberid" id="memberid_input" class="form-control" placeholder="Enter Member ID">
                                @error('memberid')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label>{{ __('Title') }}</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter Title" required>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ __('Message') }}</label>
                            <textarea name="message" class="form-control" rows="4" required placeholder="Enter notification message"></textarea>
                            @error('message')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <button class="btn btn-primary float-right">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @if (session()->has('delMsg'))
                <div class="alert alert-primary" role="alert">
                    {{session('delMsg')}}
                </div>
            @endif
            <div class="card">
                <div class="card-header"><h3>{{ __('Notification Table')}}</h3></div>
                <div class="card-body px-5">
                    <table id="data_table" class="table">
                        <thead>
                            <tr>
                                <th>{{ __('S.No')}}</th>
                                <th>{{ __('Created At')}}</th>
                                <th>{{ __('Type')}}</th>
                                <th>{{ __('Member ID')}}</th>
                                <th>{{ __('Title')}}</th>
                                <th>{{ __('Message')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1;  @endphp
                            @foreach ($data as $list )
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{date('d-m-Y', strtotime($list['created_at']))}}</td>
                                    <td>{{ $list['type'] }}</td>
                                    <td>{{ $list['memberid'] ?? '-' }}</td>
                                    <td>{{ $list['title'] }}</td>
                                    <td>{{ $list['message'] }}</td>
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

    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/form-components.js') }}"></script>
        <script>
            (function(){
                function toggleMemberField(){
                    var t = document.getElementById('notify_type');
                    var grp = document.getElementById('memberid_group');
                    if(!t || !grp) return;
                    grp.style.display = (t.value === 'Specific Member') ? 'block' : 'none';
                }
                document.addEventListener('DOMContentLoaded', function(){
                    var t = document.getElementById('notify_type');
                    if(t){
                        t.addEventListener('change', toggleMemberField);
                        toggleMemberField();
                    }
                });
            })();
        </script>
    @endpush
@endsection
