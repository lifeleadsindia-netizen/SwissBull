@extends('member.layouts.main')
@section('title', 'Trading Dashboard')
@section('container')

    <div class="content-body">
        <div class="container-fluid pt-2 pb-5" style="padding-bottom: 80px !important;">
            <!-- Page Header -->
            <div class="page-titles mb-3">
                <div class="welcome-text">
                    <h4 class="text-white font-weight-bold mb-1">Trading Dashboard</h4>
                    <p class="mb-0 text-muted" style="font-size: 13px;">
                        Live Forex Market, Real-Time Interactive Charts, Technical Indicators & Global Economic Intelligence.
                    </p>
                </div>
                <div class="justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/member/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Trading Dashboard</a></li>
                    </ol>
                </div>
            </div>

            <style>
                .trading-card {
                    position: relative;
                    overflow: hidden;
                    border: 1px solid rgba(245, 158, 11, 0.35);
                    background:
                        radial-gradient(circle at 0% 0%, rgba(245, 158, 11, 0.18), rgba(245, 158, 11, 0) 42%),
                        linear-gradient(135deg, rgba(16, 20, 30, 0.96), rgba(10, 12, 18, 0.94));
                    box-shadow: 0 16px 36px rgba(0, 0, 0, 0.6);
                    border-radius: 18px;
                    margin-bottom: 24px;
                }

                .trading-card::before {
                    content: "";
                    position: absolute;
                    inset: -35% 40% auto -35%;
                    height: 280px;
                    background: radial-gradient(circle, rgba(245, 158, 11, 0.30), rgba(245, 158, 11, 0));
                    filter: blur(8px);
                    animation: poolGlowDrift 9s linear infinite;
                    pointer-events: none;
                }

                .trading-card .card-header {
                    border-bottom: 1px solid rgba(245, 158, 11, 0.2);
                    background: linear-gradient(90deg, rgba(245, 158, 11, 0.14), rgba(255, 255, 255, 0.02));
                    padding: 18px 24px;
                }

                .trading-card .card-title {
                    color: #FFFFFF;
                    letter-spacing: 0.25px;
                    font-weight: 700;
                }

                .trading-stat-chip {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 6px 14px;
                    border-radius: 20px;
                    font-size: 12px;
                    font-weight: 600;
                    background: rgba(245, 158, 11, 0.12);
                    border: 1px solid rgba(245, 158, 11, 0.3);
                    color: #f59e0b;
                }

                .trading-stat-chip.live {
                    background: rgba(16, 185, 129, 0.15);
                    border-color: rgba(16, 185, 129, 0.35);
                    color: #10b981;
                }

                .pulse-dot {
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    background: #10b981;
                    box-shadow: 0 0 8px #10b981;
                    animation: pulseLive 1.8s infinite;
                }

                @keyframes pulseLive {
                    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
                    70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
                    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
                }

                @keyframes poolGlowDrift {
                    0% { transform: translate(0, 0); }
                    50% { transform: translate(30px, 15px); }
                    100% { transform: translate(0, 0); }
                }
            </style>

            <!-- 1. LIVE FOREX TICKER TAPE -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="tradingview-widget-container" style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(245, 158, 11, 0.25);">
                        <div class="tradingview-widget-container__widget"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                        {
                            "symbols": [
                                { "proName": "FX_IDC:EURUSD", "title": "EUR/USD" },
                                { "proName": "FX_IDC:GBPUSD", "title": "GBP/USD" },
                                { "proName": "FX_IDC:USDJPY", "title": "USD/JPY" },
                                { "proName": "FX_IDC:USDCHF", "title": "USD/CHF" },
                                { "proName": "FX_IDC:AUDUSD", "title": "AUD/USD" },
                                { "proName": "FX_IDC:USDCAD", "title": "USD/CAD" },
                                { "proName": "FX_IDC:NZDUSD", "title": "NZD/USD" },
                                { "proName": "FX_IDC:EURJPY", "title": "EUR/JPY" },
                                { "proName": "FX_IDC:GBPJPY", "title": "GBP/JPY" },
                                { "proName": "OANDA:XAUUSD", "title": "Gold (XAU/USD)" },
                                { "proName": "BINANCE:BTCUSDT", "title": "BTC/USDT" }
                            ],
                            "showSymbolLogo": true,
                            "isTransparent": true,
                            "displayMode": "adaptive",
                            "colorTheme": "dark",
                            "locale": "en"
                        }
                        </script>
                    </div>
                </div>
            </div>

            <!-- 2. MAIN ADVANCED REAL-TIME FOREX CHART CARD -->
            <div class="row">
                <div class="col-12">
                    <div class="card trading-card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <span class="me-3 text-warning fs-3">
                                    <i class="la la-chart-area"></i>
                                </span>
                                <div>
                                    <h4 class="card-title mb-0">Live Forex Trading Chart</h4>
                                    <small class="text-white-50">Real-Time Interactive Candlestick Chart with Multi-Timeframe Analysis</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="trading-stat-chip live">
                                    <span class="pulse-dot"></span> 24/5 LIVE MARKET
                                </span>
                                <span class="trading-stat-chip">
                                    <i class="fas fa-coins me-1"></i> MAJOR CURRENCIES
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-2 p-md-3">
                            <div class="tradingview-widget-container" style="height: 680px; min-height: 600px; width: 100%; border-radius: 12px; overflow: hidden;">
                                <div class="tradingview-widget-container__widget" style="height: 100%; width: 100%;"></div>
                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-advanced-chart.js" async>
                                {
                                    "autosize": true,
                                    "symbol": "FX:EURUSD",
                                    "interval": "D",
                                    "timezone": "Etc/UTC",
                                    "theme": "dark",
                                    "style": "1",
                                    "locale": "en",
                                    "allow_symbol_change": true,
                                    "calendar": false,
                                    "support_host": "https://www.tradingview.com"
                                }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 3. FOREX CROSS RATES & FOREX HEATMAP (50% - 50% SIDE BY SIDE) -->
            <div class="row">
                <!-- Forex Currency Cross Rates (50% Width) -->
                <div class="col-xl-6 col-lg-6 col-12">
                    <div class="card trading-card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <span class="me-2 text-warning fs-4">
                                    <i class="la la-table"></i>
                                </span>
                                <div>
                                    <h5 class="card-title mb-0">Forex Currency Cross Rates</h5>
                                    <small class="text-white-50">Live Matrix Comparison Across Global Reserve Currencies</small>
                                </div>
                            </div>
                            <span class="trading-stat-chip">
                                <i class="la la-sync me-1"></i> REAL-TIME
                            </span>
                        </div>
                        <div class="card-body p-2 p-md-3">
                            <div class="tradingview-widget-container" style="height: 480px; width: 100%; border-radius: 12px; overflow: hidden;">
                                <div class="tradingview-widget-container__widget" style="height: 100%; width: 100%;"></div>
                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-forex-cross-rates.js" async>
                                {
                                    "width": "100%",
                                    "height": "100%",
                                    "currencies": [
                                        "EUR",
                                        "USD",
                                        "JPY",
                                        "GBP",
                                        "CHF",
                                        "AUD",
                                        "CAD",
                                        "NZD"
                                    ],
                                    "isTransparent": true,
                                    "colorTheme": "dark",
                                    "locale": "en"
                                }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Forex Heatmap (50% Width) -->
                <div class="col-xl-6 col-lg-6 col-12">
                    <div class="card trading-card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <span class="me-2 text-warning fs-4">
                                    <i class="la la-fire"></i>
                                </span>
                                <div>
                                    <h5 class="card-title mb-0">Forex Heatmap & Strength</h5>
                                    <small class="text-white-50">Daily Relative Strength & Currency Performance Heatmap</small>
                                </div>
                            </div>
                            <span class="trading-stat-chip">
                                <i class="la la-layer-group me-1"></i> HEATMAP
                            </span>
                        </div>
                        <div class="card-body p-2 p-md-3">
                            <div class="tradingview-widget-container" style="height: 480px; width: 100%; border-radius: 12px; overflow: hidden;">
                                <div class="tradingview-widget-container__widget" style="height: 100%; width: 100%;"></div>
                                <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-forex-heat-map.js" async>
                                {
                                    "width": "100%",
                                    "height": "100%",
                                    "currencies": [
                                        "EUR",
                                        "USD",
                                        "JPY",
                                        "GBP",
                                        "CHF",
                                        "AUD",
                                        "CAD",
                                        "NZD"
                                    ],
                                    "isTransparent": true,
                                    "colorTheme": "dark",
                                    "locale": "en"
                                }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
