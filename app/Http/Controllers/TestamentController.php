<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testament;

class TestamentController extends Controller
{
    public function showTestaments(Testament $testament)
    {
        return view('testament.show')->with(['testaments' => $testament->get()]);
    }
}