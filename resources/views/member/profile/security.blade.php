@extends('member.layouts.main')
@section('title', 'Security')
@section('container')
    <!-- Content wrapper -->
    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6 col-xxl-6">
                    @if (session()->has('confirm_passError'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('confirm_passError') }}
                        </div>
                    @endif
                    @if (session()->has('confirm_passSuccess'))
                        <div class="alert alert-success" role="alert">
                            {{ session('confirm_passSuccess') }}
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="mb-0 text-white fs-20">Transaction Password Change</h4>
                        </div>
                        <div class="card-body">
                            <form class="forms-sample" action="{{ route('txnChange') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Old Transaction Password</label>
                                    <div class="">
                                        <input type="password" class="form-control" id="exampleInputUsername2"
                                            name="old_txnpassword" placeholder="Enter Old Transaction Password">
                                    </div>
                                    @error('old_txnpassword')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 ">
                                    <label for="exampleInputUsername2">Transaction Password</label>
                                    <div class="">
                                        <input type="password" class="form-control" id="exampleInputUsername2"
                                            name="txnpassword" placeholder="Enter Transaction Password">
                                    </div>
                                    @error('txnpassword')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail2">Transaction Confirm Password</label>
                                    <div class="">
                                        <input type="password" class="form-control" id="exampleInputEmail2"
                                            name="password_confirmation" placeholder="Enter Transaction Confirm Password">
                                    </div>
                                    @error('txnpassword')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                <input type="hidden" value="{{ $data['id'] }}" name="id">
                                <button class="btn btn-primary me-2 float-end" type="submit">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-xxl-6">
                    @if (session()->has('confirm_pasError'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('confirm_pasError') }}
                        </div>
                    @endif
                    @if (session()->has('confirm_pasSuccess'))
                        <div class="alert alert-success" role="alert">
                            {{ session('confirm_pasSuccess') }}
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="mb-0 text-white fs-20"> Password Change</h4>
                        </div>
                        <div class="card-body">
                            <form class="forms-sample" action="{{ route('passChange') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Old Password</label>
                                    <div class="">
                                        <input type="password" class="form-control" id="exampleInputUsername2"
                                            name="old_password" placeholder="Enter Old Password">
                                    </div>
                                    @error('old_password')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3 ">
                                    <label for="exampleInputUsername2"> Password</label>
                                    <div class="">
                                        <input type="password" class="form-control" id="exampleInputUsername2"
                                            name="password" placeholder="Enter Password">
                                    </div>
                                    @error('password')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail2">Confirm Password</label>
                                    <div class="">
                                        <input type="password" class="form-control" id="exampleInputEmail2"
                                            name="password_confirmation" placeholder="Enter Confirm Password">
                                    </div>
                                    @error('password')
                                        <span class="text-danger"> {{ $message }}</span>
                                    @enderror
                                </div>
                                <input type="hidden" value="{{ $data['id'] }}" name="id">
                                <button class="btn btn-primary me-2 float-end" type="submit">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-xxl-6 mt-5">
                    @if (session()->has('forgetPass'))
                        <div class="alert alert-success" role="alert">
                            {{ session('forgetPass') }}
                        </div>
                    @endif
                    <div class="card" style="height: auto">
                        <div class="card-header border-0">
                            <h4 class="mt-3 mb-2">Forget Transaction Password</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a class="btn btn-warning me-2 btn-block"
                                    href="{{ url('/member/forget-password') }}/{{ $data['id'] }}"
                                    id="forgetPass">Retrieve Transaction Password</a>
                                <small>Your transaction password will be sent to your registered email</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
