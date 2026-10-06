@extends('admin.layouts.main')
@section('title', 'Tutorial Videos Management')
@section('content')
    @push('head')
        <style>
            .video-thumb-preview {
                width: 96px;
                height: 56px;
                object-fit: cover;
                border-radius: 6px;
                background: #111;
                border: 1px solid #ddd;
            }
            .badge-status-active {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 5px;
                background-color: #28a745;
                color: #ffffff !important;
                padding: 5px 12px;
                border-radius: 50px;
                font-size: 11px;
                font-weight: 600;
                line-height: 1;
                white-space: nowrap;
                text-decoration: none !important;
                vertical-align: middle;
                transition: all 0.2s ease;
                box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
            }

            .badge-status-active:hover {
                background-color: #218838;
                color: #ffffff !important;
                box-shadow: 0 3px 6px rgba(40, 167, 69, 0.3);
            }

            .badge-status-inactive {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 5px;
                background-color: #6c757d;
                color: #ffffff !important;
                padding: 5px 12px;
                border-radius: 50px;
                font-size: 11px;
                font-weight: 600;
                line-height: 1;
                white-space: nowrap;
                text-decoration: none !important;
                vertical-align: middle;
                transition: all 0.2s ease;
                box-shadow: 0 2px 4px rgba(108, 117, 125, 0.2);
            }

            .badge-status-inactive:hover {
                background-color: #5a6268;
                color: #ffffff !important;
                box-shadow: 0 3px 6px rgba(108, 117, 125, 0.3);
            }

            .badge-status-active i,
            .badge-status-inactive i {
                font-size: 11px;
                line-height: 1;
                margin: 0;
                padding: 0;
            }
        </style>
    @endpush

<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-play-circle bg-green"></i>
                    <div class="d-inline">
                        <h5>{{ __('Tutorial Videos Management') }}</h5>
                        <span>{{ __('Add unlimited step-by-step tutorial guides and instructional videos for members') }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('hdgteyusjasget/dashboard') }}"><i class="ik ik-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">{{ __('Media') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Tutorial Videos') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if (session()->has('successMsg'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ik ik-check-circle me-1"></i> {{ session('successMsg') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session()->has('delMsg'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ik ik-trash me-1"></i> {{ session('delMsg') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Add / Edit Tutorial Video Form -->
        <div class="col-xl-4 col-lg-5 col-md-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 id="tutVideoFormCardTitle">{{ __('Add Tutorial Video') }}</h3>
                    <button type="button" class="btn btn-sm btn-outline-secondary d-none" id="cancelTutVideoEditBtn" onclick="resetTutVideoForm()">
                        <i class="ik ik-x"></i> Cancel
                    </button>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.media.tutorial-videos.save') }}" method="POST" enctype="multipart/form-data" id="tutVideoForm">
                        @csrf
                        <input type="hidden" name="id" id="tut_video_id">

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('Video Title') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="tut_video_title" placeholder="e.g. How to Deposit USDT & Activate Account" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('Tag / Topic') }}</label>
                            <input type="text" class="form-control" name="tag" id="tut_video_tag" placeholder="e.g. Activation Guide, Staking Guide">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('Video Source Type') }} <span class="text-danger">*</span></label>
                            <select class="form-control" name="video_source" id="tut_video_source" onchange="toggleTutVideoSourceInputs()" required>
                                <option value="upload">Upload Video File (MP4, MKV, MOV, WEBM, AVI, etc.)</option>
                                <option value="url">External Video URL (YouTube, Vimeo, MP4)</option>
                            </select>
                        </div>

                        <!-- Upload file container -->
                        <div class="form-group mb-3" id="tutVideoUploadContainer">
                            <label class="form-label font-weight-bold">{{ __('Video File') }} <span id="tutVideoFileRequiredStar" class="text-danger">*</span></label>
                            <input type="file" name="video_file" id="tut_video_file" class="form-control" accept="video/*" onchange="validateTutVideoFileSize(this)">
                            <small class="text-muted d-block mt-1">Upload video file (MP4, MKV, MOV, WEBM, AVI, 3GP, etc.). Max size: 200MB.</small>
                            <div id="currentTutVideoFileDisplay" class="mt-2 d-none">
                                <span class="badge badge-info p-2" id="currentTutVideoFileName"></span>
                            </div>
                        </div>

                        <!-- External URL container -->
                        <div class="form-group mb-3 d-none" id="tutVideoUrlContainer">
                            <label class="form-label font-weight-bold">{{ __('External Video URL') }} <span class="text-danger">*</span></label>
                            <input type="url" name="video_url" id="tut_video_url" class="form-control" placeholder="https://www.youtube.com/watch?v=... or https://youtu.be/...">
                            <small class="text-muted d-block mt-1">Supports YouTube, Vimeo, or direct MP4 links.</small>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('Custom Thumbnail (Optional)') }}</label>
                            <input type="file" name="thumbnail" id="tut_video_thumbnail" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
                            <small class="text-muted d-block mt-1">If using YouTube, YouTube thumbnail is used automatically if left empty.</small>
                            <div id="currentTutThumbPreviewContainer" class="mt-2 d-none">
                                <img src="" id="currentTutThumbImg" class="video-thumb-preview" alt="Thumbnail">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label font-weight-bold">{{ __('Duration') }}</label>
                                <input type="text" class="form-control" name="duration" id="tut_video_duration" placeholder="e.g. 05:30 or Step-by-Step">
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label font-weight-bold">{{ __('Sort Order') }}</label>
                                <input type="number" class="form-control" name="sort_order" id="tut_video_sort_order" value="0" min="0">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" id="tut_video_status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('Description') }}</label>
                            <textarea class="form-control" name="description" id="tut_video_description" rows="3" placeholder="Explain the tutorial steps covered in this video..."></textarea>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success btn-block py-2 font-weight-bold" id="submitTutVideoBtn">
                                <i class="ik ik-upload me-1"></i> {{ __('Save Tutorial Video') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tutorial Videos Table -->
        <div class="col-xl-8 col-lg-7 col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h3>{{ __('Tutorial Videos List') }} ({{ $videos->total() }})</h3>
                    
                    <!-- Search Filter Form -->
                    <form action="{{ route('admin.media.tutorial-videos') }}" method="GET" class="d-flex align-items-center">
                        <div class="input-group input-group-sm" style="width: 230px;">
                            <input type="text" name="search" class="form-control" placeholder="Search tutorial videos..." value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-secondary"><i class="ik ik-search"></i></button>
                                @if(request('search'))
                                    <a href="{{ route('admin.media.tutorial-videos') }}" class="btn btn-outline-danger"><i class="ik ik-x"></i></a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>{{ __('Preview') }}</th>
                                    <th>{{ __('Title & Topic') }}</th>
                                    <th>{{ __('Source') }}</th>
                                    <th class="text-center">{{ __('Order') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($videos as $index => $item)
                                    <tr>
                                        <td>{{ $videos->firstItem() + $index }}</td>
                                        <td>
                                            <a href="{{ $item->video_play_url }}" target="_blank" title="Preview Video">
                                                <img src="{{ $item->thumbnail_url }}" class="video-thumb-preview" alt="{{ $item->title }}" onerror="this.onerror=null;this.src='{{ asset('uassets/images/default-video-thumbnail.png') }}';">
                                            </a>
                                            <div class="text-muted" style="font-size: 11px;">{{ $item->duration ?? 'Guide' }}</div>
                                        </td>
                                        <td>
                                            <strong>{{ $item->title }}</strong>
                                            @if ($item->tag)
                                                <br><span class="badge badge-success" style="font-size: 10px;">{{ $item->tag }}</span>
                                            @endif
                                            @if ($item->description)
                                                <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($item->description, 60) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->video_source === 'url')
                                                @if ($item->isYouTube())
                                                    <span class="badge badge-danger"><i class="fab fa-youtube me-1"></i> YouTube</span>
                                                @else
                                                    <span class="badge badge-info"><i class="ik ik-link me-1"></i> Link</span>
                                                @endif
                                            @else
                                                <span class="badge badge-secondary"><i class="ik ik-file me-1"></i> Uploaded MP4</span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-secondary">{{ $item->sort_order }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('admin.media.tutorial-videos.status', $item->id) }}" 
                                               class="text-decoration-none d-inline-block" 
                                               title="Click to toggle status">
                                                @if ($item->status === 'active')
                                                    <span class="badge-status-active"><i class="ik ik-check"></i> Active</span>
                                                @else
                                                    <span class="badge-status-inactive"><i class="ik ik-slash"></i> Inactive</span>
                                                @endif
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ $item->video_play_url }}" target="_blank" class="btn btn-sm btn-outline-success mr-1" title="Play Tutorial">
                                                    <i class="ik ik-play"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-info text-white mr-1" 
                                                    title="Edit Tutorial Video"
                                                    onclick="editTutVideo({{ json_encode($item) }})">
                                                    <i class="ik ik-edit-2"></i>
                                                </button>
                                                <a href="{{ route('admin.media.tutorial-videos.delete', $item->id) }}" 
                                                   class="btn btn-sm btn-danger text-white" 
                                                   title="Delete Video"
                                                   onclick="return confirm('Are you sure you want to delete this tutorial video?');">
                                                    <i class="ik ik-trash-2"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="ik ik-video-off" style="font-size: 28px;"></i>
                                            <p class="mt-2 mb-0">No tutorial videos found. Add your first tutorial video using the form.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <div class="mt-3 d-flex justify-content-end">
                        {{ $videos->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        function toggleTutVideoSourceInputs() {
            var source = document.getElementById('tut_video_source').value;
            if (source === 'url') {
                document.getElementById('tutVideoUrlContainer').classList.remove('d-none');
                document.getElementById('tutVideoUploadContainer').classList.add('d-none');
                document.getElementById('tut_video_url').required = true;
                document.getElementById('tut_video_file').required = false;
            } else {
                document.getElementById('tutVideoUrlContainer').classList.add('d-none');
                document.getElementById('tutVideoUploadContainer').classList.remove('d-none');
                document.getElementById('tut_video_url').required = false;
                var isEdit = document.getElementById('tut_video_id').value !== '';
                document.getElementById('tut_video_file').required = !isEdit;
            }
        }

        function editTutVideo(item) {
            document.getElementById('tut_video_id').value = item.id;
            document.getElementById('tut_video_title').value = item.title;
            document.getElementById('tut_video_tag').value = item.tag || '';
            document.getElementById('tut_video_source').value = item.video_source;
            document.getElementById('tut_video_duration').value = item.duration || '';
            document.getElementById('tut_video_sort_order').value = item.sort_order;
            document.getElementById('tut_video_status').value = item.status;
            document.getElementById('tut_video_description').value = item.description || '';
            document.getElementById('tut_video_url').value = item.video_url || '';

            toggleTutVideoSourceInputs();

            document.getElementById('tutVideoFormCardTitle').innerText = 'Edit Tutorial Video (#' + item.id + ')';
            document.getElementById('submitTutVideoBtn').innerHTML = '<i class="ik ik-check me-1"></i> Update Tutorial Video';
            document.getElementById('cancelTutVideoEditBtn').classList.remove('d-none');
            document.getElementById('tutVideoFileRequiredStar').innerText = '(optional if keeping current file)';

            if (item.video_file) {
                document.getElementById('currentTutVideoFileName').innerText = 'Current file: ' + item.video_file;
                document.getElementById('currentTutVideoFileDisplay').classList.remove('d-none');
            }

            if (item.thumbnail_url) {
                document.getElementById('currentTutThumbImg').src = item.thumbnail_url;
                document.getElementById('currentTutThumbPreviewContainer').classList.remove('d-none');
            }

            window.scrollTo({ top: 120, behavior: 'smooth' });
        }

        function resetTutVideoForm() {
            document.getElementById('tutVideoForm').reset();
            document.getElementById('tut_video_id').value = '';
            document.getElementById('tutVideoFormCardTitle').innerText = 'Add Tutorial Video';
            document.getElementById('submitTutVideoBtn').innerHTML = '<i class="ik ik-upload me-1"></i> Save Tutorial Video';
            document.getElementById('cancelTutVideoEditBtn').classList.add('d-none');
            document.getElementById('tutVideoFileRequiredStar').innerText = '*';
            document.getElementById('currentTutVideoFileDisplay').classList.add('d-none');
            document.getElementById('currentTutThumbPreviewContainer').classList.add('d-none');
            toggleTutVideoSourceInputs();
        }

        function validateTutVideoFileSize(input) {
            if (input.files && input.files[0]) {
                var file = input.files[0];
                var maxBytes = 200 * 1024 * 1024; // 200MB
                if (file.size > maxBytes) {
                    var sizeMB = (file.size / (1024 * 1024)).toFixed(1);
                    alert('Selected file size exceeds 200MB limit (Selected: ' + sizeMB + 'MB).\nPlease choose a video file smaller than 200MB or provide a YouTube URL.');
                    input.value = '';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleTutVideoSourceInputs();
        });

        document.getElementById('tutVideoForm').addEventListener('submit', function(e) {
            var btn = document.getElementById('submitTutVideoBtn');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Uploading video, please wait...';
            btn.style.pointerEvents = 'none';
            btn.style.opacity = '0.75';
        });
    </script>
@endpush
@endsection
