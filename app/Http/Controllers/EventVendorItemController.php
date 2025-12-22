<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventVendorItem;
use App\Models\Vendor;
use App\Models\VendorCatalogItem;
use App\Models\VendorProduct;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventVendorItemController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show the page to manage items for a specific vendor in an event.
     */
    public function index(Event $event, Vendor $vendor)
    {
        $this->authorize('update', $event);

        // Ensure vendor is attached to event
        if (!$event->vendors->contains($vendor->id)) {
            return redirect()->route('events.show', $event)->with('error', 'Vendor tidak terdaftar di event ini.');
        }

        // Get existing items for this event and vendor
        $existingItems = EventVendorItem::where('event_id', $event->id)
            ->where('vendor_id', $vendor->id)
            ->with('itemable')
            ->get();

        // Get available products (Services)
        $products = $vendor->products;

        // Get available catalog items
        $catalogItems = $vendor->catalogItems()->with('category')->get();

        // Get available packages
        $packages = $vendor->packages()->with('services')->where('is_visible', true)->get();

        return view('events.vendor-items.index', compact('event', 'vendor', 'existingItems', 'products', 'catalogItems', 'packages'));
    }

    /**
     * Store selected items.
     */
    public function store(Request $request, Event $event, Vendor $vendor)
    {
        $this->authorize('update', $event);

        $request->validate([
            'selected_items' => 'required|array',
            'items' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $event, $vendor) {
            foreach ($request->selected_items as $uid) {
                if (!isset($request->items[$uid])) continue;
                
                $itemData = $request->items[$uid];
                
                // Determine model type based on item type
                $modelType = match($itemData['type']) {
                    'product' => VendorProduct::class,
                    'catalog_item' => VendorCatalogItem::class,
                    'package' => \App\Models\VendorPackage::class,
                    default => VendorProduct::class,
                };
                
                EventVendorItem::create([
                    'event_id' => $event->id,
                    'vendor_id' => $vendor->id,
                    'itemable_type' => $modelType,
                    'itemable_id' => $itemData['id'],
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'notes' => $itemData['notes'] ?? null,
                ]);
            }

            // Update the total agreed price in event_vendor pivot
            $totalItemsPrice = EventVendorItem::where('event_id', $event->id)
                ->where('vendor_id', $vendor->id)
                ->sum(DB::raw('price * quantity'));

            $event->vendors()->updateExistingPivot($vendor->id, [
                'agreed_price' => $totalItemsPrice
            ]);
        });

        return redirect()->route('events.vendor-items.index', [$event->id, $vendor->id])
            ->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Remove an item.
     */
    public function destroy(EventVendorItem $item)
    {
        $this->authorize('update', $item->event);

        $event = $item->event;
        $vendor = $item->vendor;

        $item->delete();

        // Update total price
        $totalItemsPrice = EventVendorItem::where('event_id', $event->id)
            ->where('vendor_id', $vendor->id)
            ->sum(DB::raw('price * quantity'));

        $event->vendors()->updateExistingPivot($vendor->id, [
            'agreed_price' => $totalItemsPrice
        ]);

        return back()->with('success', 'Item berhasil dihapus.');
    }
}
