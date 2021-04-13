<?php

namespace App\Http\Controllers;

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
            ->orderBy('index', 'desc')
            ->get();

        $treeSubjects = $subjectService->getSubjectTree(null, $subjects);

        return view('subjects.index', compact('treeSubjects'));
    }

    public function show(Request $request, SubjectService $subjectService, Subject $parentSubject, Subject $subject = null, $paperType = 'chapter')
    {
        $subjects = Subject::query()
            ->where('level', '<=', 1)
            ->status()
            ->orderBy('index', 'desc')
            ->get();

        $treeSubjects = $subjectService->getSubjectTree(null, $subjects);

        if (!$subject) {
            $subject = ($parentSubject->children_groups[Subject::TRAIT_SPECIAL] ?? collect())->first();
        }

        return view('subjects.show', compact('treeSubjects', 'parentSubject', 'subject', 'paperType'));
    }
}
