<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    public function index(Note $note)
    {
        return view('notes.index')->with(['notes' => $note->get()]);
    }
}
