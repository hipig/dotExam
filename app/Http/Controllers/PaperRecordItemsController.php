<?php

namespace App\Http\Controllers;

use App\Events\PaperRecordItemSaved;
use App\Events\PaperRecordSubmitted;
use App\Http\Requests\RecordItemBatchStoreRequest;
use App\Http\Requests\RecordItemStoreRequest;
use App\Models\PaperItem;
use App\Models\PaperRecord;
use App\Models\PaperRecordItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaperRecordItemsController extends Controller
{
    public function store(RecordItemStoreRequest $request, PaperRecord $record, PaperItem $paperItem)
    {
        $record->load(['subject', 'paper']);

        $recordItem = PaperRecordItem::query()->firstOrNew([
            'user_id' => Auth::id(),
            'record_id' => $record->id,
            'paper_item_id' => $paperItem->id,
        ]);
        $recordItem->store($record, $paperItem, $request->input('answer'));

        event(new PaperRecordItemSaved($recordItem));
        event(new PaperRecordSubmitted($record));

        return response()->json($recordItem);
    }

    public function batchStore(RecordItemBatchStoreRequest $request, PaperRecord $record)
    {
        $record = DB::transaction(function () use ($request, $record) {
            $items = $request->input('items');
            $itemsCount = count($items);

            $record->load(['subject', 'paper']);
            $record->done_count = $itemsCount;
            $record->done_time = $request->input('done_time');
            $record->is_end = $request->boolean('is_end');
            $record->save();

            foreach ($items as $item) {
                $paperItem = PaperItem::query()->find($item['paper_item_id']);

                $recordItem = PaperRecordItem::query()->firstOrNew([
                    'user_id' => Auth::id(),
                    'record_id' => $record->id,
                    'paper_item_id' => $item['paper_item_id'],
                ]);
                $recordItem->store($record, $paperItem, $item['answer']);

                event(new PaperRecordItemSaved($recordItem));
            }

            event(new PaperRecordSubmitted($record));

            return $record;
        });

        return response()->json($record->append('result_url'));
    }
}
