<?php

use App\Http\Controllers\ActivationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminMediaController;
use App\Http\Controllers\AdminWhatsappController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\MemberDetailController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PartnershipController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TempController;
use App\Http\Controllers\UserAccessController;
use App\Http\Controllers\WhatsappReferralController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [FrontendController::class, 'index']);
// Route::get('/about', [FrontendController::class, 'about']);
// Route::get('/contact', [FrontendController::class, 'contact']);
Route::get('/forget-password', [AdminController::class, 'forgetPassword']);
Route::post('retrivePassword', [AdminController::class, 'retrivePassword'])->name('retrivePassword');

Route::get('/admin', [AdminController::class, 'index']);
Route::get('/send-admin-otp', [AdminController::class, 'sendAdminOtp']);
Route::post('adminLogin', [AdminController::class, 'authenticate'])->name('adminLogin');

Route::group(['prefix' => 'admin', 'middleware' => 'AdminAuth'], function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/logout', [AdminController::class, 'logout']);

    // set advertise rates
    Route::get('/set_rate', [AdminController::class, 'setRate']);
    Route::post('/setRoiRate', [AdminController::class, 'setRoiRate'])->name('setRoiRate');

    // set packages
    Route::get('/set-packages', [AdminController::class, 'setPackages'])->name('admin.setPackages');
    Route::get('/set_packages', [AdminController::class, 'setPackages']);
    Route::post('/savePackages', [AdminController::class, 'savePackages'])->name('admin.savePackages');
    Route::post('/set-packages', [AdminController::class, 'savePackages']);

    // Trading Wallet Control
    Route::get('/trading-wallet-control', [AdminController::class, 'tradingWalletControl'])->name('admin.tradingWalletControl');
    Route::post('/trading-wallet-control', [AdminController::class, 'applyTradingWalletControl'])->name('admin.applyTradingWalletControl');
    Route::post('/apply-trading-wallet-control', [AdminController::class, 'applyTradingWalletControl']);
    Route::post('/filter-trading-members', [AdminController::class, 'filterTradingMembers'])->name('admin.filterTradingMembers');
    Route::post('/get-trading-member', [AdminController::class, 'getTradingMember'])->name('admin.getTradingMember');

    // Monthly Trading Profit Configuration
    Route::get('/monthly-trading-profit', [AdminController::class, 'monthlyTradingProfit'])->name('admin.monthlyTradingProfit');
    Route::get('/monthly_trading_profit', [AdminController::class, 'monthlyTradingProfit']);
    Route::post('/monthly-trading-profit', [AdminController::class, 'saveMonthlyTradingProfit'])->name('admin.saveMonthlyTradingProfit');
    Route::post('/save-monthly-trading-profit', [AdminController::class, 'saveMonthlyTradingProfit']);

    // Color Dashboard
    Route::get('/color-dashboard', [AdminController::class, 'colorDashboard']);

    // notification
    Route::get('/notification', [AdminController::class, 'notification']);
    Route::post('saveNotification', [AdminController::class, 'saveNotification'])->name('saveNotification');

    // Dash Messages
    Route::get('/dash-msg', [AdminController::class, 'dashMessages']);
    Route::get('/dash-messages/delete/{id}', [AdminController::class, 'dashMsgDelete']);
    Route::post('/dashMsg', [AdminController::class, 'dashMsg'])->name('dashMsg');

    // Achievers Images Section//
    Route::get('/dashboard-images', [AdminController::class, 'achiversImages']);
    Route::post('/acheivImage', [AdminController::class, 'acheivImage'])->name('acheivImage');
    Route::get('/achv-image/delete/{id}', [AdminController::class, 'achiversImagesDelete']);

    // income routes
    Route::get('/income/roi-incomes', [AdminController::class, 'roiInc']);
    Route::get('/income/roi-details', [AdminController::class, 'roiDetails']);

    Route::get('/income/level-incomes', [AdminController::class, 'levelInc']);
    Route::get('/income/staking-level-incomes', [AdminController::class, 'stakLevelInc']);
    Route::get('/income/single-leg-incomes', [AdminController::class, 'singleLegInc']);
    Route::get('/income/partnership-incomes', [AdminController::class, 'partnershipInc']);
    Route::get('/income/partnership-details', [AdminController::class, 'partnershipDetails']);
    Route::get('/income/team-withdrawal-commission-incomes', [AdminController::class, 'teamWithInc']);

    // Profile Management
    Route::get('/profile', [AdminController::class, 'profile']);
    Route::post('/changePassword', [AdminController::class, 'change'])->name('changePassword');
    Route::post('/changeEmail', [AdminController::class, 'changeEmail'])->name('changeEmail');
    Route::get('/member-details', [AdminController::class, 'details']);

    // WALLET ADDRESS
    Route::get('/wallet-address', [AdminController::class, 'walletAddress']);
    Route::post('/updateWallet', [AdminController::class, 'updateWallet'])->name('updateWallet');

    // funds
    Route::get('/funds/add-funds', [AdminController::class, 'addFunds']);
    Route::post('addMemFunds', [AdminController::class, 'addMemFunds'])->name('addMemFunds');

    Route::get('/funds/deduct-funds', [AdminController::class, 'deductFunds']);
    Route::post('deductMemFunds', [AdminController::class, 'deductMemFunds'])->name('deductMemFunds');
    Route::get('/funds/add-funds-details', [AdminController::class, 'addFundsDetails']);
    Route::get('/funds/import-fund-details', [AdminController::class, 'importFundDetails']);
    // Route::get('/funds/add-flt-details', [AdminController::class, 'addFltDetails']);

    Route::get('/withdrawal/accept-online/{id}', [WithdrawalController::class, 'wAcceptOnline']);
    Route::get('/withdrawal-accept-pepe/{id}', [WithdrawalController::class, 'wAcceptOnlinePepe']);
    Route::post('/withdrawalAccept', [WithdrawalController::class, 'withdrawalAccept'])->name('withdrawalAccept');
    Route::get('/withdrawal/cancel/{id}', [AdminController::class, 'wCancel']);
    Route::get('/withdrawal/accept/{id}', [AdminController::class, 'wAccept']);

    // support ticket sections
    Route::get('/support/new-support-ticket', [SupportController::class, 'newSupport']);
    Route::get('/support/open-support-ticket', [SupportController::class, 'openSupport']);
    Route::get('/support/close-support-ticket', [SupportController::class, 'closeSupport']);
    Route::get('/support/view-support-ticket/{id}', [SupportController::class, 'viewSupport']);
    Route::get('/support/view-closed-ticket/{id}', [SupportController::class, 'viewClosed']);
    Route::get('/closed-ticket/{id}', [SupportController::class, 'closedTicket']);
    Route::post('/Ticket', [SupportController::class, 'Ticket'])->name('Ticket');

    // member security update
    Route::get('/member-security', [AdminController::class, 'security']);
    Route::post('securityUpdate', [AdminController::class, 'securityUpdate'])->name('securityUpdate');
    Route::get('member-login/{id}', [AdminController::class, 'memberLogin']);
    Route::get('/account-control', [AdminController::class, 'accountControl']);
    Route::get('/account-control/{id}', [AdminController::class, 'statusChange']);
    Route::get('/members/member-update/{id}', [AdminController::class, 'memberUpdate']);
    Route::post('/updateDetails', [AdminController::class, 'updateDetails'])->name('updateDetails');

    Route::get('/payment-history', [AdminController::class, 'paymentHistory']);
    Route::get('/new-withdrawal-request', [AdminController::class, 'newWithdrawelRequest']);
    Route::get('/cancelled-request', [AdminController::class, 'cancelledRequest']);
    Route::get('/package-details', [AdminController::class, 'packageDetails']);
    Route::get('/transaction', [AdminController::class, 'transaction']);

    // Member KYC Management
    Route::get('/new-kyc-requests', [AdminController::class, 'kycRequests']);
    Route::get('/verified-kyc-requests', [AdminController::class, 'verifiedKyc']);
    Route::post('updateKYC', [AdminController::class, 'updateKYC'])->name('updateKYC');
    Route::post('updatePan', [AdminController::class, 'updatePan'])->name('updatePan');
    Route::post('updateAfront', [AdminController::class, 'updateAfront'])->name('updateAfront');
    Route::post('updateAback', [AdminController::class, 'updateAback'])->name('updateAback');

    // account statement
    Route::get('/account-statement', [AdminController::class, 'accountStatement']);

    // Marketing -> WhatsApp Referral Messages
    // Route::get('/whatsapp-messages', [AdminWhatsappController::class, 'messagesIndex'])->name('admin.whatsapp.messages');
    // Route::post('/whatsapp-messages/save', [AdminWhatsappController::class, 'saveMessage'])->name('admin.whatsapp.saveMessage');
    // Route::get('/whatsapp-messages/delete/{id}', [AdminWhatsappController::class, 'deleteMessage'])->name('admin.whatsapp.deleteMessage');

    // Marketing -> WhatsApp Referral Reports
    Route::get('/whatsapp-reports', [AdminWhatsappController::class, 'reportsIndex'])->name('admin.whatsapp.reports');
    Route::get('/whatsapp-reports/export', [AdminWhatsappController::class, 'exportReports'])->name('admin.whatsapp.exportReports');

    // Promotional Media Management (Banners, PDFs, Plan Videos, Tutorial Videos)
    Route::get('/promotion-banners', [AdminMediaController::class, 'banners'])->name('admin.media.banners');
    Route::post('/promotion-banners/save', [AdminMediaController::class, 'saveBanner'])->name('admin.media.banners.save');
    Route::get('/promotion-banners/status/{id}', [AdminMediaController::class, 'toggleBannerStatus'])->name('admin.media.banners.status');
    Route::get('/promotion-banners/delete/{id}', [AdminMediaController::class, 'deleteBanner'])->name('admin.media.banners.delete');

    Route::get('/business-plan-pdfs', [AdminMediaController::class, 'pdfs'])->name('admin.media.pdfs');
    Route::post('/business-plan-pdfs/save', [AdminMediaController::class, 'savePdf'])->name('admin.media.pdfs.save');
    Route::get('/business-plan-pdfs/status/{id}', [AdminMediaController::class, 'togglePdfStatus'])->name('admin.media.pdfs.status');
    Route::get('/business-plan-pdfs/delete/{id}', [AdminMediaController::class, 'deletePdf'])->name('admin.media.pdfs.delete');

    Route::get('/plan-videos', [AdminMediaController::class, 'planVideos'])->name('admin.media.plan-videos');
    Route::post('/plan-videos/save', [AdminMediaController::class, 'savePlanVideo'])->name('admin.media.plan-videos.save');
    Route::get('/plan-videos/status/{id}', [AdminMediaController::class, 'toggleVideoStatus'])->name('admin.media.plan-videos.status');
    Route::get('/plan-videos/delete/{id}', [AdminMediaController::class, 'deleteVideo'])->name('admin.media.plan-videos.delete');

    Route::get('/tutorial-videos', [AdminMediaController::class, 'tutorialVideos'])->name('admin.media.tutorial-videos');
    Route::post('/tutorial-videos/save', [AdminMediaController::class, 'saveTutorialVideo'])->name('admin.media.tutorial-videos.save');
    Route::get('/tutorial-videos/status/{id}', [AdminMediaController::class, 'toggleVideoStatus'])->name('admin.media.tutorial-videos.status');
    Route::get('/tutorial-videos/delete/{id}', [AdminMediaController::class, 'deleteVideo'])->name('admin.media.tutorial-videos.delete');

    // PEPE Token Settings
    Route::get('/pepe-settings', [AdminController::class, 'pepeSettings'])->name('admin.pepeSettings');
    Route::post('/updatePepeSettings', [AdminController::class, 'updatePepeSettings'])->name('admin.updatePepeSettings');
});

// Member Routes
Route::get('/member/register/{id}', [UserAccessController::class, 'register']);
Route::post('/addressValidate', [UserAccessController::class, 'addressValidate'])->name('addressValidate');
Route::post('/userRegister', [UserAccessController::class, 'userRegister'])->name('userRegister');
Route::get('/member', [UserAccessController::class, 'index']);
Route::get('/signin', [UserAccessController::class, 'index']);
Route::get('/login', [UserAccessController::class, 'index']);

// Route::get('/member', [MemberDetailController::class, 'index']);
Route::post('/userLogin', [MemberDetailController::class, 'authenticate'])->name('userLogin');
Route::post('/getSponname', [MemberDetailController::class, 'getSponname']);
Route::post('/getUserid', [MemberDetailController::class, 'getUserid']);
Route::post('/getMember', [MemberDetailController::class, 'getMember']);
Route::get('/mem-forget-password', [MemberDetailController::class, 'memforgetPassword']);
Route::post('/memretrivePassword', [MemberDetailController::class, 'memretrivePassword'])->name('memretrivePassword');

Route::post('/send_register_otp', [MemberDetailController::class, 'sendRegisterOtp']);
Route::post('/verify_register_otp', [MemberDetailController::class, 'verifyRegisterOtp']);

Route::group(['prefix' => 'member', 'middleware' => 'MemberAuth'], function () {

    Route::get('/dashboard', [MemberDetailController::class, 'dashboard']);
    Route::get('/trading-dashboard', [MemberDetailController::class, 'tradingDashboard'])->name('member.tradingDashboard');
    Route::get('/tour', [MemberDetailController::class, 'tour']);
    Route::get('/logout', [MemberDetailController::class, 'logout']);

    Route::get('/create-user/{id}', [MemberDetailController::class, 'createUser']);

    // Activation Routes
    Route::get('/activation', [ActivationController::class, 'activation']);
    Route::post('/accountActivation', [ActivationController::class, 'accountActivation'])->name('accountActivation');
    Route::get('/activation-detail', [ActivationController::class, 'activationDetail'])->name('activationDetail');

    //  Staking Routes
    Route::get('/Staking/create', [InvestmentController::class, 'createStaking'])->name('Staking.create');
    Route::post('/createInvestment', [InvestmentController::class, 'createInvestment'])->name('createInvestment');
    Route::get('/Staking/details', [InvestmentController::class, 'StakingDetails'])->name('Staking.details');

    // Partnership Investment Routes
    Route::get('/partnership/create-investment', [PartnershipController::class, 'partCreateInvestment']);
    Route::post('/partCreateInvest', [PartnershipController::class, 'partCreateInvest'])->name('partCreateInvest');
    Route::get('/partnership/investment-details', [PartnershipController::class, 'investmentDetails']);

    // Profile Section
    Route::get('/profile/profile', [MemberDetailController::class, 'profile']);
    Route::post('insertProfile', [MemberDetailController::class, 'insertPdata'])->name('insertProfile');
    Route::get('/profile/security', [MemberDetailController::class, 'password']);

    Route::post('upgradeUser', [ActivationController::class, 'upgradeUser'])->name('upgradeUser');

    // Import Fund Section
    Route::get('/fund/deposit-fund', [FundController::class, 'importfund']);
    // Route::get('/fund/import-flt', [FundController::class, 'importFlt']);
    Route::get('/account/deposit-fund', [FundController::class, 'depositFund']);
    Route::post('addFund', [FundController::class, 'addFund'])->name('addFund');
    Route::post('addFltFund', [FundController::class, 'addFltFund'])->name('addFltFund');
    Route::get('/payment-success/{orderid}', [FundController::class, 'paySuccess']);
    Route::get('/payment-cancel/{orderid}', [FundController::class, 'payCancel']);
    Route::get('/fund/import-fund-history', [FundController::class, 'impfundhis']);
    Route::get('/fund/import-fund-flt-history', [FundController::class, 'impfundFlthis']);

    // p2p wallet
    Route::get('/p2p-wallet', [FundController::class, 'p2pwallet']);
    // Route::get('/p2p/fund-transfer', [FundController::class, 'fundTransfer']);
    Route::post('fundTrans', [FundController::class, 'fundTrans'])->name('fundTrans');
    Route::get('/p2p-history', [FundController::class, 'p2pHistory']);

    // Income Section
    Route::get('/income/monthly-staking-income', [IncomeController::class, 'roiIncome']);
    Route::get('/income/staking-level-income', [IncomeController::class, 'stakingLevelIncome']);
    Route::get('/income/level-income', [IncomeController::class, 'levelIncome']);
    Route::get('/income/single-leg-income', [IncomeController::class, 'singleLegIncome']);
    Route::get('/income/single-leg-details', [MemberDetailController::class, 'singlrLegDetails'])->name('member.single-leg-details');
    Route::get('/income/partnership/investment-incomes', [IncomeController::class, 'partnershipIncome']);
    Route::get('/income/team-withdrawal-commission', [IncomeController::class, 'withdrawalCommissionIncome']);

    // Withdrawal Section
    Route::get('/wallet/withdrawal', [WithdrawalController::class, 'mainWallets']);
    Route::post('initWithdrawal', [WithdrawalController::class, 'initWithdrawal'])->name('initWithdrawal');
    Route::get('/wallet/all-transaction', [WithdrawalController::class, 'walletTransfer']);
    Route::get('/wallet/withdrawal-history', [WithdrawalController::class, 'withdrlHistory']);
    Route::post('balValidate', [WithdrawalController::class, 'balValidate'])->name('balValidate');
    Route::post('getPrivateKey', [WithdrawalController::class, 'getPrivateKey'])->name('getPvtcd');
    Route::post('initiate-withdrawal', [WithdrawalController::class, 'withdrawal'])->name('initiate-withdrawal');

    // pepe_tokens withdrawal
    Route::post('pepeValidate', [WithdrawalController::class, 'pepeValidate'])->name('pepeValidate');
    Route::post('getPrivateKeyPepe', [WithdrawalController::class, 'getPrivateKeyPepe'])->name('getPrivateKeyPepe');
    Route::post('initiatePepeWithdrawal', [WithdrawalController::class, 'initiatePepeWithdrawal'])->name('initiatePepeWithdrawal');

    // Trading Wallet withdrawal validation
    Route::post('tradingWalletValidate', [WithdrawalController::class, 'tradingWalletValidate'])->name('tradingWalletValidate');

    // Geneology Section
    Route::get('/team/geneology', [MemberDetailController::class, 'geneology'])->name('member.team.geneology');
    Route::get('/team/view-geneology/{id}', [MemberDetailController::class, 'ViewGeneology'])->name('member.team.view-geneology');
    Route::get('/direct-team', [MemberDetailController::class, 'directteam']);
    Route::get('/level-details', [MemberDetailController::class, 'leveldetails']);
    Route::get('/level-members-details/{level}', [MemberDetailController::class, 'lelMemDetails']);

    // member usdt support section //
    Route::get('/support/usdt-support', [SupportController::class, 'usdtSupport']);
    Route::get('/support/usdt-support-ticket', [SupportController::class, 'usdtSupportTicket']);
    Route::post('/crtusdtsupport', [SupportController::class, 'crtusdtsupport'])->name('crtusdtsupport');
    Route::get('/support/view-usdt-support-ticket/{tid}', [SupportController::class, 'ViewusdtsupportTickets']);

    // member support section
    Route::get('/support/create-ticket', [SupportController::class, 'createTick']);
    Route::get('/support/support-tickets', [SupportController::class, 'supportTickets']);
    Route::get('/support/view-support-ticket/{tid}', [SupportController::class, 'ViewsupportTickets']);
    Route::post('/createTicket', [SupportController::class, 'createTicket'])->name('createTicket');
    Route::post('/MbReplyTicket', [SupportController::class, 'MbReplyTicket'])->name('MbReplyTicket');

    Route::post('passChange', [MemberDetailController::class, 'passChange'])->name('passChange');
    Route::post('txnChange', [MemberDetailController::class, 'txnChange'])->name('txnChange');
    Route::post('stackWithdrawal', [IncomeController::class, 'stackWithdrawal'])->name('stackWithdrawal');
    Route::post('stackConvert', [IncomeController::class, 'stackConvert'])->name('stackConvert');
    Route::post('FundReq', [FundController::class, 'addFundReq'])->name('FundReq');
    Route::post('AvtivationReq', [ActivationController::class, 'AvtivationReq'])->name('AvtivationReq');
    Route::post('upgradeRequest', [ActivationController::class, 'upgradeRequest'])->name('upgradeRequest');
    Route::post('memberActivate', [ActivationController::class, 'memberActivate'])->name('memberActivate');

    // Telegram Airdrop Coins
    Route::post('/add-airdrop-coins', [MemberDetailController::class, 'addAirdropCoins'])->name('addAirdropCoins');

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'allNotifications'])->name('member.notifications');
    Route::get('/notification/{id}', [NotificationController::class, 'showNotification'])->name('member.notification.show');
    Route::post('/notifications/read', [NotificationController::class, 'markRead'])->name('member.notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('member.notifications.readAll');

    // WhatsApp Referral Promotion
    Route::post('/whatsapp/verify', [WhatsappReferralController::class, 'verifyMobile'])->name('member.whatsapp.verify');
    Route::post('/whatsapp/process', [WhatsappReferralController::class, 'processReferral'])->name('member.whatsapp.process');
    Route::get('/whatsapp/referral-details', [WhatsappReferralController::class, 'referralDetails'])->name('member.whatsapp.referralDetails');

    // PEPE Token Redeem
    Route::get('/pepe/dapp-config', [WithdrawalController::class, 'getPepeDappConfig'])->name('member.pepe.dappConfig');
    Route::post('/pepe/prepare-claim', [WithdrawalController::class, 'preparePepeClaimVoucher'])->name('member.pepe.prepareClaim');
    Route::post('/pepe/redeem', [WithdrawalController::class, 'redeemPepe'])->name('member.pepe.redeem');
    Route::get('/pepe/redeem-history', [WithdrawalController::class, 'pepeRedeemHistory'])->name('member.pepe.redeemHistory');
    Route::get('/pepe/withdrawal', [WithdrawalController::class, 'pepeWallet'])->name('pepe.withdrawal');
    Route::post('initPepeWithdrawalForm', [WithdrawalController::class, 'initPepeWithdrawalForm'])->name('initPepeWithdrawalForm');

    // Promotional Banners
    Route::get('/promotion-banners', [MemberDetailController::class, 'promotionalBanners'])->name('member.promotional-banners');
    Route::get('/promotional-banners/download/{filename}', [MemberDetailController::class, 'downloadBanner'])->name('member.promotional-banners.download');

    // Business Plan PDF
    Route::get('/business-plan-pdf', [MemberDetailController::class, 'businessPlanPdf'])->name('member.business-plan-pdf');
    Route::get('/business-plan-pdf/download/{filename}', [MemberDetailController::class, 'downloadPdf'])->name('member.business-plan-pdf.download');
    Route::get('/business-plan-text', [MemberDetailController::class, 'businessPlanText'])->name('member.business-plan-text');

    // Plan Video
    Route::get('/plan-video', [MemberDetailController::class, 'planVideo'])->name('member.plan-video');
    Route::get('/plan-video/download/{filename}', [MemberDetailController::class, 'downloadVideo'])->name('member.plan-video.download');

    // Tutorial Video
    Route::get('/tutorial-video', [MemberDetailController::class, 'tutorialVideo'])->name('member.tutorial-video');
    Route::get('/tutorial-video/download/{filename}', [MemberDetailController::class, 'downloadTutorialVideo'])->name('member.tutorial-video.download');
});

Route::fallback(function () {
    return view('frontend.error404');
});

Route::get('dailyIncomeDis', [TempController::class, 'dailyIncomeDis']);
Route::get('roiLevelIncomeDis', [TempController::class, 'roiLevelIncomeDis']);
Route::get('/addTempMember/{memberid}', [TempController::class, 'addTempMember']);
Route::get('singleLegIncome', [TempController::class, 'singleLegIncome']);
Route::get('partnershipIncomeDis', [TempController::class, 'partnershipIncomeDis']);
Route::get('dailyPartTeamBizUpdate', [TempController::class, 'dailyPartTeamBizUpdate']);

Route::get('/temp', function () {});
