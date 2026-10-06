@extends('admin.layouts.main')
@section('title', 'Promotion Banners Management')
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
        <style>
            .banner-preview-thumb {
                width: 90px;
                height: 55px;
                object-fit: cover;
                border-radius: 6px;
                border: 1px solid #e2e8f0;
                box-shadow: 0 2px 4px rgba(0,0,0,0.08);
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
                    <i class="ik ik-image bg-blue"></i>
                    <div class="d-inline">
                        <h5>{{ __('Promotion Banners') }}</h5>
                        <span>{{ __('Manage dynamic promotional banners displayed on the member panel') }}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Promotion Banners') }}</li>
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
        <!-- Add / Edit Banner Form -->
        <div class="col-xl-4 col-lg-5 col-md-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 id="formCardTitle">{{ __('Add New Banner') }}</h3>
                    <button type="button" class="btn btn-sm btn-outline-secondary d-none" id="cancelEditBtn" onclick="resetBannerForm()">
                        <i class="ik ik-x"></i> Cancel
                    </button>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.media.banners.save') }}" method="POST" enctype="multipart/form-data" id="bannerForm">
                        @csrf
                        <input type="hidden" name="id" id="banner_id">

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('Banner Title') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="banner_title" placeholder="e.g. Official Promo Banner #1" required>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('Tag / Category') }}</label>
                            <input type="text" class="form-control" name="tag" id="banner_tag" placeholder="e.g. Math Wallet Ecosystem">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('Banner Image') }} <span id="imageRequiredStar" class="text-danger">*</span></label>
                            <input type="file" name="image" id="banner_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp,image/svg+xml">
                            <small class="text-muted d-block mt-1">Recommended: 1080x1080 or HD JPG/PNG/WebP. Max 10MB.</small>
                            <div id="imagePreviewContainer" class="mt-2 d-none">
                                <span class="text-muted small d-block mb-1">Current Image:</span>
                                <img src="" id="currentImagePreview" class="banner-preview-thumb" alt="Preview">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label font-weight-bold">{{ __('Sort Order') }}</label>
                                <input type="number" class="form-control" name="sort_order" id="banner_sort_order" value="0" min="0">
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label font-weight-bold">{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select class="form-control" name="status" id="banner_status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('External Link (Optional)') }}</label>
                            <input type="url" class="form-control" name="external_link" id="banner_external_link" placeholder="https://...">
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold" id="submitBannerBtn">
                                <i class="ik ik-upload me-1"></i> {{ __('Save Banner') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Banners Table -->
        <div class="col-xl-8 col-lg-7 col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('Promotion Banners List') }} ({{ count($banners) }})</h3>
                    <span class="badge badge-info">{{ __('Dynamic & Member-Sync') }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data_table" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">{{ __('S.No') }}</th>
                                    <th>{{ __('Preview') }}</th>
                                    <th>{{ __('Title & Tag') }}</th>
                                    <th class="text-center">{{ __('Order') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($banners as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ $item->image_url }}" target="_blank" title="View full image">
                                                <img src="{{ $item->image_url }}" class="banner-preview-thumb" alt="{{ $item->title }}">
                                            </a>
                                        </td>
                                        <td>
                                            <strong>{{ $item->title }}</strong>
                                            @if ($item->tag)
                                                <br><span class="badge badge-light border text-muted mt-1">{{ $item->tag }}</span>
                                            @endif
                                            <br><small class="text-muted">{{ $item->image_path }}</small>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-secondary">{{ $item->sort_order }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('admin.media.banners.status', $item->id) }}" 
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
                                                <button type="button" class="btn btn-sm btn-info text-white mr-1" 
                                                    title="Edit Banner"
                                                    onclick="editBanner({{ json_encode($item) }})">
                                                    <i class="ik ik-edit-2"></i>
                                                </button>
                                                <a href="{{ route('admin.media.banners.delete', $item->id) }}" 
                                                   class="btn btn-sm btn-danger text-white" 
                                                   title="Delete Banner"
                                                   onclick="return confirm('Are you sure you want to delete this banner?');">
                                                    <i class="ik ik-trash-2"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
    <script>
        function editBanner(item) {
            document.getElementById('banner_id').value = item.id;
            document.getElementById('banner_title').value = item.title;
            document.getElementById('banner_tag').value = item.tag || '';
            document.getElementById('banner_sort_order').value = item.sort_order;
            document.getElementById('banner_status').value = item.status;
            document.getElementById('banner_external_link').value = item.external_link || '';

            // Update UI for Edit mode
            document.getElementById('formCardTitle').innerText = 'Edit Banner (#' + item.id + ')';
            document.getElementById('submitBannerBtn').innerHTML = '<i class="ik ik-check me-1"></i> Update Banner';
            document.getElementById('cancelEditBtn').classList.remove('d-none');
            document.getElementById('imageRequiredStar').innerText = '(optional if keeping current)';

            // Image Preview
            if (item.image_url) {
                document.getElementById('currentImagePreview').src = item.image_url;
                document.getElementById('imagePreviewContainer').classList.remove('d-none');
            }

            // Scroll to form smoothly
            window.scrollTo({ top: 120, behavior: 'smooth' });
        }

        function resetBannerForm() {
            document.getElementById('bannerForm').reset();
            document.getElementById('banner_id').value = '';
            document.getElementById('formCardTitle').innerText = 'Add New Banner';
            document.getElementById('submitBannerBtn').innerHTML = '<i class="ik ik-upload me-1"></i> Save Banner';
            document.getElementById('cancelEditBtn').classList.add('d-none');
            document.getElementById('imageRequiredStar').innerText = '*';
            document.getElementById('imagePreviewContainer').classList.add('d-none');
        }
    </script>
@endpush
@endsection
