<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        
        if ($query) {
            $projects = Project::where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->get();
        } else {
            $projects = Project::all();
        }

        return view('pages.search', compact('projects'));
    }
}