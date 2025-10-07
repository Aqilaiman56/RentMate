<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PenaltiesController extends Controller
{
    public function index()
    {
        return view('admin.penalties');
    }
}