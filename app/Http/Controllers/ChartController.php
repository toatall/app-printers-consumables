<?php

namespace App\Http\Controllers;

use App\Models\Chart;
use Inertia\Inertia;

class ChartController extends Controller
{
    public function index()
    {
        return Inertia::render('Chart/Index', [
            'data' => Chart::get(),
        ]);
    }
}
