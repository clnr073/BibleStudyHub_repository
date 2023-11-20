<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testament;

class ReadingController extends Controller
{
    public function index(Testament $testament)
    {
        return $testament->get();
    }
}
