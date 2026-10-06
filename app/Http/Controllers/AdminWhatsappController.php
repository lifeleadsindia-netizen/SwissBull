<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\PepeRewardLog;
use App\Models\WhatsappReferral;
use App\Models\WhatsappReferralMessage;
use App\Services\PepeRewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminWhatsappController extends Controller
{
    /**
     * Display WhatsApp Referral Messages Manager.
     */
    public function messagesIndex()
    {
        $messages = WhatsappReferralMessage::orderBy('id', 'desc')->get();
        $members = MemberDetail::orderBy('memberid', 'asc')->get(['id', 'memberid', 'name']);

        return view('admin.marketing.whatsapp-messages', compact('messages', 'members'));
    }

    /**
     * Create or Update WhatsApp Referral Message.
     */
    public function saveMessage(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:Active,Inactive',
            'apply_to' => 'required|in:All Members,Specific Members',
            'target_member_ids' => 'required_if:apply_to,Specific Members|array',
        ]);

        if ($request->filled('id')) {
            $msg = WhatsappReferralMessage::findOrFail($request->input('id'));
        } else {
            $msg = new WhatsappReferralMessage;
        }

        $msg->title = $request->input('title');
        $msg->content = $request->input('content');
        $msg->status = $request->input('status');
        $msg->apply_to = $request->input('apply_to');
        $msg->target_member_ids = $request->input('apply_to') === 'Specific Members' ? $request->input('target_member_ids', []) : [];
        $msg->save();

        session()->flash('successMsg', 'WhatsApp Referral Message saved successfully.');

        return redirect()->back();
    }

    /**
     * Delete WhatsApp Referral Message.
     */
    public function deleteMessage($id)
    {
        WhatsappReferralMessage::where('id', $id)->delete();
        session()->flash('delMsg', 'WhatsApp Referral Message deleted successfully.');

        return redirect()->back();
    }

    /**
     * Display WhatsApp & Promotion Airdrop Reports.
     */
    public function reportsIndex(Request $request)
    {
        $hasLogs = PepeRewardLog::exists();
        $query = $hasLogs ? PepeRewardLog::query() : WhatsappReferral::query();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        if ($request->filled('member_id')) {
            $query->where('member_id', 'like', '%'.trim($request->input('member_id')).'%');
        }

        if ($hasLogs && $request->filled('reward_type') && $request->input('reward_type') !== 'all') {
            $query->where('reward_type', $request->input('reward_type'));
        }

        if ($request->filled('search')) {
            $searchTerm = trim($request->input('search'));
            if ($hasLogs) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('mobile_number', 'like', '%'.$searchTerm.'%')
                        ->orWhere('referred_member_id', 'like', '%'.$searchTerm.'%')
                        ->orWhere('description', 'like', '%'.$searchTerm.'%');
                });
            } else {
                $query->where('mobile_number', 'like', '%'.$searchTerm.'%');
            }
        }

        // Summary calculations
        $totalReferrals = (clone $query)->count();
        $totalPepeDistributed = (clone $query)->sum('reward_amount');

        $msgCount = PepeRewardLog::where('reward_type', PepeRewardService::TYPE_MESSAGE)->count();
        $msgTokens = (float) PepeRewardLog::where('reward_type', PepeRewardService::TYPE_MESSAGE)->sum('reward_amount');
        if ($msgCount === 0) {
            $msgCount = WhatsappReferral::count();
            $msgTokens = (float) WhatsappReferral::sum('reward_amount');
        }

        $regCount = PepeRewardLog::where('reward_type', PepeRewardService::TYPE_DIRECT_REGISTRATION)->count();
        $regTokens = (float) PepeRewardLog::where('reward_type', PepeRewardService::TYPE_DIRECT_REGISTRATION)->sum('reward_amount');

        $actCount = PepeRewardLog::where('reward_type', PepeRewardService::TYPE_DIRECT_ACTIVATION)->count();
        $actTokens = (float) PepeRewardLog::where('reward_type', PepeRewardService::TYPE_DIRECT_ACTIVATION)->sum('reward_amount');

        $referrals = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.marketing.whatsapp-reports', compact(
            'referrals',
            'totalReferrals',
            'totalPepeDistributed',
            'msgCount',
            'msgTokens',
            'regCount',
            'regTokens',
            'actCount',
            'actTokens',
            'hasLogs'
        ));
    }

    /**
     * Export WhatsApp Referral Reports to CSV.
     */
    public function exportReports(Request $request)
    {
        $hasLogs = PepeRewardLog::exists();
        $query = $hasLogs ? PepeRewardLog::query() : WhatsappReferral::query();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        if ($request->filled('member_id')) {
            $query->where('member_id', 'like', '%'.trim($request->input('member_id')).'%');
        }

        if ($hasLogs && $request->filled('reward_type') && $request->input('reward_type') !== 'all') {
            $query->where('reward_type', $request->input('reward_type'));
        }

        if ($request->filled('search')) {
            $searchTerm = trim($request->input('search'));
            if ($hasLogs) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('mobile_number', 'like', '%'.$searchTerm.'%')
                        ->orWhere('referred_member_id', 'like', '%'.$searchTerm.'%')
                        ->orWhere('description', 'like', '%'.$searchTerm.'%');
                });
            } else {
                $query->where('mobile_number', 'like', '%'.$searchTerm.'%');
            }
        }

        $records = $query->orderBy('created_at', 'desc')->get();

        $filename = 'promotion_airdrop_pepe_report_'.date('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($records, $hasLogs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['S.No', 'Member ID', 'Reward Rule / Type', 'Details / Reference', 'Reward Amount (PEPE)', 'Date & Time']);

            $i = 1;
            foreach ($records as $row) {
                $rewardTypeLabel = 'Rule 1: WhatsApp Message';
                $details = $row->mobile_number ?? '';

                if ($hasLogs) {
                    if (($row->reward_type ?? '') === 'direct_registration') {
                        $rewardTypeLabel = 'Rule 2: Direct Registration';
                        $details = 'Referred ID: '.($row->referred_member_id ?? '-').($row->description ? ' ('.$row->description.')' : '');
                    } elseif (($row->reward_type ?? '') === 'direct_activation') {
                        $rewardTypeLabel = 'Rule 3: Direct Activation';
                        $details = 'Activated ID: '.($row->referred_member_id ?? '-').($row->description ? ' ('.$row->description.')' : '');
                    } else {
                        $rewardTypeLabel = 'Rule 1: WhatsApp Message';
                        $details = $row->mobile_number ?? ($row->description ?? '-');
                    }
                }

                fputcsv($file, [
                    $i++,
                    $row->member_id,
                    $rewardTypeLabel,
                    $details,
                    $row->reward_amount,
                    $row->created_at ? $row->created_at->format('d-m-Y H:i:s') : '-',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
