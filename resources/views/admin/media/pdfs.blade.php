@extends('admin.layouts.main')
@section('title', 'Business Plan PDFs Management')
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
        <style>
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
            .pdf-icon-box {
                width: 44px;
                height: 44px;
                background: rgba(220, 53, 69, 0.1);
                color: #dc3545;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
            }
        </style>
    @endpush

<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-file-text bg-red"></i>
                    <div class="d-inline">
                        <h5>{{ __('Business Plan PDFs') }}</h5>
                        <span>{{ __('Manage multi-language pitch decks & PDFs available to members for download and in-app viewing') }}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Business Plan PDFs') }}</li>
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
        <!-- Add / Edit PDF Form -->
        <div class="col-xl-4 col-lg-5 col-md-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 id="pdfFormCardTitle">{{ __('Add New Plan PDF') }}</h3>
                    <button type="button" class="btn btn-sm btn-outline-secondary d-none" id="cancelPdfEditBtn" onclick="resetPdfForm()">
                        <i class="ik ik-x"></i> Cancel
                    </button>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.media.pdfs.save') }}" method="POST" enctype="multipart/form-data" id="pdfForm">
                        @csrf
                        <input type="hidden" name="id" id="pdf_id">

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('Document Title') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" id="pdf_title" placeholder="e.g. Math Wallet Global Business Plan" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label font-weight-bold">{{ __('Language') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="language" id="pdf_language" placeholder="e.g. English" required>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label font-weight-bold">{{ __('Native Name') }}</label>
                                <input type="text" class="form-control" name="native_language" id="pdf_native_language" placeholder="e.g. English, 中文, Русский">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label font-weight-bold">{{ __('Flag Icon/Emoji') }}</label>
                                <input type="text" class="form-control" name="flag" id="pdf_flag" placeholder="e.g. 🇬🇧, 🇨🇳, 🇷🇺">
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label font-weight-bold">{{ __('Badge Label') }}</label>
                                <input type="text" class="form-control" name="badge" id="pdf_badge" placeholder="e.g. Global Edition">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('PDF Document File') }} <span id="pdfFileRequiredStar" class="text-danger">*</span></label>
                            <input type="file" name="pdf_file" id="pdf_file" class="form-control" accept=".pdf">
                            <small class="text-muted d-block mt-1">Upload PDF document. Max file size: 50MB.</small>
                            <div id="currentPdfFileInfo" class="mt-2 d-none">
                                <span class="badge badge-info p-2" id="currentPdfFilenameDisplay"></span>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">{{ __('Description / Key Topics') }}</label>
                            <textarea class="form-control" name="description" id="pdf_description" rows="3" placeholder="Brief summary of what this pitch deck covers..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label font-weight-bold">{{ __('Sort Order') }}</label>
                                <input type="number" class="form-control" name="sort_order" id="pdf_sort_order" value="0" min="0">
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label class="form-label font-weight-bold">{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select class="form-control" name="status" id="pdf_status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger btn-block py-2 font-weight-bold" id="submitPdfBtn">
                                <i class="ik ik-upload me-1"></i> {{ __('Save Business Plan PDF') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- PDFs Table -->
        <div class="col-xl-8 col-lg-7 col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('Business Plan PDFs List') }} ({{ count($pdfs) }})</h3>
                    <span class="badge badge-info">{{ __('Multilingual Pitch Decks') }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data_table" class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">{{ __('S.No') }}</th>
                                    <th>{{ __('Document') }}</th>
                                    <th>{{ __('Language') }}</th>
                                    <th>{{ __('Size') }}</th>
                                    <th class="text-center">{{ __('Order') }}</th>
                                    <th class="text-center">{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pdfs as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-start">
                                                <div class="pdf-icon-box me-2 mr-2">
                                                    <i class="ik ik-file-text"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-dark">{{ $item->title }}</strong>
                                                    @if ($item->badge)
                                                        <span class="badge badge-primary ms-1" style="font-size: 10px;">{{ $item->badge }}</span>
                                                    @endif
                                                    <br><small class="text-muted">{{ $item->file_path }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="font-size: 16px;">{{ $item->flag ?? '📄' }}</span>
                                            <strong>{{ $item->language }}</strong>
                                            @if ($item->native_language && $item->native_language !== $item->language)
                                                <br><small class="text-muted">{{ $item->native_language }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-light border">{{ $item->file_size ?? 'PDF' }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-secondary">{{ $item->sort_order }}</span>
                                        </td>
                                        <td class="text-center align-middle">
                                            <a href="{{ route('admin.media.pdfs.status', $item->id) }}" 
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
                                                <a href="{{ $item->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary mr-1" title="View / Download PDF">
                                                    <i class="ik ik-external-link"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-info text-white mr-1" 
                                                    title="Edit PDF Metadata"
                                                    onclick="editPdf({{ json_encode($item) }})">
                                                    <i class="ik ik-edit-2"></i>
                                                </button>
                                                <a href="{{ route('admin.media.pdfs.delete', $item->id) }}" 
                                                   class="btn btn-sm btn-danger text-white" 
                                                   title="Delete PDF"
                                                   onclick="return confirm('Are you sure you want to delete this PDF?');">
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
        function editPdf(item) {
            document.getElementById('pdf_id').value = item.id;
            document.getElementById('pdf_title').value = item.title;
            document.getElementById('pdf_language').value = item.language;
            document.getElementById('pdf_native_language').value = item.native_language || '';
            document.getElementById('pdf_flag').value = item.flag || '';
            document.getElementById('pdf_badge').value = item.badge || '';
            document.getElementById('pdf_description').value = item.description || '';
            document.getElementById('pdf_sort_order').value = item.sort_order;
            document.getElementById('pdf_status').value = item.status;

            document.getElementById('pdfFormCardTitle').innerText = 'Edit PDF (#' + item.id + ')';
            document.getElementById('submitPdfBtn').innerHTML = '<i class="ik ik-check me-1"></i> Update Business Plan PDF';
            document.getElementById('cancelPdfEditBtn').classList.remove('d-none');
            document.getElementById('pdfFileRequiredStar').innerText = '(optional if keeping current file)';

            if (item.file_path) {
                document.getElementById('currentPdfFilenameDisplay').innerText = 'Current file: ' + item.file_path;
                document.getElementById('currentPdfFileInfo').classList.remove('d-none');
            }

            window.scrollTo({ top: 120, behavior: 'smooth' });
        }

        function resetPdfForm() {
            document.getElementById('pdfForm').reset();
            document.getElementById('pdf_id').value = '';
            document.getElementById('pdfFormCardTitle').innerText = 'Add New Plan PDF';
            document.getElementById('submitPdfBtn').innerHTML = '<i class="ik ik-upload me-1"></i> Save Business Plan PDF';
            document.getElementById('cancelPdfEditBtn').classList.add('d-none');
            document.getElementById('pdfFileRequiredStar').innerText = '*';
            document.getElementById('currentPdfFileInfo').classList.add('d-none');
        }
    </script>
@endpush
@endsection
