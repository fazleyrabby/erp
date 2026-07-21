<?php

namespace App\Http\Controllers\Admin\ProjectManagement;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Subtask;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display the Kanban board / List View of projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('status', 'Active')->where('deleted', 'No')->get();
        
        // Eager load subtasks for counts and progress bars
        $projects = Project::with(['user', 'subtasks'])->get();
        
        $statuses = ['Pending', 'processing', 'testing', 'completed'];
        $kanbanProjects = [];
        
        foreach ($statuses as $status) {
            $kanbanProjects[$status] = $projects->where('status', $status);
        }

        // Return the projects collection as a flat list as well for the List View table
        return view('admin.projects.index', compact('users', 'projects', 'kanbanProjects', 'statuses'));
    }

    /**
     * Store a newly created project with optional subtasks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'subtasks' => 'nullable|array',
            'subtasks.*' => 'nullable|string|max:255',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'status' => 'Pending',
            'priority' => $request->priority,
            'due_date' => $request->due_date,
        ]);

        // Save subtasks if present
        if ($request->has('subtasks')) {
            foreach ($request->subtasks as $title) {
                if (trim($title) !== '') {
                    $project->subtasks()->create([
                        'title' => trim($title),
                        'is_completed' => false,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Project created successfully.');
    }

    /**
     * Retrieve a specific project with its subtasks (for edit dialog).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:projects,id',
        ]);

        $project = Project::with('subtasks')->findOrFail($request->id);

        return response()->json($project);
    }

    /**
     * Update an existing project and sync its subtasks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'status' => 'required|in:Pending,processing,testing,completed',
            'subtasks' => 'nullable|array',
        ]);

        $project = Project::findOrFail($request->id);
        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'priority' => $request->priority,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        // Sync Subtasks
        $incomingSubtasks = $request->input('subtasks', []);
        $keepIds = [];

        foreach ($incomingSubtasks as $sub) {
            if (!empty($sub['id'])) {
                // Update existing subtask
                $subtask = Subtask::where('project_id', $project->id)->findOrFail($sub['id']);
                $subtask->update([
                    'title' => $sub['title'],
                    'is_completed' => isset($sub['is_completed']) ? (bool)$sub['is_completed'] : false,
                ]);
                $keepIds[] = $subtask->id;
            } else {
                // Create new subtask if title is not empty
                if (isset($sub['title']) && trim($sub['title']) !== '') {
                    $newSub = $project->subtasks()->create([
                        'title' => trim($sub['title']),
                        'is_completed' => isset($sub['is_completed']) ? (bool)$sub['is_completed'] : false,
                    ]);
                    $keepIds[] = $newSub->id;
                }
            }
        }

        // Delete subtasks that were removed in the UI
        $project->subtasks()->whereNotIn('id', $keepIds)->delete();

        return redirect()->back()->with('success', 'Project updated successfully.');
    }

    /**
     * Update the status of a project (via AJAX drag-and-drop).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:projects,id',
            'status' => 'required|in:Pending,processing,testing,completed',
        ]);

        $project = Project::findOrFail($request->id);
        $project->status = $request->status;
        $project->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.'
        ]);
    }

    /**
     * Delete a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:projects,id',
        ]);

        $project = Project::findOrFail($request->id);
        $project->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'Project deleted successfully.');
    }
}
