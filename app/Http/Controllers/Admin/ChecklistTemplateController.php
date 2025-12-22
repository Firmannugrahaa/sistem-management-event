<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChecklistTemplate;
use App\Models\ChecklistTemplateItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ChecklistTemplateController extends Controller
{
    /**
     * Display a listing of checklist templates.
     */
    public function index()
    {
        Gate::authorize('manage-checklist-templates');
        
        $templates = ChecklistTemplate::withCount('items')->get();
        
        return view('admin.checklist-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new template.
     */
    public function create()
    {
        Gate::authorize('manage-checklist-templates');
        
        return view('admin.checklist-templates.create');
    }

    /**
     * Store a newly created template in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('manage-checklist-templates');
        
        $validated = $request->validate([
            'event_type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        $template = ChecklistTemplate::create($validated);

        return redirect()->route('admin.checklist-templates.edit', $template)
            ->with('success', 'Template berhasil dibuat! Silakan tambahkan item checklist.');
    }

    /**
     * Display the specified template with items.
     */
    public function show(ChecklistTemplate $template)
    {
        Gate::authorize('manage-checklist-templates');
        
        $template->load('items');
        
        return view('admin.checklist-templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified template.
     */
    public function edit(ChecklistTemplate $template)
    {
        Gate::authorize('manage-checklist-templates');
        
        $template->load(['items' => function($query) {
            $query->orderBy('order');
        }]);
        
        $itemsByCategory = $template->items->groupBy('category');
        
        return view('admin.checklist-templates.edit', compact('template', 'itemsByCategory'));
    }

    /**
     * Update the specified template in storage.
     */
    public function update(Request $request, ChecklistTemplate $template)
    {
        Gate::authorize('manage-checklist-templates');
        
        $validated = $request->validate([
            'event_type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        $template->update($validated);

        return redirect()->route('admin.checklist-templates.index')
            ->with('success', 'Template berhasil diperbarui!');
    }

    /**
     * Remove the specified template from storage.
     */
    public function destroy(ChecklistTemplate $template)
    {
        Gate::authorize('manage-checklist-templates');
        
        // Delete all items first
        $template->items()->delete();
        
        // Delete template
        $template->delete();

        return redirect()->route('admin.checklist-templates.index')
            ->with('success', 'Template berhasil dihapus!');
    }

    /**
     * Add new item to template.
     */
    public function storeItem(Request $request, ChecklistTemplate $template)
    {
        Gate::authorize('manage-checklist-templates');
        
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
        ]);

        // Get max order for this template
        $maxOrder = $template->items()->max('order') ?? 0;

        ChecklistTemplateItem::create([
            'template_id' => $template->id,
            'category' => $validated['category'],
            'title' => $validated['title'],
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('admin.checklist-templates.edit', $template)
            ->with('success', 'Item berhasil ditambahkan!');
    }

    /**
     * Update template item.
     */
    public function updateItem(Request $request, ChecklistTemplateItem $item)
    {
        Gate::authorize('manage-checklist-templates');
        
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer|min:1',
        ]);

        $item->update($validated);

        return redirect()->route('admin.checklist-templates.edit', $item->template)
            ->with('success', 'Item berhasil diperbarui!');
    }

    /**
     * Delete template item.
     */
    public function destroyItem(ChecklistTemplateItem $item)
    {
        Gate::authorize('manage-checklist-templates');
        
        $template = $item->template;
        $item->delete();

        return redirect()->route('admin.checklist-templates.edit', $template)
            ->with('success', 'Item berhasil dihapus!');
    }

    /**
     * Reorder template items.
     */
    public function reorderItems(Request $request, ChecklistTemplate $template)
    {
        Gate::authorize('manage-checklist-templates');
        
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:checklist_template_items,id',
            'items.*.order' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['items'] as $itemData) {
                ChecklistTemplateItem::where('id', $itemData['id'])
                    ->update(['order' => $itemData['order']]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Urutan berhasil diperbarui!']);
    }
}
