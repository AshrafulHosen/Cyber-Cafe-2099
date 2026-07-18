<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\UserInventory;

class InventoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $items = InventoryItem::all();
        $ownedItemIds = $user->inventoryItems()->pluck('inventory_items.id')->toArray();

        return view('store', compact('items', 'ownedItemIds', 'user'));
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id'
        ]);

        $user = auth()->user();
        $item = InventoryItem::findOrFail($request->item_id);

        // Check if already owned
        $alreadyOwned = $user->inventoryItems()->where('inventory_items.id', $item->id)->exists();
        if ($alreadyOwned) {
            return response()->json(['success' => false, 'message' => 'You already own this item.'], 400);
        }

        // Check credits
        if ($user->cyber_credits < $item->price) {
            return response()->json(['success' => false, 'message' => 'Insufficient cyber credits.'], 400);
        }

        // Deduct credits and add to inventory
        $user->cyber_credits -= $item->price;
        $user->save();

        UserInventory::create([
            'user_id' => $user->id,
            'inventory_item_id' => $item->id,
            'status' => 'active'
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => $user->id,
            'type'    => 'STORE',
            'message' => "Purchased {$item->name} from the cyber store."
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Purchase successful.',
            'new_balance' => $user->cyber_credits
        ]);
    }

    public function toggleEquip(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:inventory_items,id'
        ]);

        $user = auth()->user();
        
        $pivot = UserInventory::where('user_id', $user->id)
                    ->where('inventory_item_id', $request->item_id)
                    ->first();

        if (!$pivot) {
            return response()->json(['success' => false, 'message' => 'Item not owned.'], 403);
        }

        if ($pivot->status === 'EQUIPPED') {
            $pivot->status = 'STORED';
        } else {
            // Optional: If we only allow 1 room equipped at a time, we could unequip other rooms here
            $item = InventoryItem::find($request->item_id);
            if ($item->type === 'room') {
                // Unequip all other rooms
                $roomItemIds = InventoryItem::where('type', 'room')->pluck('id');
                UserInventory::where('user_id', $user->id)
                    ->whereIn('inventory_item_id', $roomItemIds)
                    ->update(['status' => 'STORED']);
            }
            $pivot->status = 'EQUIPPED';
        }
        $pivot->save();

        return response()->json([
            'success' => true,
            'new_status' => $pivot->status
        ]);
    }
}
