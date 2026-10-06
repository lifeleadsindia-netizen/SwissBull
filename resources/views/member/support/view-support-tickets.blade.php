@extends('member.layouts.main')
@section('title','View Support Ticket')
@section('container')

	   <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
                <!-- row -->
                <div class="row">
                    <div class="col-xl-6 col-md-6  col-lg-6">
						<div class="card border-0 pb-0">
							<div class="card-header border-0 pb-0">
								<h4 class="card-title">Ticket ID : {{$support['ticket_id']}} </h4>
                                <p>Status : <span class="btn btn-sm btn-primary">{{$support['status']}}</span> </p>
							</div>
							<div class="card-body p-0">
								<div id="DZ_W_Todo3" class="widget-media dlab-scroll my-4 px-4 height370">
									<ul class="timeline ">
                                        @foreach ($tdata as $list )
                                            <li>
                                                <div class="timeline-panel">
                                                    <div class="media me-2">
                                                        <img src="{{asset('uploads/avatar.jpg')}}"   alt="image" width="50">
                                                    </div>
                                                    <div class="media-body">
                                                        @if ($list['written_by'] == 'Member')
                                                            <h5 class="preview-subject text-primary">{{$data['first_name']}} {{$data['last_name']}}</h5>
                                                        @else
                                                            <h5 class="preview-subject text-danger">Support Team</h5>
                                                        @endif 
                                                        <p class="mb-1">{{$list['text']}}</p>
                                                        <small class="text-muted">{{date('d-M-Y H:i:s', strtotime($list['created_at'])) }}</small>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
									</ul>
								</div>
							</div>
						</div>
					</div>
                    @if ($support['status']!='Closed')
                        <div class="col-xl-6 col-md-6  col-lg-6">
                            @if (session()->has('createMsg'))
                                <div class="alert alert-success" role="alert">
                                    {{session('createMsg')}}
                                </div>
                            @endif
                            <div class="card border-0 pb-0">                        
                                <div class="card-body">
                                    <form method="post" action="{{route('MbReplyTicket')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                          <label for="exampleInputUsername1">Reply </label>
                                          <textarea  class="form-control form-control-lg " name="reply" placeholder="Enter Your Reply" rows="5" style="min-height:100px"></textarea>
                                          @error('reply')
                                              <span class="text-danger">{{$message}}</span>
                                          @enderror
                                        </div>
                                        <div class="mb-3">
                                            <input type="hidden" name="ticketid" value="{{$support['ticket_id']}}">
                                            <button type="submit" class="btn btn-primary me-2 float-end" >Your Reply</button>
                                            <a class="btn btn-primary me-2 float-end"  href="{{url('member/support/support-tickets')}}">Back</a>
                                        </div>
                                        
                                      </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
@endsection	
