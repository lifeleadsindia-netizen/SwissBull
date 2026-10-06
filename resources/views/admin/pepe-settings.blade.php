@extends('admin.layouts.main')
@section('title', 'PEPE Token Withdrawal Settings')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-settings bg-green"></i>
                        <div class="d-inline">
                            <h5>{{ __('PEPE Token Withdrawal Settings') }}</h5>
                            <span>{{ __('Configure PEPE BEP-20 Token Contract, RPC Network, and DApp Disbursement Parameters') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('hdgteyusjasget/dashboard') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Payment Management') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('PEPE Token Settings') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if (session()->has('successMsg'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ik ik-check-circle mr-2"></i> {{ session('successMsg') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6 class="font-weight-bold mb-2"><i class="ik ik-alert-circle mr-1"></i> Please resolve the following
                    errors:</h6>
                <ul class="mb-0 pl-3">
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
            <!-- Settings Form -->
            <div class="col-lg-8 col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i
                                class="ik ik-sliders mr-2 text-success"></i>{{ __('BEP-20 Contract & Disbursement Settings') }}
                        </h3>
                        <span class="badge {{ $settings->is_active ? 'badge-success' : 'badge-danger' }} px-2 py-1">
                            {{ $settings->is_active ? 'Active' : 'Disabled' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.updatePepeSettings') }}" method="POST">
                            @csrf

                            <!-- Section 1: Contract Details -->
                            <div class="border-bottom pb-3 mb-4">
                                <h6 class="font-weight-bold text-primary mb-3">
                                    <i class="ik ik-code mr-1"></i> 1. Smart Contract Details
                                </h6>
                                <div class="form-group">
                                    <label for="contract_address" class="font-weight-bold">
                                        PEPE BEP-20 Contract Address <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control font-weight-bold @error('contract_address') is-invalid @enderror"
                                            id="contract_address" name="contract_address"
                                            value="{{ old('contract_address', $settings->contract_address) }}"
                                            placeholder="0x..." required>
                                        <div class="input-group-append">
                                            <a href="{{ rtrim($settings->explorer_url, '/') }}/token/{{ $settings->contract_address }}"
                                                target="_blank" class="btn btn-outline-secondary"
                                                title="View contract on BscScan">
                                                <i class="ik ik-external-link"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        The BEP-20 Token smart contract address on BNB Smart Chain (default:
                                        <code>0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00</code>).
                                    </small>
                                    @error('contract_address')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Token ABI Editor -->
                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label for="token_abi" class="font-weight-bold mb-0">
                                            PEPE BEP-20 Contract ABI (JSON)
                                        </label>
                                        <div>
                                            <button type="button" class="btn btn-xs btn-outline-info py-0 px-2 mr-1"
                                                onclick="formatAbiJson()" title="Beautify JSON formatting">
                                                <i class="ik ik-align-left mr-1"></i> Format JSON
                                            </button>
                                            <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2"
                                                onclick="resetToDefaultAbi()" title="Reset to standard BEP-20 ABI">
                                                <i class="ik ik-refresh-cw mr-1"></i> Reset Standard ABI
                                            </button>
                                        </div>
                                    </div>
                                    <textarea class="form-control font-monospace @error('token_abi') is-invalid @enderror" id="token_abi" name="token_abi"
                                        rows="9"
                                        style="font-family: 'Courier New', Courier, monospace; font-size: 12px; background: #0f172a; color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.35); line-height: 1.4;"
                                        placeholder="Paste BEP-20 / ERC-20 contract ABI JSON here...">{{ old('token_abi', $settings->getEffectiveAbi()) }}</textarea>
                                    <small class="form-text text-muted">
                                        The application and DApp Web3 signer use this ABI to call <code>transfer</code>,
                                        <code>balanceOf</code>, and <code>decimals</code> on the PEPE smart contract.
                                    </small>
                                    @error('token_abi')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="token_symbol" class="font-weight-bold">Token Symbol <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('token_symbol') is-invalid @enderror"
                                                id="token_symbol" name="token_symbol"
                                                value="{{ old('token_symbol', $settings->token_symbol) }}" required>
                                            @error('token_symbol')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="token_name" class="font-weight-bold">Token Display Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('token_name') is-invalid @enderror"
                                                id="token_name" name="token_name"
                                                value="{{ old('token_name', $settings->token_name) }}" required>
                                            @error('token_name')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="token_decimals" class="font-weight-bold">Decimals <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('token_decimals') is-invalid @enderror"
                                                id="token_decimals" name="token_decimals"
                                                value="{{ old('token_decimals', $settings->token_decimals) }}"
                                                min="0" max="36" required>
                                            @error('token_decimals')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2: Blockchain Network & RPC -->
                            <div class="border-bottom pb-3 mb-4">
                                <h6 class="font-weight-bold text-primary mb-3">
                                    <i class="ik ik-globe mr-1"></i> 2. Blockchain Network & RPC Configuration
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="chain_id" class="font-weight-bold">Chain ID <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('chain_id') is-invalid @enderror"
                                                id="chain_id" name="chain_id"
                                                value="{{ old('chain_id', $settings->chain_id) }}" required>
                                            <small class="form-text text-muted">56 = BSC Mainnet, 97 = BSC Testnet</small>
                                            @error('chain_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="network_name" class="font-weight-bold">Network Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('network_name') is-invalid @enderror"
                                                id="network_name" name="network_name"
                                                value="{{ old('network_name', $settings->network_name) }}" required>
                                            @error('network_name')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="rpc_url" class="font-weight-bold">RPC Node URL <span
                                            class="text-danger">*</span></label>
                                    <input type="url" class="form-control @error('rpc_url') is-invalid @enderror"
                                        id="rpc_url" name="rpc_url" value="{{ old('rpc_url', $settings->rpc_url) }}"
                                        placeholder="https://bsc-dataseed.binance.org/" required>
                                    <small class="form-text text-muted">Web3 JSON-RPC endpoint used for querying blockchain
                                        and submitting token transactions.</small>
                                    @error('rpc_url')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="explorer_url" class="font-weight-bold">Block Explorer Base URL <span
                                            class="text-danger">*</span></label>
                                    <input type="url" class="form-control @error('explorer_url') is-invalid @enderror"
                                        id="explorer_url" name="explorer_url"
                                        value="{{ old('explorer_url', $settings->explorer_url) }}"
                                        placeholder="https://bscscan.com" required>
                                    @error('explorer_url')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Section 3: Redemption Rules & Limits -->
                            <div class="border-bottom pb-3 mb-4">
                                <h6 class="font-weight-bold text-primary mb-3">
                                    <i class="ik ik-sliders mr-1"></i> 3. Redemption Rules & Limits
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="gas_limit" class="font-weight-bold">Gas Limit <span
                                                    class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('gas_limit') is-invalid @enderror"
                                                id="gas_limit" name="gas_limit"
                                                value="{{ old('gas_limit', $settings->gas_limit) }}" min="21000"
                                                required>
                                            <small class="form-text text-muted">Recommended: 150000</small>
                                            @error('gas_limit')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="min_redeem" class="font-weight-bold">Min Redeem Amount (PEPE)
                                                <span class="text-danger">*</span></label>
                                            <input type="number" step="any"
                                                class="form-control @error('min_redeem') is-invalid @enderror"
                                                id="min_redeem" name="min_redeem"
                                                value="{{ old('min_redeem', $settings->min_redeem) }}" min="0"
                                                required>
                                            <small class="form-text text-muted">Minimum tokens member can redeem at
                                                once.</small>
                                            @error('min_redeem')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section 4: System Status Toggle -->
                            <div class="mb-4">
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $settings->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold" for="is_active">
                                        Enable PEPE Token Redemption via DApp
                                    </label>
                                </div>
                                <small class="form-text text-muted d-block mt-1">
                                    If unchecked, members will see a maintenance notice when attempting to redeem PEPE
                                    tokens.
                                </small>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-success btn-lg px-4 font-weight-bold">
                                    <i class="ik ik-save mr-1"></i> {{ __('Save PEPE Settings') }}
                                </button>
                                <a href="{{ url('hdgteyusjasget/payment-history') }}"
                                    class="btn btn-outline-secondary ml-2">
                                    {{ __('View Payment History') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Side Card: Information & Quick Links -->
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0"><i class="ik ik-info mr-2 text-info"></i>{{ __('Contract Overview') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center p-3 mb-3 bg-light rounded">
                            <div class="mb-2">
                                <span class="badge badge-success p-2" style="font-size: 14px;">
                                    <i class="ik ik-award mr-1"></i> {{ $settings->token_name }}
                                    ({{ $settings->token_symbol }})
                                </span>
                            </div>
                            <div class="font-weight-bold text-dark mt-2"
                                style="word-break: break-all; font-family: monospace; font-size: 13px;">
                                {{ $settings->contract_address }}
                            </div>
                            <div class="mt-3">
                                <a href="{{ rtrim($settings->explorer_url, '/') }}/token/{{ $settings->contract_address }}"
                                    target="_blank" class="btn btn-sm btn-primary">
                                    <i class="ik ik-external-link mr-1"></i> {{ __('View on BscScan') }}
                                </a>
                            </div>
                        </div>

                        <div class="list-group list-group-flush mb-3">
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Network</span>
                                <span class="font-weight-bold">{{ $settings->network_name }}
                                    ({{ $settings->chain_id }})</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Decimals</span>
                                <span class="font-weight-bold">{{ $settings->token_decimals }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Gas Limit</span>
                                <span class="font-weight-bold">{{ number_format($settings->gas_limit) }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Min Redeem</span>
                                <span class="font-weight-bold">{{ number_format($settings->min_redeem, 2) }}
                                    {{ $settings->token_symbol }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Status</span>
                                <span class="badge {{ $settings->is_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $settings->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </div>
                        </div>

                        <div class="alert alert-info py-2 px-3 small mb-0">
                            <i class="ik ik-help-circle mr-1"></i>
                            <strong>Note:</strong> When you update the contract address or RPC settings here, changes
                            immediately reflect on the Member Dashboard and DApp redemption flows without modifying any
                            code.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            const defaultAbiJson = {!! json_encode(\App\Models\PepeSetting::getDefaultAbi()) !!};

            function resetToDefaultAbi() {
                if (confirm('Reset Contract ABI to standard BEP-20 token ABI?')) {
                    document.getElementById('token_abi').value = defaultAbiJson;
                }
            }

            function formatAbiJson() {
                var el = document.getElementById('token_abi');
                try {
                    var parsed = JSON.parse(el.value);
                    el.value = JSON.stringify(parsed, null, 2);
                } catch (e) {
                    alert('Invalid JSON syntax: ' + e.message);
                }
            }
        </script>
    @endpush
@endsection
