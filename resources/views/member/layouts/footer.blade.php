<!--**********************************
            Footer start
        ***********************************-->
<!--<div class="footer style-1">-->
<!--    <div class="copyright">-->
<!--        <p>Copyright ©-->
<!--            <script>
    -- >
    <
    !--document.write(new Date().getFullYear()) -- >
        <
        !--
</script>-->
<!--            {{ config('detailsApp.name') }}, All Right Reserved -->

<!--<a href="https://www.mlmsoftwaredevelopers.live/"-->
<!--    target="_blank"> <u> Developed By MLM Software developer</u></a>-->
<!--        </p>-->
<!--    </div>-->
<!--</div>-->
<!--**********************************
                    Footer end
                ***********************************-->

<!--**********************************
                   Support ticket button start
                ***********************************-->

<!--**********************************
                   Support ticket button end
                ***********************************-->


</div>
<!--**********************************
                Main wrapper end
            ***********************************-->

<!--**********************************
                Scripts
            ***********************************-->
<!-- Required vendors -->
<script src="{{ asset('uassets/vendor/global/global.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('uassets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

<!-- Datatable -->
<script src="{{ asset('uassets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('uassets/vendor/datatables/responsive/responsive.js') }}"></script>
<script src="{{ asset('uassets/js/plugins-init/datatables.init.js') }}"></script>


<!-- Apex Chart -->
<script src="{{ asset('uassets/vendor/apexchart/apexchart.js') }}"></script>
<script src="{{ asset('uassets/vendor/chart-js/chart.bundle.min.js') }}"></script>

<!-- counter -->
<script src="{{ asset('uassets/vendor/counter/counter.min.js') }}"></script>
<script src="{{ asset('uassets/vendor/counter/waypoint.min.js') }}"></script>

<!-- Chart piety plugin files -->
{{-- <script src="{{asset('uassets/vendor/peity/jquery.peity.min.js')}}"></script>
            <script src="{{asset('uassets/vendor/swiper/js/swiper-bundle.min.js')}}"></script> --}}
<script src="{{ asset('uassets/vendor/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('uassets/js/dashboard/trading-market.js') }}"></script>

<!-- Dashboard 1 -->
<script src="{{ asset('uassets/js/dashboard/dashboard-1.js') }}"></script>
<script src="{{ asset('uassets/js/custom.min.js') }}"></script>
<script src="{{ asset('uassets/js/dlabnav-init.js') }}"></script>
<script src="{{ asset('uassets/js/demo.js') }}"></script>
<script src="{{ asset('uassets/js/main.js') }}"></script>
{{-- <script src="{{asset('uassets/js/styleSwitcher.js')}}"></script> --}}
<script type="text/javascript">
    function googleTranslateFunction() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateFunction"></script>
<script>
    jQuery(document).ready(function() {
        setTimeout(function() {
            dlabSettingsOptions.version = 'dark';
            new dlabSettings(dlabSettingsOptions);
            setCookie('version', 'dark');
        }, 1500)
    });
</script>
<script>
    $(function() {
        function isMobileView() {
            return window.innerWidth <= 768;
        }

        // Unbind any legacy/theme handlers on nav-control to prevent conflicting toggles
        $('.nav-control').off('click');
        $(document).off('click', '.nav-control');

        // Direct, bulletproof hamburger menu toggle handler
        $(document).on('click', '.nav-control', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $wrapper = $('#main-wrapper');
            var $hamburger = $('.hamburger');

            $wrapper.toggleClass('menu-toggle');
            $hamburger.toggleClass('is-active');

            if (isMobileView() && $wrapper.hasClass('menu-toggle')) {
                $('body').addClass('overflow-hidden');
            } else {
                $('body').removeClass('overflow-hidden');
            }
        });

        // Close mobile sidebar when clicking outside (on content area / backdrop overlay)
        $(document).on('click touchstart', function(e) {
            if (!isMobileView()) return;

            if ($('#main-wrapper').hasClass('menu-toggle')) {
                var inSidebarOrHeader = $(e.target).closest('.dlabnav, .nav-header, .nav-control')
                    .length > 0;
                if (!inSidebarOrHeader) {
                    $('#main-wrapper').removeClass('menu-toggle');
                    $('.hamburger').removeClass('is-active');
                    $('body').removeClass('overflow-hidden');
                }
            }
        });

        // Close mobile sidebar ONLY when an actual page link (not dropdown section toggle) is clicked
        $(document).on('click', '.dlabnav a', function() {
            if (!isMobileView()) return;

            var href = $(this).attr('href');
            var isDropdownToggle = $(this).hasClass('has-arrow') || !href || href ===
                'javascript:void(0);' || href === '#';

            if (!isDropdownToggle) {
                $('#main-wrapper').removeClass('menu-toggle');
                $('.hamburger').removeClass('is-active');
                $('body').removeClass('overflow-hidden');
            }
        });

        // Handle window resize
        $(window).on('resize', function() {
            if (!isMobileView()) {
                $('body').removeClass('overflow-hidden');
            }
        });

        // Header Profile Dropdown Handler (Desktop & Mobile)
        $(document).on('click',
            '.header-profile2 > a.nav-link, .header-profile2 .header-info2, .header-profile2 [data-bs-toggle="dropdown"]',
            function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (typeof toggleProfileDropdown === 'function') {
                    toggleProfileDropdown(e);
                } else {
                    var $container = $(this).closest('.header-profile2');
                    var $menu = $container.find('.dropdown-menu');
                    var isOpen = $container.hasClass('show') || $menu.hasClass('show');

                    $('#headerNotifPanel').hide().removeClass('active');

                    if (isOpen) {
                        $container.removeClass('show');
                        $menu.removeClass('show').hide();
                    } else {
                        $container.addClass('show');
                        $menu.addClass('show').show();
                    }
                }
            });

        // Close profile dropdown when clicking outside
        $(document).on('click touchstart', function(e) {
            if (!$(e.target).closest('.header-profile2').length) {
                $('.header-profile2').removeClass('show');
                $('.header-profile2 .dropdown-menu').removeClass('show').hide();
            }
        });

        // Close on escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                $('.header-profile2').removeClass('show');
                $('.header-profile2 .dropdown-menu').removeClass('show').hide();
                $('#headerNotifPanel').hide().removeClass('active');
            }
        });

        // Guard the theme selectpicker call because it sometimes crashes before the menu interaction runs.
        if (typeof $.fn.selectpicker === 'function') {
            $('.default-select, .dataTables_wrapper select').selectpicker();
        }

        // Universal Double-Submit Prevention for All Member Panel Forms
        $(document).on('submit', 'form', function(e) {
            var $form = $(this);

            // Stop second submission immediately if form is already in submitting state
            if ($form.data('is-submitting') === true) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }

            // Find submit button inside the form
            var $btn = $form.find(
                'button[type="submit"], input[type="submit"], .subBtn, .staking-btn, .act-btn, .dep-btn, .transBtn, .withdraw_btn, #regBtn, #transBtn, #approveBtn, #act_btn'
                );

            if ($btn.length === 0) {
                $btn = $form.find('button, input[type="button"]').filter(function() {
                    return !$(this).attr('type') || $(this).attr('type') === 'submit' || $(this)
                        .hasClass('btn');
                });
            }

            if ($btn.length > 0) {
                // Save original HTML content
                if (!$btn.data('orig-html')) {
                    $btn.data('orig-html', $btn.html());
                }

                // Immediately disable button and add visual disabled state
                $btn.prop('disabled', true).addClass('disabled');
                $btn.css({
                    'pointer-events': 'none',
                    'opacity': '0.7',
                    'cursor': 'not-allowed'
                });

                // Show processing indicator
                $btn.html(
                '<span>Processing...</span> <i class="fa-solid fa-spinner fa-spin ms-2"></i>');
            }

            // Mark form state as submitting
            $form.data('is-submitting', true);

            // Safety timeout: Re-enable form after 15s if page didn't reload (e.g. backend validation failure)
            setTimeout(function() {
                $form.data('is-submitting', false);
                if ($btn.length > 0) {
                    $btn.prop('disabled', false).removeClass('disabled');
                    $btn.css({
                        'pointer-events': 'auto',
                        'opacity': '1',
                        'cursor': 'pointer'
                    });
                    if ($btn.data('orig-html')) {
                        $btn.html($btn.data('orig-html'));
                    }
                }
            }, 15000);
        });

        // Direct button click lock to catch fast double clicks before form submit event fires
        $(document).on('click',
            'button[type="submit"], input[type="submit"], .subBtn, .staking-btn, .act-btn, .dep-btn, .transBtn, .withdraw_btn, #regBtn, #transBtn, #approveBtn, #act_btn',
            function(e) {
                var $btn = $(this);
                var $form = $btn.closest('form');

                if ($form.length > 0 && $form.data('is-submitting') === true) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }

                if ($btn.data('click-locked') === true) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }

                $btn.data('click-locked', true);
                setTimeout(function() {
                    $btn.data('click-locked', false);
                }, 1500);
            });

        //withdrawal code
        $('#withAmount').on('input', function() {

            var netBalance = parseInt($('#netamount').val());
            var inputAmount = parseInt($(this).val());
            //  alert("Echo");
            if (inputAmount > netBalance) {

                $('#withdrawBtn').attr('disabled', true);
                $('#wMesg').html('Enter amount can not be greater then Net Balance');
            } else if (inputAmount < 10) {
                $('#withdrawBtn').attr('disabled', true);
                $('#wMesg').html('Minimum withdrawal amount is $10');
            } else {
                $('#withdrawBtn').attr('disabled', false);
                $('#wMesg').html('');
            }
        });

        $('#userid_b').on('input', function() {
            var memberid = $(this).val();
            var csrf = $('.csrf').val();
            if (memberid.length == 0) {
                $('#memMsg_b').html('');
                $('.transBtn').attr('disabled', false);
            }
            $.ajax({
                url: '/getMember',
                type: 'POST',
                data: {
                    'memberid': memberid,
                    _token: csrf,
                },
                success: function(response) {
                    $('#memMsg_b').html(response['data']);
                    $('#memName_b').html(response['name']);

                    if (response['code'] == 0) {
                        $('.transBtn').attr('disabled', true);
                    } else {
                        $('.transBtn').attr('disabled', false);
                    }
                }
            });
        });

        //package selection code
        $('#poolname').on('change', function() {
            var pool = $(this).val();
            if (pool == 'Silver') {
                $('#gld,#dmnd,#pltnm,#rby').hide();
                $('#slvr').show();
            } else if (pool == 'Gold') {
                $('#slvr,#dmnd,#pltnm,#rby').hide();
                $('#gld').show();
            } else if (pool == 'Diamond') {
                $('#slvr,#gld,#pltnm,#rby').hide();
                $('#dmnd').show();
            } else if (pool == 'Platinum') {
                $('#slvr,#gld,#dmnd,#rby').hide();
                $('#pltnm').show();
            } else if (pool == 'Ruby') {
                $('#slvr,#gld,#dmnd,#pltnm').hide();
                $('#rby').show();
            }
        });
        $('#userid_r').on('input', function() {
            var memberid = $(this).val();
            var csrf = $('.csrf').val();
            if (memberid.length == 0) {
                $('#memMsg_r').html('');
                $('.transBtn').attr('disabled', false);
            }
            $.ajax({
                url: '/getMember',
                type: 'POST',
                data: {
                    'memberid': memberid,
                    _token: csrf,
                },
                success: function(response) {
                    $('#memMsg_r').html(response['data']);
                    $('#memName_r').html(response['name']);

                    if (response['code'] == 0) {
                        $('.transBtn').attr('disabled', true);
                    } else {
                        $('.transBtn').attr('disabled', false);
                    }
                }
            });
        });
        $('#userid_t').on('input', function() {
            var memberid = $(this).val();
            var csrf = $('.csrf').val();
            if (memberid.length == 0) {
                $('#memMsg_t').html('');
                $('.transBtn').attr('disabled', false);
            }
            $.ajax({
                url: '/getMember',
                type: 'POST',
                data: {
                    'memberid': memberid,
                    _token: csrf,
                },
                success: function(response) {
                    $('#memMsg_t').html(response['data']);
                    $('#memName_t').html(response['name']);

                    if (response['code'] == 0) {
                        $('.transBtn').attr('disabled', true);
                    } else {
                        $('.transBtn').attr('disabled', false);
                    }
                }
            });
        });

        //package selection code for activation
        $('#package').on('change', function() {
            var package = $(this).val();


            if (package == 'Starter') {
                $('#packMsg').html('Min Value = 50 and Max Value = 499');
            } else if (package == 'Silver') {
                $('#packMsg').html('Min Value = 500 and Max Value = 999')
            } else if (package == 'Gold') {
                $('#packMsg').html('Min Value = 1000 and Max Value = 4999')
            } else if (package == 'Platinum') {
                $('#packMsg').html('Min Value = 5000 and Max Value = 9999')
            } else if (package == 'Diamond') {
                $('#packMsg').html('Min Value = 10000 and Max Value = 19999')
            } else if (package == '' || package == null) {
                $('#packMsg').html('')
            }
        });

        //deposit value input function
        $('#pvalue').on('input', function() {
            var amount = $(this).val();
            if (amount.length == 0) {
                $('#platform_fee').val(0);
                $('#actual_deposit').val(0);
                return;
            }
            if (isNaN(amount) || amount < 0) {
                $('#intMsg').html('Please enter a valid number');
                $('#platform_fee').val(0);
                $('#actual_deposit').val(0);
                return;
            } else {
                $('#intMsg').html('');
            }
            if (amount < 50) {
                $('#intMsg').html('Please enter a value greater than 50');
                $('#platform_fee').val(0);
                $('#actual_deposit').val(0);
                return;
            } else {
                $('#intMsg').html('');
            }
            var platform_fee = amount / 100;
            var actual_deposit = amount - platform_fee;
            $('#platform_fee').val(platform_fee.toFixed(2));
            $('#actual_deposit').val(actual_deposit.toFixed(2));

        });

    })
</script>
</body>

</html>
