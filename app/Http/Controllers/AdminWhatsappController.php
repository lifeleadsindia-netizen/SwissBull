<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\WhatsappReferral;
use App\Models\WhatsappReferralMessage;
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
     * Display WhatsApp Referral Reports.
     */
    public function reportsIndex(Request $request)
    {
        $query = WhatsappReferral::query();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        if ($request->filled('member_id')) {
            $query->where('member_id', 'like', '%'.trim($request->input('member_id')).'%');
        }

        if ($request->filled('mobile_number')) {
            $query->where('mobile_number', 'like', '%'.trim($request->input('mobile_number')).'%');
        }

        $totalReferrals = (clone $query)->count();
        $totalPepeDistributed = (clone $query)->sum('reward_amount');

        $referrals = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.marketing.whatsapp-reports', compact('referrals', 'totalReferrals', 'totalPepeDistributed'));
    }

    /**
     * Export WhatsApp Referral Reports to CSV.
     */
    public function exportReports(Request $request)
    {
        $query = WhatsappReferral::query();

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        if ($request->filled('member_id')) {
            $query->where('member_id', 'like', '%'.trim($request->input('member_id')).'%');
        }

        if ($request->filled('mobile_number')) {
            $query->where('mobile_number', 'like', '%'.trim($request->input('mobile_number')).'%');
        }

        $records = $query->orderBy('created_at', 'desc')->get();

        $filename = 'whatsapp_referral_report_'.date('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['S.No', 'Member ID', 'Recipient Mobile', 'Reward Amount (PEPE)', 'Status', 'Date Time']);

            $i = 1;
            foreach ($records as $row) {
                fputcsv($file, [
                    $i++,
                    $row->member_id,
                    $row->mobile_number,
                    $row->reward_amount,
                    $row->status,
                    $row->created_at ? $row->created_at->format('d-m-Y H:i:s') : '-',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
