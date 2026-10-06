@extends('member.layouts.main')
@section('title','Create Support Ticket')
@section('container')

	   <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <!-- row -->
                <div class="row">
                    <div class="col-12">
                        <div class="col-xl-7 col-lg-7 mb-3">
                            @if (session()->has('successMsg'))
                                <div class="alert alert-success" role="alert">
                                    {{session('successMsg')}}
                                </div>
                            @endif
                            @if (session()->has('failedMsg'))
                                <div class="alert alert-danger" role="alert">
                                    {{session('failedMsg')}}
                                </div>
                            @endif
                            <div class="card ">
                                <div class="card-header">
                                    <h4 class="card-title">Create Support Ticket</h4>
                                </div>
                                <div class="card-body">
                                    <div class="basic-form">
                                        <form action="{{route('createTicket')}}" method="post" enctype="multipart/form-data">
                                            <input type="hidden" id="csrf" value="{{csrf_token()}}">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">UserId</label>
                                                <input id="newPass" type="text" class="form-control"  name="userid" value="{{$data['memberid']}}" readonly>
                                                @error('userid')
                                                    <span class="text-danger"> {{$message}}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Subject</label>
                                                <input id="newPass" type="text" class="form-control" name="subject" placeholder="Enter Subject">
                                                @error('subject')
                                                <span class="text-danger"> {{$message}}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Particular</label>
                                                <textarea  class="form-control " name="issue" placeholder="Enter Your Issue" rows="5" style="min-height:100px"></textarea>
                                                @error('issue')
                                                    <span class="text-danger">{{$message}}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <input type="hidden" name="memberid" value="{{$data['memberid']}}">
                                                <button class="btn btn-primary btn-rounded btn-block mt-3 float-end" >Create New Ticket</button>
                                            </div>
                                        </form>
                                    </div>
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
