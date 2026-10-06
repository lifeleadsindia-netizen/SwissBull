@extends('member.layouts.main')
@section('title','Support Ticket')
@section('container')

	   <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <!-- row -->
                <div class="row">
                    @foreach ($support as $list )
                    <div class="col-md-4 grid-margin stretch-card">
                        <div class="card">

                            <div class="card-body">
                                <h5 class="card-title">ID : {{$list['ticket_id']}} <span class="float-end "><a href="{{url('member/support/view-support-ticket')}}/{{$list['ticket_id']}}" class="btn btn-sm btn-warning">View</a></span> </h5>
                                <p class="card-description p-0 m-0">Subject : {{$list['subject']}}</p>
                                <p class="card-description p-0 m-0">Created Date : {{date('d-M-Y', strtotime($list['created_at']))}}</p>
                                <p class="card-description p-0 m-0">Status : {{$list['status']}}</p>
                                <p class="card-description p-0 m-0">Unread : <span class="btn btn-sm py-0 btn-warning">{{AdmUnread($list['ticket_id'])}}</span></p>
                                   {{-- @if (AdmUnread($list['ticket_id']) == 0)
                                        <span class="badge badge-dark">{{AdmUnread($list['ticket_id'])}}</span></p>
                                   @else

                                   @endif --}}
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
@endsection
