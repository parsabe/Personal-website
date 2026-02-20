<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

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
            // Return an empty collection instead of pulling all projects
            $projects = collect();
        }

        return view('pages.search', compact('projects'));
    }
}