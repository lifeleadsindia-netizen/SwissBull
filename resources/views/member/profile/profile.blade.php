@extends('member.layouts.main')
@section('title', 'Profile')
@section('container')

    <!--********************************** Content body start ***********************************-->

    <div class="content-body">
        <div class="container-fluid">
            <!-- row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="profile card card-body px-3 pt-3 pb-0">
                        <div class="profile-head">
                            <div class="photo-content">
                                <div class="cover-photo rounded"></div>
                            </div>
                            <div class="profile-info">
                                <div class="profile-photo">
                                    @if ($data['profile_image'] != '')
                                        <img src="{{ asset('uploads') }}/{{ $data['profile_image'] }}" alt=""
                                            class="img-fluid rounded-circle">
                                    @else
                                        <img src="{{ asset('uploads/avatar.jpg') }}" class="img-fluid rounded-circle"
                                            alt="">
                                    @endif
                                </div>
                                <div class="profile-details">
                                    <div class="profile-name px-3 pt-2">
                                        <h4 class="text-primary mb-0">{{ $data['name'] }}</h4>
                                        <p>Name</p>
                                    </div>
                                    <div class="profile-email px-2 pt-2">
                                        <h4 class="text-primary mb-0">{{ $data['email'] }}</h4>
                                        <p>Email</p>
                                    </div>
                                    {{-- <div class="dropdown ms-auto">
											<div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown">
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="12" cy="5" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="19" r="2"></circle></g></svg>
											</div>
											<ul class="dropdown-menu dropdown-menu-end">
												<li class="dropdown-item"><a href="javascript:void(0);"><i class="fa fa-user-circle text-primary me-2"></i> View profile</a></li>
												<li class="dropdown-item"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addCloseFriendModal"><i class="fa fa-users text-primary me-2"></i> Add to close friends</a></li>
												<li class="dropdown-item"><a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#createGroupModal"><i class="fa fa-plus text-primary me-2"></i> Create group</a></li>
												<li class="dropdown-item"><a href="javascript:void(0);" class="text-danger sweet-confirm"><i class="fa fa-ban text-danger me-2"></i> Block</a></li>
											</ul>
										</div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-8 col-lg-8">
                    <div class="alert alert-warning mt-3 mb-2" role="alert">
                        <strong>⚠️ Warning:</strong>
                        You can submit your profile details only one time. Double-check all information carefully. Once
                        submitted, it cannot be edited.
                    </div>
                    @if (session()->has('successMsg'))
                        <div class="alert alert-success" role="alert">
                            {{ session('successMsg') }}
                        </div>
                    @endif
                    @if (session()->has('failedMsg'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('failedMsg') }}
                        </div>
                    @endif
                    @if ($data['profile_status'] != 'Updated')
                        <div class="card profile-card card-bx m-b30 h-auto">
                            <div class="card-header">
                                <h4 class="card-title">Update Profile Details</h4>
                            </div>
                            <form class="profile-form" action="{{ route('insertProfile') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="Name">Name</label>
                                                <input type="text" class="form-control" value="{{ $data['name'] }}"
                                                    id="Name" name="name">
                                            </div>
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="Email">Email address</label>
                                                <input type="text" class="form-control" value="{{ $data['email'] }}"
                                                    name="email" id="Email">
                                            </div>
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label required">Mobile</label>
                                                <div class="row">
                                                    <div class="col-2 me-0 pe-0">
                                                        <input type="text" class="form-control px-2"
                                                            value="{{ $data['phonecode'] }}" readonly
                                                            style="border-top-right-radius: 0px; border-bottom-right-radius:0px;">
                                                    </div>
                                                    <div class="col-10 ms-0 ps-0">
                                                        <input type="text" class="form-control" name="mobile"
                                                            value="{{ $data['mobile'] }}" minlength="4"
                                                            placeholder="Enter Mobile"
                                                            style="border-top-left-radius: 0px; border-bottom-left-radius:0px; width: 100%">
                                                    </div>
                                                </div>
                                                @error('mobile')
                                                    <span class="text-danger"> {{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label class="form-label">Profile Image</label>
                                                <input class="form-control" name="profile_image" type="file"
                                                    id="formFile" value="{{ $data['profile_image'] }}">
                                            </div>
                                            @error('profile_image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="member_wallet">Member Wallet</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $data['member_wallet'] }}" id="member_wallet"
                                                    name="member_wallet" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer justify-content-center">
                                    <input type="hidden" name="id" value="{{ $data['id'] }}">
                                    <button class="btn btn-primary btn-sm">UPDATE DETAILS</button>

                                </div>
                            </form>
                        </div>
                    @else
                        <div class="card profile-card card-bx m-b30 h-auto">
                            <div class="card-header">
                                <h4 class="card-title">Updated Profile Details</h4>
                            </div>
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="Name">Name</label>
                                            <input type="text" class="form-control" value="{{ $data['name'] }}"
                                                id="Name" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="Email">Email address</label>
                                            <input type="text" class="form-control" value="{{ $data['email'] }}"
                                                id="Email" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="number" class="form-control" value="{{ $data['mobile'] }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label class="form-label">Member Wallet</label>
                                            <input type="text"
                                                class="form-control"value="{{ $data['member_wallet'] }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!--********************************** Content body end ***********************************-->

@endsection
