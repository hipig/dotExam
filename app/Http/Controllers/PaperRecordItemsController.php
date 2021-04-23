<?php

namespace App\Http\Controllers;

use App\Models\PaperItem;
use App\Models\PaperRecord;
use App\Models\PaperRecordItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaperRecordItemsController extends Controller
{
    public function store(Request $request, PaperRecord $record)
    {
        $record->load(['subject', 'paper']);
        $paperItem = PaperItem::query()->findOrFail($request->bank_item_id);

        $recordItem = PaperRecordItem::query()->firstOrNew([
            'user_id' => Auth::user(),
            'record_id' => $record,
            'paper_item_id' => $paperItem->id
        ]);
        $recordItem->subject()->associate($record->subject);
        $recordItem->paper()->associate($record->paper);
        $recordItem->question()->associate($paperItem->question);
        $recordItem->save();

        return response()->json($recordItem);
    }

    public function batchStore(Request $request)
    {

    }
}
