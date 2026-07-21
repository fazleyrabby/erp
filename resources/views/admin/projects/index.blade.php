@extends('admin.master')

@section('title')
    {{ Session::get('companySettings')[0]['name'] ?? 'ERP' }} - Project Management
@endsection

@section('content')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<style>
    .kanban-board {
        display: flex;
        gap: 1.25rem;
        overflow-x: auto;
        padding-bottom: 1.5rem;
        align-items: flex-start;
    }

    .kanban-column {
        flex: 1;
        min-width: 280px;
        max-width: 320px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    /* Distinct column accents */
    .column-Pending { border-top: 4px solid #6c757d; }
    .column-processing { border-top: 4px solid #ff9f43; }
    .column-testing { border-top: 4px solid #7460ee; }
    .column-completed { border-top: 4px solid #28c76f; }

    .kanban-column-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .kanban-column-title {
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #495057;
        margin: 0;
    }

    .kanban-column-count {
        background-color: #dee2e6;
        color: #495057;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 0.15rem 0.5rem;
        border-radius: 20px;
    }

    .kanban-column-cards {
        min-height: 450px;
        transition: background-color 0.2s ease;
    }

    .kanban-card {
        background: #ffffff;
        border-radius: 6px;
        padding: 0.85rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        cursor: grab;
        border: 1px solid #e9ecef;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    }

    .kanban-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        border-color: #ced4da;
    }

    .kanban-card:active {
        cursor: grabbing;
    }

    /* Priority accents */
    .priority-high { border-left: 4px solid #dc3545; }
    .priority-medium { border-left: 4px solid #ff9f43; }
    .priority-low { border-left: 4px solid #28c76f; }

    .kanban-placeholder {
        border: 2px dashed #ced4da;
        background-color: #f8f9fa;
        border-radius: 6px;
        margin-bottom: 0.75rem;
        height: 80px;
    }

    .card-footer-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.75rem;
        padding-top: 0.5rem;
        border-top: 1px solid #f1f3f5;
        font-size: 0.75rem;
        color: #6c757d;
    }

    .priority-badge {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
    }
    
    .badge-high { background-color: #f8d7da; color: #721c24; }
    .badge-medium { background-color: #fff3cd; color: #856404; }
    .badge-low { background-color: #d4edda; color: #155724; }

    .badge-status {
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .subtask-row {
        background-color: #f8f9fa;
        padding: 0.25rem;
        border-radius: 4px;
    }
</style>

<div class="row mb-3">
    <div class="col-md-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="page-title">Project Management</h2>
            <p class="text-muted mb-0">Track projects, monitor subtask progress, and switch views seamlessly.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- View toggler tab pills -->
            <ul class="nav nav-pills bg-white p-1 border rounded" id="project-view-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-1 px-3" id="kanban-tab" data-bs-toggle="pill" data-bs-target="#view-kanban" type="button" role="tab" aria-controls="view-kanban" aria-selected="true">
                        <i class="fa fa-th-large me-1"></i> Kanban
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-1 px-3" id="list-tab" data-bs-toggle="pill" data-bs-target="#view-list" type="button" role="tab" aria-controls="view-list" aria-selected="false">
                        <i class="fa fa-list me-1"></i> List
                    </button>
                </li>
            </ul>

            <button class="btn btn-primary" onclick="openCreateModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Project
            </button>
        </div>
    </div>
</div>

<div class="tab-content" id="projectViewContent">
    <!-- Kanban View Tab -->
    <div class="tab-pane fade show active" id="view-kanban" role="tabpanel" aria-labelledby="kanban-tab">
        <div class="kanban-board">
            @foreach ($statuses as $status)
                @php
                    $projs = $kanbanProjects[$status] ?? collect();
                    $displayName = $status === 'processing' ? 'Processing' : ($status === 'testing' ? 'Testing' : ($status === 'completed' ? 'Completed' : $status));
                @endphp
                <div class="kanban-column column-{{ $status }}" data-status="{{ $status }}">
                    <div class="kanban-column-header">
                        <h3 class="kanban-column-title">{{ $displayName }}</h3>
                        <span class="kanban-column-count">{{ $projs->count() }}</span>
                    </div>
                    
                    <div class="kanban-column-cards" id="column-cards-{{ $status }}">
                        @foreach ($projs as $project)
                            <div class="kanban-card priority-{{ $project->priority }}" data-id="{{ $project->id }}">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <strong class="text-dark" style="font-size: 0.88rem;">{{ $project->name }}</strong>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link p-0 text-muted" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)" onclick="openEditModal({{ $project->id }})">
                                                    <i class="fa fa-edit me-1"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteProject({{ $project->id }})">
                                                    <i class="fa fa-trash-alt me-1"></i> Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                @if($project->description)
                                    <p class="text-muted mb-2" style="font-size: 0.8rem; line-height: 1.3;">
                                        {{ Str::limit($project->description, 80) }}
                                    </p>
                                @endif

                                <!-- Subtask Progress Bar on Kanban Card -->
                                @if($project->subtasks->count() > 0)
                                    @php
                                        $totalSubs = $project->subtasks->count();
                                        $completedSubs = $project->subtasks->where('is_completed', true)->count();
                                        $percent = round(($completedSubs / $totalSubs) * 100);
                                    @endphp
                                    <div class="mt-2 mb-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.72rem;">
                                            <span class="text-muted"><i class="fa fa-tasks me-1"></i> {{ $completedSubs }}/{{ $totalSubs }} subtasks</span>
                                            <span class="font-weight-bold text-dark">{{ $percent }}%</span>
                                        </div>
                                        <div class="progress" style="height: 4px; border-radius: 4px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                @endif

                                <div class="card-footer-info">
                                    <div>
                                        <span class="priority-badge badge-{{ $project->priority }}">{{ $project->priority }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($project->due_date)
                                            <span title="Due Date" style="font-size: 0.72rem;">
                                                <i class="fa fa-calendar-alt me-1 text-muted"></i>{{ \Carbon\Carbon::parse($project->due_date)->format('M d') }}
                                            </span>
                                        @endif
                                        @if($project->user)
                                            <span class="avatar avatar-xs" title="Assignee: {{ $project->user->name }}" style="font-size: 0.6rem; font-weight: bold; width: 1.3rem; height: 1.3rem; background-color: #7460ee; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                {{ strtoupper(substr($project->user->name, 0, 2)) }}
                                            </span>
                                        @else
                                            <span class="text-muted" title="Unassigned"><i class="fa fa-user-slash"></i></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- List View Tab -->
    <div class="tab-pane fade" id="view-list" role="tabpanel" aria-labelledby="list-tab">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter table-bordered table-hover mb-0">
                        <thead>
                            <tr class="bg-light">
                                <th width="6%">SL#</th>
                                <th>Project Name</th>
                                <th width="15%">Assignee</th>
                                <th width="12%">Priority</th>
                                <th width="12%">Status</th>
                                <th width="20%">Subtasks Progress</th>
                                <th width="15%">Due Date</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                                <tr>
                                    <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $project->name }}</div>
                                        @if($project->description)
                                            <div class="text-muted small" style="font-size: 0.75rem;">{{ Str::limit($project->description, 100) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($project->user)
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-xs me-2" style="font-size: 0.6rem; font-weight: bold; width: 1.3rem; height: 1.3rem; background-color: #7460ee; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    {{ strtoupper(substr($project->user->name, 0, 2)) }}
                                                </span>
                                                <span class="small font-weight-semibold">{{ $project->user->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted small"><i class="fa fa-user-slash me-1"></i> Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="priority-badge badge-{{ $project->priority }}">{{ $project->priority }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = $project->status === 'completed' ? 'success' : ($project->status === 'testing' ? 'primary' : ($project->status === 'processing' ? 'warning' : 'secondary'));
                                            $dispStatus = $project->status === 'processing' ? 'Processing' : ($project->status === 'testing' ? 'Testing' : ($project->status === 'completed' ? 'Completed' : $project->status));
                                        @endphp
                                        <span class="badge badge-status bg-{{ $badgeClass }} text-white">{{ $dispStatus }}</span>
                                    </td>
                                    <td>
                                        @if($project->subtasks->count() > 0)
                                            @php
                                                $totalSubs = $project->subtasks->count();
                                                $completedSubs = $project->subtasks->where('is_completed', true)->count();
                                                $percent = round(($completedSubs / $totalSubs) * 100);
                                            @endphp
                                            <div>
                                                <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 0.75rem;">
                                                    <span class="text-muted font-weight-semibold">{{ $completedSubs }}/{{ $totalSubs }} completed</span>
                                                    <span class="font-weight-bold text-dark">{{ $percent }}%</span>
                                                </div>
                                                <div class="progress" style="height: 5px; border-radius: 4px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">No subtasks</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($project->due_date)
                                            <span class="small text-dark font-weight-medium">
                                                <i class="fa fa-calendar-alt me-1 text-muted"></i>{{ \Carbon\Carbon::parse($project->due_date)->format('M d, Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-secondary py-1" onclick="openEditModal({{ $project->id }})" title="Edit Project">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger py-1" onclick="deleteProject({{ $project->id }})" title="Delete Project">
                                                <i class="fa fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">No projects found. Create one to get started!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Project Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Create New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required font-weight-semibold">Project Name</label>
                        <input type="text" class="form-control" name="name" required placeholder="Enter project name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Enter project details..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-semibold">Assignee</label>
                            <select class="form-control" name="user_id">
                                <option value="">Select Assignee</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required font-weight-semibold">Priority</label>
                            <select class="form-control" name="priority" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold">Due Date</label>
                        <input type="date" class="form-control" name="due_date">
                    </div>

                    <!-- Subtask Builder Area -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0 font-weight-semibold">Subtasks Checklist</label>
                            <button type="button" class="btn btn-sm btn-outline-primary font-weight-semibold" id="btn-add-create-subtask">
                                <i class="fa fa-plus me-1"></i> Add Item
                            </button>
                        </div>
                        <div id="create-subtask-container">
                            <!-- Appended input fields go here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Edit Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('projects.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="edit-project-id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required font-weight-semibold">Project Name</label>
                        <input type="text" class="form-control" name="name" id="edit-project-name" required placeholder="Enter project name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-semibold">Description</label>
                        <textarea class="form-control" name="description" id="edit-project-description" rows="3" placeholder="Enter project details..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-semibold">Assignee</label>
                            <select class="form-control" name="user_id" id="edit-project-user-id">
                                <option value="">Select Assignee</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required font-weight-semibold">Priority</label>
                            <select class="form-control" name="priority" id="edit-project-priority" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-semibold">Due Date</label>
                            <input type="date" class="form-control" name="due_date" id="edit-project-due-date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required font-weight-semibold">Status</label>
                            <select class="form-control" name="status" id="edit-project-status" required>
                                <option value="Pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="testing">Testing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Dynamic Subtask Sync List -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0 font-weight-semibold">Subtasks Checklist</label>
                            <button type="button" class="btn btn-sm btn-outline-primary font-weight-semibold" id="btn-add-edit-subtask">
                                <i class="fa fa-plus me-1"></i> Add Item
                            </button>
                        </div>
                        <div id="edit-subtask-container">
                            <!-- Loaded from DB / dynamically appended fields -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize jQuery UI sortable
        $(".kanban-column-cards").sortable({
            connectWith: ".kanban-column-cards",
            placeholder: "kanban-placeholder",
            cursor: "grabbing",
            update: function(event, ui) {
                // Ensure request triggers exactly once on drop (only inside the receiver list)
                if (ui.sender) {
                    var cardId = ui.item.data('id');
                    var newStatus = $(this).closest('.kanban-column').data('status');
                    
                    $.ajax({
                        url: "{{ route('projects.update-status') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: cardId,
                            status: newStatus
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                refreshColumnCounts();
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to update project status.');
                            $(".kanban-column-cards").sortable("cancel");
                        }
                    });
                }
            }
        });

        // Add subtask input field in Create Modal
        $('#btn-add-create-subtask').click(function() {
            var html = `
                <div class="input-group mb-2 subtask-row">
                    <span class="input-group-text"><i class="fa fa-minus text-muted" style="font-size: 0.75rem;"></i></span>
                    <input type="text" class="form-control" name="subtasks[]" placeholder="Enter subtask title..." required>
                    <button type="button" class="btn btn-outline-danger btn-remove-subtask"><i class="fa fa-times"></i></button>
                </div>
            `;
            $('#create-subtask-container').append(html);
        });

        // Add subtask input field in Edit Modal
        $('#btn-add-edit-subtask').click(function() {
            var index = $('#editProjectModal').data('subtask-index') || 0;
            appendEditSubtaskRow(null, '', false, index);
            $('#editProjectModal').data('subtask-index', index + 1);
        });

        // Remove dynamic subtask row
        $(document).on('click', '.btn-remove-subtask', function() {
            $(this).closest('.subtask-row').remove();
        });
    });

    function openCreateModal() {
        $('#create-subtask-container').empty();
        $('#createProjectModal').modal('show');
    }

    function openEditModal(projectId) {
        $('#edit-subtask-container').empty();
        
        $.ajax({
            url: "{{ route('projects.show') }}",
            method: "GET",
            data: { id: projectId },
            success: function(project) {
                $('#edit-project-id').val(project.id);
                $('#edit-project-name').val(project.name);
                $('#edit-project-description').val(project.description);
                $('#edit-project-user-id').val(project.user_id || '');
                $('#edit-project-priority').val(project.priority);
                $('#edit-project-due-date').val(project.due_date);
                $('#edit-project-status').val(project.status);
                
                var subtaskCount = 0;
                if (project.subtasks && project.subtasks.length > 0) {
                    project.subtasks.forEach(function(sub) {
                        appendEditSubtaskRow(sub.id, sub.title, sub.is_completed, subtaskCount);
                        subtaskCount++;
                    });
                }
                
                // Track current count to avoid duplicate HTML indices
                $('#editProjectModal').data('subtask-index', subtaskCount);
                $('#editProjectModal').modal('show');
            },
            error: function() {
                toastr.error('Failed to load project details.');
            }
        });
    }

    function appendEditSubtaskRow(id, title, isCompleted, index) {
        var checked = isCompleted ? 'checked' : '';
        var hiddenId = id ? `<input type="hidden" name="subtasks[${index}][id]" value="${id}">` : '';
        
        var row = `
            <div class="input-group mb-2 subtask-row align-items-center">
                <span class="input-group-text py-2">
                    <input type="hidden" name="subtasks[${index}][is_completed]" value="0">
                    <input type="checkbox" class="form-check-input m-0" name="subtasks[${index}][is_completed]" value="1" ${checked}>
                </span>
                ${hiddenId}
                <input type="text" class="form-control" name="subtasks[${index}][title]" value="${title}" placeholder="Enter subtask title..." required>
                <button type="button" class="btn btn-outline-danger btn-remove-subtask"><i class="fa fa-times"></i></button>
            </div>
        `;
        $('#edit-subtask-container').append(row);
    }

    function deleteProject(id) {
        confirmDeleteSwal({
            url: "{{ route('projects.destroy') }}",
            id: id,
            itemName: 'Project',
            onSuccess: function(res) {
                Swal.fire({
                    title: 'Deleted!',
                    text: res.message || 'Project has been deleted.',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                }).then(function() {
                    location.reload();
                });
            }
        });
    }

    function refreshColumnCounts() {
        $('.kanban-column').each(function() {
            var count = $(this).find('.kanban-card').length;
            $(this).find('.kanban-column-count').text(count);
        });
    }
</script>
@endsection
