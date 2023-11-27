<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    public function show(Note $note)
    {
        return view('notes.show')->with(['notes' => $note->get()]);
    }
}
