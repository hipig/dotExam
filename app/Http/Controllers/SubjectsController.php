<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\Subject;
use App\Services\SubjectService;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    public function index(Request $request, SubjectService $subjectService)
    {
        $subjects = Subject::query()
            ->where('level', '<=', 1)
            ->status()
            ->orderIndex()
            ->get();

        $treeSubjects = $subjectService->getSubjectTree(null, $subjects);

        return view('subjects.index', compact('treeSubjects'));
    }

    public function show(Request $request, SubjectService $subjectService, Subject $parentSubject, Subject $subject = null, $paperType = 'chapter')
    {
        $paperTypeMap = Paper::$typeMap;

        $subjects = Subject::query()
            ->where('level', '<=', 1)
            ->status()
            ->orderIndex()
            ->get();

        $treeSubjects = $subjectService->getSubjectTree(null, $subjects);

        if (!$subject) {
            $subject = ($parentSubject->children_groups[Subject::TRAIT_SPECIAL] ?? collect())->first();
        }

        switch ($paperType) {
            case Paper::TYPE_CHAPTER:
                $query = $subject->chapterPapers()
                    ->with('children')
                    ->whereNull('parent_id');
                break;
            case Paper::TYPE_MOCK:
                $query = $subject->mockPapers();
                break;
            case Paper::TYPE_OLD:
                $query = $subject->oldPapers();
                break;
            case Paper::TYPE_DAILY:
                $query = $subject->dailyPapers()
                    ->with('topThreeItems');
                break;
        }

        $papers = $query->status()
            ->orderIndex()
            ->latest()
            ->get();

        return view('subjects.show', compact('treeSubjects', 'parentSubject', 'subject', 'paperTypeMap', 'paperType', 'papers'));
    }
}
