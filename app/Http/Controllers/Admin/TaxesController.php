<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TaxesController extends Controller
{
    public function index()
    {
        return view('admin.taxes');
    }
}