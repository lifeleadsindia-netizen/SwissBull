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
                                            @php
                                                $suffixes = [1 => '1st', 2 => '2nd', 3 => '3rd', 4 => '4th', 5 => '5th', 6 => '6th', 7 => '7th', 8 => '8th', 9 => '9th', 10 => '10th'];
                                            @endphp
                                            @for ($lvl = 1; $lvl <= 10; $lvl++)
                                                @php
                                                    $lvlCount = totalsingleLevel($data['memberid'], (string) $lvl);
                                                    $lvlLabel = ($suffixes[$lvl] ?? ($lvl . 'th')) . ' Level';
                                                @endphp
                                                <tr>
                                                    <td>{{ $lvl }}</td>
                                                    <td>{{ __($lvlLabel) }}</td>
                                                    <td><div class="badge badge-pill bg-success">{{ $lvlCount }}</div></td>
                                                    <td>
                                                        <a class="btn btn-primary level-view-btn"
                                                           href="{{ route('member.level-members-details', ['level' => $lvl], false) }}"
                                                           onclick="window.location.href='{{ route('member.level-members-details', ['level' => $lvl], false) }}';"
                                                           style="cursor: pointer !important; position: relative !important; z-index: 20 !important; display: inline-block !important;">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endfor
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
