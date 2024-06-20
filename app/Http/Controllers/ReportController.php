<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReportNotification;

class ReportController extends Controller
{
    /**
     * Store a newly created report in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'reportable_id' => 'required|integer',
            'reportable_type' => 'required|string',
            'reason' => 'required|string|max:255',
        ]);

        $report = new Report();
        $report->user_id = Auth::id();
        $report->reportable_id = $request->reportable_id;
        $report->reportable_type = $request->reportable_type;
        $report->reason = $request->reason;
        $report->save();

        if ($report->reportable_type == 'App\Models\Comment') {
            // Get the comment
            $comment = Comment::find($report->reportable_id);
            if ($comment) {
                // Notify the user who owns the post that the comment belongs to
                $postOwner = $comment->post->user;
                $postOwner->notify(new ReportNotification($report));
            }
        } else {
            // Notify all admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new ReportNotification($report));
            }
        }

        return redirect()->back()->with('success', 'Report submitted successfully.');
    }
}
