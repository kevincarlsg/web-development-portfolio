<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function getSubjects(School $school)
    {
        return response()->json($school->subjects);
    }
}

