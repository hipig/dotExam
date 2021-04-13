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
}
