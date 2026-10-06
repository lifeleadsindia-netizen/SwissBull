@extends('member.layouts.main')
@section('title','Direct Team')
@section('container')
@include('member.income._income-styles')

	   <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body inc-page">
            <div class="container-fluid py-4">
                <!-- row -->
                <div class="row">
                    <div class="col-12">
                        <div class="col-12">
                            <div class="inc-card">
                                <div class="inc-card-header">
                                    <div class="inc-header-icon"><i class="fa-solid fa-users"></i></div>
                                    <div>
                                        <div class="inc-header-title">List Of Direct Members</div>
                                        <div class="inc-header-sub">Your direct team network</div>
                                    </div>
                                </div>
                                <div class="inc-table-shell">
                                    <table id="example3" class="inc-table display dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Image</th>
                                                <th>Joining Date</th>
                                                <th>Member Id</th>
                                                <th>Name</th>
                                                <!--<th>Mobile</th>-->
                                                <th>Downline</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1;  @endphp
                                            @foreach ($directData as $list)
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>
                                                        @if ($list['profile_image']=='')
                                                            <img src="{{asset('uploads/avatar.jpg')}}" class="img-fluid img-thumbnail" width="50" alt="">
                                                        @else
                                                            <img src="{{asset('uploads')}}/{{$list['profile_image']}}" width="50" class="img-fluid img-thumbnail" alt="">
                                                        @endif
                                                    </td>
                                                    <td> {{ __( date('d-m-Y', strtotime($list['created_at'])))}}</td>
                                                    <td>{{ __($list['memberid'])}}</td>
                                                    <td>{{ __($list['name'])}}</td>
                                                    <!--<td>({{ __($list['phonecode']) }}) {{ __($list['mobile'])}}</td>-->
                                                    <td>{{ __($list['downline'])}}</td>
                                                    <td>
                                                        @if ($list['status']=='Active')
                                                            <div class="badge badge-pill bg-success ">{{ __($list['status'])}}</div>
                                                        @elseif ($list['status']=='Temp')
                                                            <div class="badge badge-pill bg-primary ">Inactive</div>
                                                        @else
                                                            <div class="badge badge-pill bg-danger ">{{ __($list['status'])}}</div>
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
