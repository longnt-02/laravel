<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index() {
        // get category
        return view('admin.category.list');
    }

    public function delete() {
        
    }
}
