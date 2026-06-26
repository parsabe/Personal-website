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

            // Dynamic fallback for Vectra items to guarantee search indexing
            $vectraItems = [
                [
                    'title' => 'Vectra',
                    'description' => 'An end-to-end spatial computing framework engineered to generate, extract, and simulate high-fidelity 3D objects directly from simple visual and textual data.',
                    'image' => 'images/vectra.png',
                    'route_name' => 'projects.vectra',
                ],
                [
                    'title' => 'Vectra: The Quarantine Matrix, Constraining Neural Hallucinations in 3D Gaussian Environments',
                    'description' => 'As spatial computing and generative artificial intelligence converge, the necessity for robust, secure, and highly optimized integration architectures becomes strictly paramount. The Vectra Spatial Computing Protocol bridges the gap between high-fidelity digital twins and localized generative AI pipelines.',
                    'image' => 'images/vectra.png',
                    'route_name' => 'publications.vectra_paper',
                ]
            ];

            foreach ($vectraItems as $item) {
                if (stripos($item['title'], $query) !== false || stripos($item['description'], $query) !== false) {
                    if (!$projects->contains('route_name', $item['route_name'])) {
                        $mockItem = new Project();
                        $mockItem->title = $item['title'];
                        $mockItem->description = $item['description'];
                        $mockItem->image = $item['image'];
                        $mockItem->route_name = $item['route_name'];
                        
                        $projects->push($mockItem);
                    }
                }
            }
        } else {
            // Return an empty collection instead of pulling all projects
            $projects = collect();
        }

        return view('pages.search', compact('projects'));
    }
}