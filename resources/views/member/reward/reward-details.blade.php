@extends('member.layouts.main')
@section('title', 'Reward Details')
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
                                <h4 class="card-title">Reward Details & Income</h4>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-pills mb-4 light">
                                    {{-- <li class=" nav-item">
                                        <a href="#navpills-1" class="nav-link active" data-bs-toggle="tab"
                                            aria-expanded="false">Reward Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#navpills-2" class="nav-link" data-bs-toggle="tab"
                                            aria-expanded="false">Reward Details</a>
                                    </li> --}}
                                </ul>
                                <div class="tab-content">
                                    <div id="navpills-1" class="tab-pane active">
                                        <div class="row">
                                            <div class="col-xl-4 col-lg-6 col-sm-6">
                                                <div class="widget-stat card bg-primary">
                                                    <div class="card-body p-4">
                                                        <div class="media ai-icon">
                                                            <span class="me-3 bgl-white text-primary">
                                                                <!-- <i class="ti-user"></i> -->
                                                                <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg"
                                                                    width="30" height="30" viewBox="0 0 24 24"
                                                                    fill="none" stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-user">
                                                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2">
                                                                    </path>
                                                                    <circle cx="12" cy="7" r="4"></circle>
                                                                </svg>
                                                            </span>
                                                            <div class="media-body text-white">
                                                                <p class="mb-1">Rank</p>
                                                                <h4 class="mb-0 text-white">
                                                                    {{ $data->rank }}
                                                                </h4>
                                                                {{-- <span class="badge badge-primary">+3.5%</span> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-lg-6 col-sm-6">
                                                <div class="widget-stat card bg-warning">
                                                    <div class="card-body p-4">
                                                        <div class="media ai-icon">
                                                            <span class="me-3 bgl-warning text-warning">
                                                                <svg id="icon-revenue" xmlns="http://www.w3.org/2000/svg"
                                                                    width="30" height="30" viewBox="0 0 24 24"
                                                                    fill="none" stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-dollar-sign">
                                                                    <line x1="12" y1="1" x2="12"
                                                                        y2="23"></line>
                                                                    <path
                                                                        d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                            @php
                                                                if ($first) {
                                                                    $first_biz = $first->team_biz;
                                                                } else {
                                                                    $first_biz = 0;
                                                                }

                                                                if ($rest) {
                                                                    $rest_biz = $rest;
                                                                } else {
                                                                    $rest_biz = 0;
                                                                }

                                                            @endphp
                                                            <div class="media-body text-white">
                                                                <p class="mb-1">Strong Leg 60%</p>
                                                                <h4 class="mb-0 text-white">
                                                                    @if ($first)
                                                                        {{ $first->team_biz }} FLT
                                                                    @else
                                                                        00
                                                                    @endif
                                                                </h4>
                                                                {{-- {{-- <span class="badge badge-warning">+3.5%</span> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-4  col-lg-6 col-sm-6">
                                                <div class="widget-stat card bg-danger">
                                                    <div class="card-body  p-4">
                                                        <div class="media ai-icon">
                                                            <span class="me-3 bgl-danger text-danger">
                                                                <svg id="icon-revenue" xmlns="http://www.w3.org/2000/svg"
                                                                    width="30" height="30" viewBox="0 0 24 24"
                                                                    fill="none" stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-dollar-sign">
                                                                    <line x1="12" y1="1" x2="12"
                                                                        y2="23"></line>
                                                                    <path
                                                                        d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6">
                                                                    </path>
                                                                </svg>
                                                            </span>
                                                            <div class="media-body text-white">
                                                                <p class="mb-1">Rest Legs 40%</p>
                                                                <h4 class="mb-0  text-white">
                                                                    @if ($rest)
                                                                        {{ $rest }} FLT
                                                                    @else
                                                                        00
                                                                    @endif

                                                                </h4>
                                                                {{-- <span class="badge badge-danger">-3.5%</span> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class="table table-striped" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Reward No.</th>
                                                        <th>Reward Rank</th>
                                                        <th>Required 60%</th>
                                                        <th>Achieved 60%</th>
                                                        <th>Required 40%</th>
                                                        <th>Achieved 40%</th>
                                                        <th>Reward</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Fighter 1</td>
                                                        <td>150 FLT</td>
                                                        <td>
                                                            @if ($first_biz > 150)
                                                                150
                                                            @else
                                                                {{ $first_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>100 FLT</td>
                                                        <td>
                                                            @if ($rest_biz > 100)
                                                                100
                                                            @else
                                                                {{ $rest_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>5 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 150 && $rest_biz >= 100)
                                                                <span class="btn btn-success">Achieved</span>
                                                            @else
                                                                <span class="btn btn-danger">Not Achieved</span>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>Fighter 2</td>
                                                        <td>300 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 300)
                                                                300
                                                            @elseif ($first_biz <= 300)
                                                                {{ $first_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>200 FLT</td>
                                                        <td>
                                                            @if ($rest_biz >= 200)
                                                                200
                                                            @elseif ($rest_biz <= 200)
                                                                {{ $rest_biz }}
                                                            @endif
                                                            FLT
                                                        </td>

                                                        <td>15 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 300 && $rest_biz >= 200)
                                                                <span class="btn btn-success">Achieved</span>
                                                            @else
                                                                <span class="btn btn-danger">Not Achieved</span>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Fighter 3</td>
                                                        <td>900 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 900)
                                                                900
                                                            @elseif ($first_biz <= 900)
                                                                {{ $first_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>600 FLT</td>
                                                        <td>
                                                            @if ($rest_biz >= 600)
                                                                600
                                                            @elseif ($rest_biz <= 600)
                                                                {{ $rest_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>35 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 900 && $rest_biz >= 600)
                                                                <span class="btn btn-success">Achieved</span>
                                                            @else
                                                                <span class="btn btn-danger">Not Achieved</span>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>4</td>
                                                        <td>Fighter 4</td>
                                                        <td>1800 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 1800)
                                                                1800
                                                            @elseif ($first_biz <= 1800)
                                                                {{ $first_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>1200 FLT</td>
                                                        <td>
                                                            @if ($rest_biz >= 1200)
                                                                1200
                                                            @elseif ($rest_biz <= 1200)
                                                                {{ $rest_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>50 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 1800 && $rest_biz >= 1200)
                                                                <span class="btn btn-success">Achieved</span>
                                                            @else
                                                                <span class="btn btn-danger">Not Achieved</span>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>5</td>
                                                        <td>Fighter 5</td>
                                                        <td>3000 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 3000)
                                                                3000
                                                            @elseif ($first_biz <= 3000)
                                                                {{ $first_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>2000 FLT</td>
                                                        <td>
                                                            @if ($rest_biz >= 2000)
                                                                2000
                                                            @elseif ($rest_biz <= 2000)
                                                                {{ $rest_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>125 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 3000 && $rest_biz >= 2000)
                                                                <span class="btn btn-success">Achieved</span>
                                                            @else
                                                                <span class="btn btn-danger">Not Achieved</span>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>6</td>
                                                        <td>Fighter 6</td>
                                                        <td>12000 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 12000)
                                                                12000
                                                            @elseif ($first_biz <= 12000)
                                                                {{ $first_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>8000 FLT</td>
                                                        <td>
                                                            @if ($rest_biz >= 8000)
                                                                8000
                                                            @elseif ($rest_biz <= 8000)
                                                                {{ $rest_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>200 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 12000 && $rest_biz >= 8000)
                                                                <span class="btn btn-success">Achieved</span>
                                                            @else
                                                                <span class="btn btn-danger">Not Achieved</span>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>7</td>
                                                        <td>Fighter 7</td>
                                                        <td>30000 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 30000)
                                                                30000
                                                            @elseif ($first_biz <= 30000)
                                                                {{ $first_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>20000 FLT</td>
                                                        <td>
                                                            @if ($rest_biz >= 20000)
                                                                20000
                                                            @elseif ($rest_biz <= 20000)
                                                                {{ $rest_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>500 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 30000 && $rest_biz >= 20000)
                                                                <span class="btn btn-success">Achieved</span>
                                                            @else
                                                                <span class="btn btn-danger">Not Achieved</span>
                                                            @endif

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>8</td>
                                                        <td>Fighter 8</td>
                                                        <td>300000 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 300000)
                                                                300000
                                                            @elseif ($first_biz <= 300000)
                                                                {{ $first_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>200000 FLT</td>
                                                        <td>
                                                            @if ($rest_biz >= 200000)
                                                                200000
                                                            @elseif ($rest_biz <= 200000)
                                                                {{ $rest_biz }}
                                                            @endif
                                                            FLT
                                                        </td>
                                                        <td>5000 FLT</td>
                                                        <td>
                                                            @if ($first_biz >= 300000 && $rest_biz >= 200000)
                                                                <span class="btn btn-success">Achieved</span>
                                                            @else
                                                                <span class="btn btn-danger">Not Achieved</span>
                                                            @endif

                                                        </td>
                                                    </tr>

                                                </tbody>

                                            </table>

                                        </div>

                                        <hr>

                                    </div>

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
