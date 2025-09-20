<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // REMOVED: Constructor that was forcing employee guard
    // This was causing HR users to be redirected to login

    // Display inbox (main messages page)
    public function inbox()
    {
        $user = Auth::user();

        if ($user->is_role == 1) {
            // HR sees all messages they sent
            return redirect()->route('messages.sent');
        } else {
            // Employee sees messages they received
            $messages = Message::whereJsonContains('recipient_ids', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('backend.messages.inbox', compact('messages'));
        }
    }

    // HR: Display form to create new message with branch/company filtering
    public function create(Request $request)
    {
        // Only HR can access this
        if (Auth::user()->is_role != 1) {
            abort(403, 'Unauthorized access');
        }

        $company_id = session('company_id');
        $branch_id = session('branch_id');

        // If branch_id is null, show all employees for the company
        if (empty($branch_id)) {
            $employees = User::where('company_id', $company_id)
                ->where('is_role', 0) // Only employees
                ->get();
        } else {
            // Check if the current branch is the main branch
            $currentBranch = \DB::table('branches')
                ->where('id', $branch_id)
                ->select('is_main')
                ->first();

            // If it's the main branch (is_main == 1), show all employees for the company
            if ($currentBranch && $currentBranch->is_main == 1) {
                $employees = User::where('company_id', $company_id)
                    ->where('is_role', 0) // Only employees
                    ->get();
            } else {
                // Otherwise, filter by the specific branch_id
                $employees = User::where('branch_id', $branch_id)
                    ->where('is_role', 0) // Only employees
                    ->get();
            }
        }

        return view('backend.messages.create', compact('employees'));
    }

    // HR: Store new message
    public function store(Request $request)
    {
        if (Auth::user()->is_role != 1) {
            abort(403, 'Unauthorized access');
        }

        $validatedData = $request->validate([
            'recipient_ids' => 'required|array|min:1',
            'recipient_ids.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string|min:1',
            'is_urgent' => 'nullable|boolean'
        ]);

        try {
            Message::create([
                'sender_id' => Auth::id(),
                'recipient_ids' => $validatedData['recipient_ids'], // Let Laravel handle JSON encoding
                'subject' => $validatedData['subject'],
                'content' => $validatedData['content'],
                'is_urgent' => $request->has('is_urgent') ? 1 : 0
            ]);

            return redirect()->route('messages.sent')->with('success', __('h_message.message_sent_success'));
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Failed to send message: ' . $e->getMessage()]);
        }
    }

    // HR: View sent messages
    public function sent()
    {
        if (Auth::user()->is_role != 1) {
            abort(403, 'Unauthorized access');
        }

        $messages = Message::where('sender_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Load recipient names for each message
        $messages->getCollection()->transform(function ($message) {
            // Ensure recipient_ids is an array
            $recipientIds = is_array($message->recipient_ids) ? $message->recipient_ids : [];

            if (!empty($recipientIds)) {
                $recipients = User::whereIn('id', $recipientIds)->pluck('name')->toArray();
                $message->recipient_names = $recipients;
                $message->recipient_count = count($recipients);
            } else {
                $message->recipient_names = [];
                $message->recipient_count = 0;
            }

            return $message;
        });

        return view('backend.messages.sent', compact('messages'));
    }

    // Show specific message
    public function show(Message $message)
    {
        $user = Auth::user();

        // Check if user can view this message
        if ($user->is_role == 1 && $message->sender_id != $user->id) {
            abort(403, 'You can only view messages you sent');
        } elseif ($user->is_role == 0 && !in_array($user->id, $message->recipient_ids ?? [])) {
            abort(403, 'You are not authorized to view this message');
        }

        // Mark as read if employee is viewing
        if ($user->is_role == 0) {
            $message->markAsRead($user->id);
        }

        return view('backend.messages.show', compact('message'));
    }

    // Delete message (HR only)
    public function destroy(Message $message)
    {
        if (Auth::user()->is_role != 1 || $message->sender_id != Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        $message->delete();
        return redirect()->route('messages.sent')->with('success', __('h_message.message_deleted_success'));
    }

    // Employee: View inbox messages
    public function employeeInbox()
    {
        // Temporarily set employee guard for this method only
        Auth::shouldUse('employee');
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Please login to continue');
        }

        if ($user->is_role != 0) {
            abort(403, 'Unauthorized access');
        }

        // Method 1: Try with string cast
        $messages = Message::whereJsonContains('recipient_ids', (string)$user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // If Method 1 doesn't work, try Method 2: Raw JSON query
        if ($messages->isEmpty()) {
            $messages = Message::whereRaw('JSON_CONTAINS(recipient_ids, ?)', ['"'.$user->id.'"'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        // If Method 2 doesn't work, try Method 3: Like query (less efficient but works)
        if ($messages->isEmpty()) {
            $messages = Message::where('recipient_ids', 'like', '%"'.$user->id.'"%')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        // Load sender information and read status for each message
        $messages->getCollection()->transform(function ($message) use ($user) {
            $message->load('sender');
            $message->is_read_by_me = $message->isReadBy($user->id);
            $message->read_time = $message->getReadTime($user->id);
            return $message;
        });

        return view('EmployeeInterface.messages.inbox', compact('messages'));
    }

    // Employee: View specific message
    public function employeeShow(Message $message)
    {
        // Temporarily set employee guard for this method only
        Auth::shouldUse('employee');
        $user = Auth::user();

        // Only employees can access this
        if ($user->is_role != 0) {
            abort(403, 'Unauthorized access');
        }

        // Check if user is a recipient of this message
        if (!in_array($user->id, $message->recipient_ids ?? [])) {
            abort(403, 'You are not authorized to view this message');
        }

        // Mark message as read
        $message->markAsRead($user->id);

        // Load sender information
        $message->load('sender');

        return view('EmployeeInterface.messages.show', compact('message'));
    }

    // Employee: Mark message as read (AJAX endpoint)
    public function markAsRead(Message $message)
    {
        // Temporarily set employee guard for this method only
        Auth::shouldUse('employee');
        $user = Auth::user();

        // Only employees can access this
        if ($user->is_role != 0) {
            abort(403, 'Unauthorized access');
        }

        // Check if user is a recipient
        if (!in_array($user->id, $message->recipient_ids ?? [])) {
            abort(403, 'You are not authorized to access this message');
        }

        $message->markAsRead($user->id);

        return response()->json(['success' => true, 'message' => 'Message marked as read']);
    }
}
