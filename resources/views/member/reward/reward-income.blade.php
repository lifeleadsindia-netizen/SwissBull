
@extends('member.layouts.main')
@section('title','Reward Income')
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
                                    <h4 class="card-title">Reward Income</h4>
                                </div>
                                <div class="card-body">
                                    <table id="responsiveTable" class="display responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Date</th>
                                                <th>Rank </th>
                                                <th>Matching </th>
                                                <th>Reward </th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1;  @endphp
                                            @foreach ($rData as $list )
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{date('d-m-Y', strtotime($list['created_at']))}}</td>
                                                    <td>{{ $list['rank']}}</td>
                                                    <td>{{ $list['matching']}}</td>
                                                    <td>{{ $list['reward']}} FLT</td>
                                                    <td>
                                                        @if ($list['status']=='Unpaid')
                                                        <div class="btn btn-danger">{{$list['status']}}</div>
                                                        @elseif ($list['status']=='Paid')
                                                        <div class="btn btn-success">{{$list['status']}}</div>
                                                        @else
                                                        <div class="btn btn-warning">{{$list['status']}}</div>
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
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
@endsection
