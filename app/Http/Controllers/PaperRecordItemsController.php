<?php

namespace App\Http\Controllers;

use App\Events\PaperRecordItemSaved;
use App\Events\PaperRecordSubmitted;
use App\Models\PaperItem;
use App\Models\PaperRecord;
use App\Models\PaperRecordItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaperRecordItemsController extends Controller
{
    public function store(Request $request, PaperRecord $record, PaperItem $paperItem)
    {
        $record->load(['subject', 'paper']);

        $recordItem = PaperRecordItem::query()->firstOrNew([
            'user_id' => Auth::id(),
            'record_id' => $record->id,
            'paper_item_id' => $paperItem->id
        ]);
        $recordItem->subject()->associate($record->subject);
        $recordItem->paper()->associate($record->paper);
        $recordItem->question()->associate($paperItem->question);
        $recordItem->question_type = $paperItem->question->type;
        $recordItem->answer = $request->answer;
        $recordItem->is_correct = PaperRecordItem::checkAnswer($request->answer, $paperItem->question->answer, $paperItem->question->type);
        $recordItem->save();

        event(new PaperRecordItemSaved($recordItem));
        event(new PaperRecordSubmitted($record));

        return response()->json($recordItem->append('answer_text'));
    }

    public function batchStore(Request $request)
    {

    }
}
