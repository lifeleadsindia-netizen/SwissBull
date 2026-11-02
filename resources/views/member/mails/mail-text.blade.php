Login Notice:
Please login using DApp Browser in your Register Wallet, the registered wallet address is "{{ $member_wallet ?? '' }}".
Please choose BNB (BEP20/BSC Smartchain) network to access the our website https://syncfx.trade

==================================================
Welcome to {{ config('detailsApp.name', 'SYNC TRADE') }}!
==================================================

Hello {{ $name }},

Your account has been successfully created with {{ config('detailsApp.name', 'SYNC TRADE') }}.
You're all set to start your journey with us. Below are your account credentials:

- Name: {{ $name }}
- Member ID: {{ $memberid }}
- Email Address: {{ $email }}
- Mobile Number: {{ $mobile }}
@if(!empty($member_wallet))
- Registered Wallet Address: {{ $member_wallet }}
@endif

Platform URL: {{ config('detailsApp.url', 'https://syncfx.trade') }}

Security Notice:
Keep your credentials safe! Never share your passwords or account details with anyone, including our support team. We'll never ask for them via email or message.

With Regards,
Team {{ config('detailsApp.name', 'SYNC TRADE') }}

