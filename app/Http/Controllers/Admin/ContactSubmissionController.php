<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    /**
     * Display contact submissions list
     */
    public function index(Request $request)
    {
        $type = $request->query('type', 'message'); // Default to 'message'
        
        $query = ContactSubmission::query();
        
        // Debug log
        \Log::info('ContactSubmissionController index called', [
            'type_param' => $type,
            'all_types' => ContactSubmission::distinct()->pluck('type')->toArray(),
            'tailoring_count' => ContactSubmission::where('type', 'tailoring_request')->count(),
            'message_count' => ContactSubmission::where('type', 'message')->count(),
            'total_count' => ContactSubmission::count(),
        ]);
        
        if ($type === 'message') {
            $query->where('type', 'message');
        } elseif ($type === 'tailoring_request') {
            $query->where('type', 'tailoring_request');
        }
        
        $submissions = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.contact-submissions.index', compact('submissions', 'type'));
    }

    /**
     * Display a specific submission
     */
    public function show($id)
    {
        $submission = ContactSubmission::findOrFail($id);
        
        // Mark as read if not already
        if (!$submission->is_read) {
            $submission->markAsRead();
        }
        
        return view('admin.contact-submissions.show', compact('submission'));
    }

    /**
     * Delete a submission
     */
    public function destroy($id)
    {
        $submission = ContactSubmission::findOrFail($id);
        $type = $submission->type;
        
        // Delete design image if exists
        if ($submission->design_image && \Storage::disk('public')->exists($submission->design_image)) {
            \Storage::disk('public')->delete($submission->design_image);
        }
        
        $submission->delete();
        
        return redirect()->route('admin.contact-submissions.index', ['type' => $type])
                        ->with('success', 'Submission deleted successfully');
    }

    /**
     * Mark submission as read
     */
    public function markAsRead($id)
    {
        $submission = ContactSubmission::findOrFail($id);
        $submission->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * Get unread count
     */
    public function getUnreadCount()
    {
        $totalUnread = ContactSubmission::where('is_read', false)->count();
        $messageUnread = ContactSubmission::where('type', 'message')->where('is_read', false)->count();
        $tailoringUnread = ContactSubmission::where('type', 'tailoring_request')->where('is_read', false)->count();
        
        return response()->json([
            'total' => $totalUnread,
            'messages' => $messageUnread,
            'tailoring' => $tailoringUnread,
        ]);
    }
}
