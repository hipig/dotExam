<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\PaperRecord;
use Illuminate\Http\Request;

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
        $paperType = Paper::$typeMap[$record->type];

        return view('records.'. $record->mode, compact('record', 'paperType'));
    }
}
