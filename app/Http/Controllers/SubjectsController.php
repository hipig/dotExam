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
                $papers = $subject->chapterPapers()
                    ->with('children')
                    ->whereNull('parent_id')
                    ->status()
                    ->orderIndex()
                    ->latest()
                    ->get();
                break;
            case Paper::TYPE_MOCK:

                break;
            case Paper::TYPE_OLD:

                break;
            case Paper::TYPE_DAILY:

                break;
        }

        return view('subjects.show', compact('treeSubjects', 'parentSubject', 'subject', 'paperTypeMap', 'paperType', 'papers'));
    }
}
