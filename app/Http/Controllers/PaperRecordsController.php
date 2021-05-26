<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\PaperRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaperRecordsController extends Controller
{
    public function store(Request $request, Paper $paper)
    {
        switch ($paper->type) {
            case Paper::TYPE_CHAPTER:
                $record = $paper->storeChapterRecord(
                    $request->range,
                    $request->type,
                    $request->size,
                    $request->mode
                );
                break;
        }

        return redirect()->route('paperRecords.show', $record);
    }

    public function show(Request $request, PaperRecord $record)
    {
        $record->load([
            'subject',
            'subject.parent',
            'paper',
        ]);
        $paperItems = $record->paper_items->map(function ($item) use ($record) {
            $item->record = $item->recordItems()
                ->where('user_id', Auth::id())
                ->where('record_id', $record->id)
                ->first();

            return $item;
        });
        $paperType = Paper::$typeMap[$record->type];

        return view('records.'. $record->mode, compact('record', 'paperItems', 'paperType'));
    }
}
