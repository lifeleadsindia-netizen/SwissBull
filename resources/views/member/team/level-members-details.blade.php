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
                            <div class="card-header">
                                <h4 class="card-title">Level Member Table</h4>
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
                                                <td> {{ date('d-m-Y', strtotime($list['created_at'])) }}</td>
                                                <td>{{ $list['memberid'] }}</td>
                                                <td>{{ $list['name'] }}</td>
                                                <td>{{ $list['sponsorid'] }} </td>

                                                <td>{{ $list['downline'] }}</td>
                                                <td>
                                                    @if ($list['status'] == 'Active')
                                                        <div class="badge badge-pill bg-success ">{{ __($list['status']) }}
                                                        </div>
                                                    @elseif ($list['status'] == 'Temp')
                                                        <div class="badge badge-pill bg-primary ">Inactive
                                                        </div>
                                                    @else
                                                        <div class="badge badge-pill bg-danger ">{{ __($list['status']) }}
                                                        </div>
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
