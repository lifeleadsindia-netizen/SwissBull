@extends('member.layouts.main')
@section('title', 'Level Members Details')
@section('container')

    <!--**********************************
                    Content body start
                ***********************************-->
    <div class="content-body">
        <div class="container-fluid">
            <!-- row -->
            <div class="row">
                <div class="col-12">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Level {{ $levelNo }} Members ({{ $users->count() }})</h4>
                                <a href="{{ route('member.level-details', [], false) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fa fa-arrow-left me-1"></i> Back to Level List
                                </a>
                            </div>
                            <div class="card-body" style="overflow:auto">
                                <table id="example3" class="display min-w850 dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Image</th>
                                            <th>Joining Date</th>
                                            <th>Member Id</th>
                                            <th>Name</th>
                                            <th>Sponsor Detail</th>
                                            <th>Downline</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($users as $list)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>
                                                    @if ($list['profile_image'] == '')
                                                        <img src="{{ asset('uploads/avatar.jpg') }}" width="50"
                                                            class="img-fluid img-thumbnail" alt="">
                                                    @else
                                                        <img src="{{ asset('uploads') }}/{{ $list['profile_image'] }}"
                                                            width="50" class="img-fluid img-thumbnail" alt="">
                                                    @endif
                                                </td>
                                                <td>{{ !empty($list['created_at']) ? date('d-m-Y', strtotime((string)$list['created_at'])) : 'N/A' }}</td>
                                                <td>{{ $list['memberid'] }}</td>
                                                <td>{{ $list['name'] }}</td>
                                                <td>{{ $list['sponsorid'] }} </td>

                                                <td>{{ $list['downline'] }}</td>
                                                <td>
                                                    @if ($list['status'] == 'Active')
                                                        <div class="badge badge-pill bg-success">Active</div>
                                                    @elseif ($list['status'] == 'Deactive')
                                                        <div class="badge badge-pill bg-danger">Deactive</div>
                                                    @elseif ($list['status'] == 'Temp')
                                                        <div class="badge badge-pill bg-warning text-dark">Inactive</div>
                                                    @else
                                                        <div class="badge badge-pill bg-secondary">{{ __($list['status']) }}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--**********************************
                    Content body end
                ***********************************-->
@endsection
