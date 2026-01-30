<?php

namespace App\Http\Controllers\Admin;

use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements
     */
    public function index()
    {
        $announcements = Announcement::ordered()->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message_ar' => 'required|string|max:500',
            'icon' => 'required|string|max:50',
            'is_active' => 'boolean',
            'order' => 'required|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'تم إضافة الإعلان بنجاح');
    }

    /**
     * Show the form for editing the specified announcement
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'message_ar' => 'required|string|max:500',
            'icon' => 'required|string|max:50',
            'is_active' => 'boolean',
            'order' => 'required|integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'تم تحديث الإعلان بنجاح');
    }

    /**
     * Remove the specified announcement
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'تم حذف الإعلان بنجاح');
    }

    /**
     * Update announcements order (AJAX)
     */
    public function reorder(Request $request)
    {
        $order = $request->input('order', []);

        foreach ($order as $index => $id) {
            Announcement::where('id', $id)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle announcement active status (AJAX)
     */
    public function toggleActive(Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $announcement->is_active
        ]);
    }
}
