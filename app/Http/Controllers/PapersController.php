<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use Illuminate\Http\Request;

class PapersController extends Controller
{


    public function getTotalities(Request $request, Paper $paper)
    {

        $range = $request->range ?? 'all';

        $totalities = $paper->getTotalities($range);

        return response()->json($totalities);
    }
}
