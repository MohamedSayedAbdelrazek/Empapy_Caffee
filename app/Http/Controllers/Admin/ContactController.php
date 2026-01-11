<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display list of contact messages
     */
    public function index(Request $request)
    {
        $query = ContactMessage::with('user')->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(20);

        $stats = [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::new()->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
        ];

        return view('admin.contacts.index', compact('messages', 'stats'));
    }

    /**
     * Show a single message
     */
    public function show(ContactMessage $contact)
    {
        // Mark as read if new
        if ($contact->isNew()) {
            $contact->markAsRead();
        }

        return view('admin.contacts.show', ['message' => $contact]);
    }

    /**
     * Update message status or add notes
     */
    public function update(Request $request, ContactMessage $contact)
    {
        $request->validate([
            'status' => 'nullable|in:new,read,replied',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $contact->update($request->only(['status', 'admin_notes']));

        return back()->with('success', 'تم تحديث الرسالة بنجاح');
    }

    /**
     * Delete a message
     */
    public function destroy(ContactMessage $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', 'تم حذف الرسالة بنجاح');
    }
}
