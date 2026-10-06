@extends('admin.layouts.main')
@section('title', 'WhatsApp Referral Messages')
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container--default .select2-selection--multiple {
                border-color: #e4e6fc;
                border-radius: 4px;
                min-height: 38px;
            }

            .placeholder-badge {
                cursor: pointer;
                background-color: #e2e8f0;
                color: #334155;
                font-size: 11px;
                font-weight: 600;
                padding: 5px 10px;
                border-radius: 4px;
                margin-right: 6px;
                margin-bottom: 6px;
                display: inline-block;
                transition: all 0.2s ease;
                border: 1px solid #cbd5e1;
            }

            .placeholder-badge:hover {
                background-color: #007bff;
                color: #ffffff;
                border-color: #007bff;
            }

            .card-header h3 {
                font-weight: 700;
                color: #2b3674;
                font-size: 1.1rem;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-message-circle bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('WhatsApp Referral Promotion Messages') }}</h5>
                            <span>{{ __('Manage promotional WhatsApp referral templates') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('hdgteyusjasget/dashboard') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Marketing') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('WhatsApp Messages') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        @if (session()->has('successMsg'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('successMsg') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session()->has('delMsg'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('delMsg') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Form Section (Centered & Increased Width) -->
        <div class="row mb-4">
            <div class="col-xl-9 col-lg-10 col-md-12 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 id="form_title"><i class="ik ik-edit mr-2 text-primary"></i>{{ __('Message Manager Form') }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.whatsapp.saveMessage') }}" method="post" id="messageForm">
                            @csrf
                            <input type="hidden" name="id" id="msg_id" value="">

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">{{ __('Message Title') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" id="msg_title"
                                    placeholder="e.g. Daily Promotion" required>
                                @error('title')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="font-weight-bold">{{ __('Message Content') }} <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" name="content" id="msg_content" rows="4" placeholder="Enter referral text..."
                                    required></textarea>

                                <div class="mt-2 p-2 bg-light rounded border">
                                    <small
                                        class="text-muted d-block mb-1 font-weight-bold">{{ __('Click placeholder to insert') }}:</small>
                                    <span class="placeholder-badge"
                                        onclick="insertPlaceholder('{member_name}')">{member_name}</span>
                                    <span class="placeholder-badge"
                                        onclick="insertPlaceholder('{member_username}')">{member_username}</span>
                                    <span class="placeholder-badge"
                                        onclick="insertPlaceholder('{member_id}')">{member_id}</span>
                                    <span class="placeholder-badge"
                                        onclick="insertPlaceholder('{referral_link}')">{referral_link}</span>
                                    <span class="placeholder-badge"
                                        onclick="insertPlaceholder('{today_date}')">{today_date}</span>
                                </div>
                                @error('content')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-row mb-3">
                                <div class="form-group col-md-6 mb-md-0 mb-3">
                                    <label class="font-weight-bold">{{ __('Status') }}</label>
                                    <select name="status" id="msg_status" class="form-control">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6 mb-0">
                                    <label class="font-weight-bold">{{ __('Apply To') }}</label>
                                    <select name="apply_to" id="msg_apply_to" class="form-control"
                                        onchange="toggleSpecificMembers()">
                                        <option value="All Members">All Members</option>
                                        <option value="Specific Members">Specific Members</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-3" id="specific_members_group" style="display: none;">
                                <label class="font-weight-bold">{{ __('Select Specific Members') }} <span
                                        class="text-danger">*</span></label>
                                <select name="target_member_ids[]" id="target_member_ids" class="form-control select2"
                                    multiple="multiple" style="width: 100%;">
                                    @foreach ($members as $m)
                                        <option value="{{ $m->memberid }}">{{ $m->memberid }} - {{ $m->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('target_member_ids')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="text-right pt-2 border-top">
                                <button type="button" class="btn btn-secondary mr-2" onclick="resetForm()">
                                    <i class="ik ik-rotate-ccw"></i> {{ __('Reset') }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ik ik-save"></i> {{ __('Save Message') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section (Full Width Below Form) -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3><i class="ik ik-list mr-2 text-primary"></i>{{ __('WhatsApp Referral Messages Table') }}</h3>
                    </div>
                    <div class="card-body px-4">
                        <div class="table-responsive">
                            <table id="data_table" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;" class="text-center">{{ __('No') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Apply To') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Created At') }}</th>
                                        <th class="text-center">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($messages as $item)
                                        <tr>
                                            <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                                            <td>
                                                <strong class="text-dark">{{ $item->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($item->content, 70) }}</small>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $item->apply_to === 'Specific Members' ? 'badge-info' : 'badge-secondary' }}">
                                                    {{ $item->apply_to }}
                                                </span>
                                                @if ($item->apply_to === 'Specific Members' && is_array($item->target_member_ids))
                                                    <br><small class="text-muted">Count:
                                                        {{ count($item->target_member_ids) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $item->status === 'Active' ? 'badge-success' : 'badge-danger' }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td>{{ $item->created_at ? $item->created_at->format('d-m-Y') : '-' }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-info btn-sm text-white mr-1"
                                                    onclick='editMessage(@json($item))'>
                                                    <i class="ik ik-edit"></i> Edit
                                                </button>
                                                <a class="btn btn-danger btn-sm text-white"
                                                    href="{{ url('hdgteyusjasget/whatsapp-messages/delete/' . $item->id) }}"
                                                    onclick="return confirm('Are you sure you want to delete this message template?')">
                                                    <i class="ik ik-trash-2"></i> Delete
                                                </a>
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
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Select Members"
                });
                $('#data_table').DataTable();
                toggleSpecificMembers();
            });

            function toggleSpecificMembers() {
                var applyTo = $('#msg_apply_to').val();
                if (applyTo === 'Specific Members') {
                    $('#specific_members_group').show();
                } else {
                    $('#specific_members_group').hide();
                }
            }

            function insertPlaceholder(placeholder) {
                var textarea = document.getElementById('msg_content');
                var start = textarea.selectionStart;
                var end = textarea.selectionEnd;
                var text = textarea.value;
                textarea.value = text.substring(0, start) + placeholder + text.substring(end);
                textarea.focus();
                textarea.selectionStart = textarea.selectionEnd = start + placeholder.length;
            }

            function editMessage(item) {
                $('#form_title').text('Edit Message Manager');
                $('#msg_id').val(item.id);
                $('#msg_title').val(item.title);
                $('#msg_content').val(item.content);
                $('#msg_status').val(item.status);
                $('#msg_apply_to').val(item.apply_to);

                toggleSpecificMembers();

                if (item.apply_to === 'Specific Members' && item.target_member_ids) {
                    $('#target_member_ids').val(item.target_member_ids).trigger('change');
                } else {
                    $('#target_member_ids').val([]).trigger('change');
                }

                $('html, body').animate({
                    scrollTop: 0
                }, 'slow');
            }

            function resetForm() {
                $('#form_title').text('Message Manager Form');
                $('#msg_id').val('');
                $('#messageForm')[0].reset();
                $('#target_member_ids').val([]).trigger('change');
                toggleSpecificMembers();
            }
        </script>
    @endpush
@endsection
