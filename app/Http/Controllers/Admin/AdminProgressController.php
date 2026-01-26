<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class AdminProgressController extends Controller
{
    public function index()
    {
        return view('admin.progress.index', [
            'students' => User::where('role','student')->get()
        ]);
    }
}
