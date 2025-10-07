<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ListingsController extends Controller
{
    public function index()
    {
        return view('admin.listings');
    }
}