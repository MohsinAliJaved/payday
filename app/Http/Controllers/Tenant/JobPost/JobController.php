<?php

namespace App\Http\Controllers\Tenant\JobPost;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index()
    {
        return view('tenant.job_post.index');
    }
}
