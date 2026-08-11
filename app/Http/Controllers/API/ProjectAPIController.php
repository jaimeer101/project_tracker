<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // 1. Search (Project Name or Client Name)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        // 2. Filter by Status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // 3. Filter by Priority
        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        // 4. Sorting
        $sortBy = $request->input('sort_by', 'id');
        $sortOrder = $request->input('sort_order', 'desc');

        $allowedSorts = ['id', 'project_name', 'client_name', 'status', 'priority', 'start_date', 'due_date'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, strtolower($sortOrder) === 'asc' ? 'asc' : 'desc');
        }

        // 5. Pagination
        $perPage = $request->input('per_page', 10);
        $projects = $query->paginate($perPage)->withQueryString();
        // dd($projects);
        return response()->json($projects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProjectRequest $request)
    {
        //
        $data = $request->validated();
        // dd($data);
        $project = Project::create([
            'project_name' => $data['name'],
            'client_name'  => $data['client'],
            'description'  => $data['description'] ?? null,
            'status'       => $data['status'],
            'priority'     => $data['priority'],
            'start_date'   => $data['startDate'],
            'due_date'     => $data['dueDate'],
        ]);

        // 3. Return JSON response with 201 status code
        return redirect()->route('projects.create')->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateProjectRequest $request, Project $id)
    {
        $data = $request->validated();

        $id->update([
            'project_name' => $data['name'],
            'client_name'  => $data['client'],
            'description'  => $data['description'] ?? null,
            'status'       => $data['status'],
            'priority'     => $data['priority'],
            'start_date'   => $data['startDate'],
            'due_date'     => $data['dueDate'],
        ]);
        return redirect()->route('projects.edit', ["project" => $id->id])->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $id)
    {
        $id->delete();

        return redirect()->back()->with('success', 'Project:' . $id->project_name . ' deleted successfully!');
    }
}
