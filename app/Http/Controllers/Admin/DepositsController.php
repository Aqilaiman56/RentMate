<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DepositsController extends Controller
{
    public function index()
    {
        return view('admin.deposits');
    }
}