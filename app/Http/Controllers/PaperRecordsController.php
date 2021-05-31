<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\PaperRecord;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
            case Paper::TYPE_MOCK:
            case Paper::TYPE_OLD:
                $record = $paper->storeMockOrOldRecord();
                break;
        }

        return redirect()->route('paperRecords.show', $record);
    }

    public function show(Request $request, PaperRecord $record)
    {
        if ($record->is_end) {
            throw new AccessDeniedHttpException('考试已结束！禁止访问');
        }

        $record->load([
            'subject',
            'subject.parent',
            'paper',
            'items',
        ]);
        $paper = $record->paper;
        $paperItems = $record->paper_items;
        $paperType = Paper::$typeMap[$record->type];

        return view('records.modes.'. $record->mode, compact('record', 'paper', 'paperItems', 'paperType'));
    }

    public function result(Request $request, PaperRecord $record)
    {
        if (!$record->is_end) {
            throw new AccessDeniedHttpException('请先交卷再来查看解析！');
        }

        $paper = $record->paper;
        $paperType = Paper::$typeMap[$record->type];
        $result = [];
        foreach (Question::$typeMap as $key => $type) {
            $result[$key] = $record->getItemsResult($key);
        }
        return view('records.result', compact('record', 'paper', 'paperType', 'result'));
    }
}
