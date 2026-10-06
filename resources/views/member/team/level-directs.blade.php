@extends('member.layouts.main')
@section('title','Level Directs')
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
                                    <h4 class="card-title">List Of Level Direct Members</h4>
                                </div>
                                <div class="card-body" style="overflow:auto">
                                    <table id="example3" class="display min-w850 dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Level No</th>
                                                <th>Total Members</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>{{ __('1st Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '1')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'1'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>{{ __('2nd Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '2')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'2'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>{{ __('3rd Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '3')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'3'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>{{ __('4th Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '4')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'4'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>{{ __('5th Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '5')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'5'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>{{ __('6th Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '6')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'6'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>{{ __('7th Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '7')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'7'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>{{ __('8th Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '8')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'8'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>{{ __('9th Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '9')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'9'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>{{ __('10th Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '10')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'10'}}">View</a></td>
                                            </tr>
                                            {{-- <tr>
                                                <td>11</td>
                                                <td>{{ __('11th Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '11')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'11'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td>{{ __('12th Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '12')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'12'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>13</td>
                                                <td>{{ __('13th Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '13')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'13'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>14</td>
                                                <td>{{ __('14th Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '14')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'14'}}">View</a></td>
                                            </tr>
                                            <tr>
                                                <td>15</td>
                                                <td>{{ __('15th Level')}}</td>
                                                <td><div class="badge badge-pill bg-success ">{{totalsingleLevel($data['memberid'], '15')}}</div></td>
                                                <td><a class="btn btn-primary" href="{{url('member/level-members-details')}}/{{'15'}}">View</a></td>
                                            </tr> --}}
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
