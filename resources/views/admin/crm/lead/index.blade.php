@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Leads
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Leads List</h3>
            <div class="card-actions">
                <button type="button" class="btn btn-primary" onclick="create()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Lead
                </button>
                <a class="btn btn-outline-secondary" onclick="location.reload()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"/><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"/></svg>
                    Refresh
                </a>
            </div>
        </div>
        <div class="card-body">
            <x-filter-bar
                route="{{ route('leads.index') }}"
                searchPlaceholder="Search leads..."
                :sortOptions="['id' => 'ID', 'first_name' => 'Name', 'email' => 'Email', 'company' => 'Company']"
                :defaultSort="'id'"
                :defaultDirection="'DESC'"
            />
            <div class="table-responsive">
                <table class="table table-vcenter table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%">SL#</th>
                            <th width="15%">Name</th>
                            <th width="15%">Contact</th>
                            <th width="12%">Company</th>
                            <th width="10%">Source</th>
                            <th width="10%">Status</th>
                            <th width="10%">Value</th>
                            <th width="8%">Active</th>
                            <th width="8%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($leads as $i => $lead)
                        <tr>
                            <td class="text-center">{{ $leads->firstItem() + $i }}</td>
                            <td>
                                <b>{{ $lead->first_name }} {{ $lead->last_name }}</b><br>
                                <small>{{ $lead->designation }}</small>
                            </td>
                            <td>
                                <b>Email:</b> {{ $lead->email ?? '—' }}<br>
                                <b>Phone:</b> {{ $lead->phone ?? '—' }}
                            </td>
                            <td>{{ $lead->company ?? '—' }}</td>
                            <td><span class="badge bg-info">{{ $lead->source }}</span></td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'New' => 'bg-primary',
                                        'Contacted' => 'bg-warning',
                                        'Qualified' => 'bg-info',
                                        'Proposal' => 'bg-secondary',
                                        'Negotiation' => 'bg-orange',
                                        'Won' => 'bg-success',
                                        'Lost' => 'bg-danger',
                                    ];
                                    $class = $statusClasses[$lead->lead_status] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $class }}">{{ $lead->lead_status }}</span>
                            </td>
                            <td>{{ number_format($lead->potential_value, 2) }}</td>
                            <td class="text-center">
                                @if ($lead->status == 'Active')
                                <i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $lead->status }}"></i>
                                @else
                                <i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $lead->status }}"></i>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         <i class="fas fa-cog"></i>
                                     </button>
                                     <div class="dropdown-menu dropdown-menu-end">
                                         <a class="dropdown-item" href="#" onclick="editLead({{ $lead->id }})"><i class="fas fa-edit me-2"></i> Edit</a>
                                         <a class="dropdown-item" href="#" onclick="confirmDelete({{ $lead->id }})"><i class="fas fa-trash-alt me-2"></i> Delete</a>
                                     </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">No leads found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $leads->links() }}
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Lead</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="leadForm" method="POST" action="#">
                    <div class="modal-body">
                        <div class="row g-3">
                            @csrf
                            <input type="hidden" name="id">
                            <div class="form-group mb-3 col-md-6">
                                <label>First Name <span class="text-danger">*</span></label>
                                <input class="form-control input-sm" id="firstName" type="text" name="first_name" placeholder="First name">
                                <span class="text-danger" id="firstNameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Last Name</label>
                                <input class="form-control input-sm" id="lastName" type="text" name="last_name" placeholder="Last name">
                                <span class="text-danger" id="lastNameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Email</label>
                                <input class="form-control input-sm" id="email" type="email" name="email" placeholder="Email">
                                <span class="text-danger" id="emailError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Phone</label>
                                <input class="form-control input-sm" id="phone" type="text" name="phone" placeholder="Phone">
                                <span class="text-danger" id="phoneError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Company</label>
                                <input class="form-control input-sm" id="company" type="text" name="company" placeholder="Company">
                                <span class="text-danger" id="companyError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Designation</label>
                                <input class="form-control input-sm" id="designation" type="text" name="designation" placeholder="Designation">
                                <span class="text-danger" id="designationError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Source <span class="text-danger">*</span></label>
                                <select class="form-control input-sm" id="source" name="source">
                                    <option value="Website">Website</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Social Media">Social Media</option>
                                    <option value="Walk-in">Walk-in</option>
                                    <option value="Cold Call">Cold Call</option>
                                    <option value="Email">Email</option>
                                    <option value="Other">Other</option>
                                </select>
                                <span class="text-danger" id="sourceError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Lead Status <span class="text-danger">*</span></label>
                                <select class="form-control input-sm" id="leadStatus" name="lead_status">
                                    <option value="New">New</option>
                                    <option value="Contacted">Contacted</option>
                                    <option value="Qualified">Qualified</option>
                                    <option value="Proposal">Proposal</option>
                                    <option value="Negotiation">Negotiation</option>
                                    <option value="Won">Won</option>
                                    <option value="Lost">Lost</option>
                                </select>
                                <span class="text-danger" id="leadStatusError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Potential Value</label>
                                <input class="form-control input-sm" id="potentialValue" type="number" step="0.01" name="potential_value" placeholder="0.00">
                                <span class="text-danger" id="potentialValueError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Follow-up Date</label>
                                <input class="form-control input-sm" id="followUpDate" type="date" name="follow_up_date">
                                <span class="text-danger" id="followUpDateError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Assigned To</label>
                                <select class="form-control input-sm" id="assignedTo" name="assigned_to">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="assignedToError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Website</label>
                                <input class="form-control input-sm" id="website" type="text" name="website" placeholder="Website URL">
                                <span class="text-danger" id="websiteError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label>Address</label>
                                <textarea class="form-control input-sm" id="address" name="address" rows="2" placeholder="Address"></textarea>
                                <span class="text-danger" id="addressError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label>Notes</label>
                                <textarea class="form-control input-sm" id="notes" name="notes" rows="3" placeholder="Notes"></textarea>
                                <span class="text-danger" id="notesError"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btnSave"><i class="fa fa-save"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Lead</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editLeadForm" method="POST" action="#">
                    <div class="modal-body">
                        <div class="row g-3">
                            @csrf
                            <input type="hidden" name="id" id="editId">
                            <div class="form-group mb-3 col-md-6">
                                <label>First Name <span class="text-danger">*</span></label>
                                <input class="form-control input-sm" id="editFirstName" type="text" name="first_name" placeholder="First name">
                                <span class="text-danger" id="editFirstNameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Last Name</label>
                                <input class="form-control input-sm" id="editLastName" type="text" name="last_name" placeholder="Last name">
                                <span class="text-danger" id="editLastNameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Email</label>
                                <input class="form-control input-sm" id="editEmail" type="email" name="email" placeholder="Email">
                                <span class="text-danger" id="editEmailError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Phone</label>
                                <input class="form-control input-sm" id="editPhone" type="text" name="phone" placeholder="Phone">
                                <span class="text-danger" id="editPhoneError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Company</label>
                                <input class="form-control input-sm" id="editCompany" type="text" name="company" placeholder="Company">
                                <span class="text-danger" id="editCompanyError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Designation</label>
                                <input class="form-control input-sm" id="editDesignation" type="text" name="designation" placeholder="Designation">
                                <span class="text-danger" id="editDesignationError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Source <span class="text-danger">*</span></label>
                                <select class="form-control input-sm" id="editSource" name="source">
                                    <option value="Website">Website</option>
                                    <option value="Referral">Referral</option>
                                    <option value="Social Media">Social Media</option>
                                    <option value="Walk-in">Walk-in</option>
                                    <option value="Cold Call">Cold Call</option>
                                    <option value="Email">Email</option>
                                    <option value="Other">Other</option>
                                </select>
                                <span class="text-danger" id="editSourceError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Lead Status <span class="text-danger">*</span></label>
                                <select class="form-control input-sm" id="editLeadStatus" name="lead_status">
                                    <option value="New">New</option>
                                    <option value="Contacted">Contacted</option>
                                    <option value="Qualified">Qualified</option>
                                    <option value="Proposal">Proposal</option>
                                    <option value="Negotiation">Negotiation</option>
                                    <option value="Won">Won</option>
                                    <option value="Lost">Lost</option>
                                </select>
                                <span class="text-danger" id="editLeadStatusError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Potential Value</label>
                                <input class="form-control input-sm" id="editPotentialValue" type="number" step="0.01" name="potential_value" placeholder="0.00">
                                <span class="text-danger" id="editPotentialValueError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Follow-up Date</label>
                                <input class="form-control input-sm" id="editFollowUpDate" type="date" name="follow_up_date">
                                <span class="text-danger" id="editFollowUpDateError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Assigned To</label>
                                <select class="form-control input-sm" id="editAssignedTo" name="assigned_to">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="editAssignedToError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Status</label>
                                <select class="form-control input-sm" id="editStatus" name="status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <span class="text-danger" id="editStatusError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Website</label>
                                <input class="form-control input-sm" id="editWebsite" type="text" name="website" placeholder="Website URL">
                                <span class="text-danger" id="editWebsiteError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label>Address</label>
                                <textarea class="form-control input-sm" id="editAddress" name="address" rows="2" placeholder="Address"></textarea>
                                <span class="text-danger" id="editAddressError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label>Notes</label>
                                <textarea class="form-control input-sm" id="editNotes" name="notes" rows="3" placeholder="Notes"></textarea>
                                <span class="text-danger" id="editNotesError"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btnSave"><i class="fa fa-save"></i> Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        function resetMessages() {
            $('#firstNameError').text('');
            $('#lastNameError').text('');
            $('#emailError').text('');
            $('#phoneError').text('');
            $('#companyError').text('');
            $('#designationError').text('');
            $('#sourceError').text('');
            $('#leadStatusError').text('');
            $('#potentialValueError').text('');
            $('#followUpDateError').text('');
            $('#assignedToError').text('');
            $('#websiteError').text('');
            $('#addressError').text('');
            $('#notesError').text('');
        }

        function resetEditMessages() {
            $('#editFirstNameError').text('');
            $('#editLastNameError').text('');
            $('#editEmailError').text('');
            $('#editPhoneError').text('');
            $('#editCompanyError').text('');
            $('#editDesignationError').text('');
            $('#editSourceError').text('');
            $('#editLeadStatusError').text('');
            $('#editPotentialValueError').text('');
            $('#editFollowUpDateError').text('');
            $('#editAssignedToError').text('');
            $('#editWebsiteError').text('');
            $('#editAddressError').text('');
            $('#editNotesError').text('');
            $('#editStatusError').text('');
        }

        function create() {
            resetMessages();
            $('#leadForm')[0].reset();
            $('#modal').modal('show');
        }

        $("#leadForm").submit(function(e) {
            e.preventDefault();
            var fd = new FormData(this);
            $.ajax({
                url: "{{ route('leads.store') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(result) {
                    $("#modal").modal('hide');
                    Swal.fire("Done!", result.success, "success");
                    location.reload();
                },
                error: function(response) {
                    $('#firstNameError').text(response.responseJSON.errors.first_name);
                    $('#lastNameError').text(response.responseJSON.errors.last_name);
                    $('#emailError').text(response.responseJSON.errors.email);
                    $('#phoneError').text(response.responseJSON.errors.phone);
                    $('#companyError').text(response.responseJSON.errors.company);
                    $('#designationError').text(response.responseJSON.errors.designation);
                    $('#sourceError').text(response.responseJSON.errors.source);
                    $('#leadStatusError').text(response.responseJSON.errors.lead_status);
                    $('#potentialValueError').text(response.responseJSON.errors.potential_value);
                    $('#followUpDateError').text(response.responseJSON.errors.follow_up_date);
                    $('#assignedToError').text(response.responseJSON.errors.assigned_to);
                    $('#websiteError').text(response.responseJSON.errors.website);
                    $('#addressError').text(response.responseJSON.errors.address);
                    $('#notesError').text(response.responseJSON.errors.notes);
                },
                complete: function() {
                    $("#loading").hide();
                }
            })
        });

        function editLead(id) {
            resetEditMessages();
            $.ajax({
                url: "{{ route('leads.edit') }}",
                method: "GET",
                data: { "id": id },
                datatype: "json",
                success: function(result) {
                    $("#editModal").modal('show');
                    $("#editId").val(result.id);
                    $("#editFirstName").val(result.first_name);
                    $("#editLastName").val(result.last_name);
                    $("#editEmail").val(result.email);
                    $("#editPhone").val(result.phone);
                    $("#editCompany").val(result.company);
                    $("#editDesignation").val(result.designation);
                    $("#editSource").val(result.source);
                    $("#editLeadStatus").val(result.lead_status);
                    $("#editPotentialValue").val(result.potential_value);
                    $("#editFollowUpDate").val(result.follow_up_date);
                    $("#editAssignedTo").val(result.assigned_to);
                    $("#editStatus").val(result.status);
                    $("#editWebsite").val(result.website);
                    $("#editAddress").val(result.address);
                    $("#editNotes").val(result.notes);
                }
            });
        }

        $("#editLeadForm").submit(function(e) {
            e.preventDefault();
            var fd = new FormData(this);
            $.ajax({
                url: "{{ route('leads.update') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(result) {
                    $("#editModal").modal('hide');
                    location.reload();
                    Swal.fire("Updated!", result.success, "success");
                },
                error: function(response) {
                    $('#editFirstNameError').text(response.responseJSON.errors.first_name);
                    $('#editLastNameError').text(response.responseJSON.errors.last_name);
                    $('#editEmailError').text(response.responseJSON.errors.email);
                    $('#editPhoneError').text(response.responseJSON.errors.phone);
                    $('#editCompanyError').text(response.responseJSON.errors.company);
                    $('#editDesignationError').text(response.responseJSON.errors.designation);
                    $('#editSourceError').text(response.responseJSON.errors.source);
                    $('#editLeadStatusError').text(response.responseJSON.errors.lead_status);
                    $('#editPotentialValueError').text(response.responseJSON.errors.potential_value);
                    $('#editFollowUpDateError').text(response.responseJSON.errors.follow_up_date);
                    $('#editAssignedToError').text(response.responseJSON.errors.assigned_to);
                    $('#editStatusError').text(response.responseJSON.errors.status);
                    $('#editWebsiteError').text(response.responseJSON.errors.website);
                    $('#editAddressError').text(response.responseJSON.errors.address);
                    $('#editNotesError').text(response.responseJSON.errors.notes);
                },
                complete: function() {
                    $("#loading").hide();
                }
            })
        });

        function confirmDelete(id) {
            confirmDeleteSwal({
                url      : "{{ route('leads.delete') }}",
                id       : id,
                itemName : 'Lead',
            });
        }

        Mousetrap.bind('ctrl+shift+n', function(e) {
            e.preventDefault();
            if ($('#modal.in, #modal.show').length) {
            } else {
                create();
            }
        });

        function reloadDt() {
            if ($('#modal.in, #modal.show').length) {
            } else if ($('#editModal.in, #editModal.show').length) {
            } else {
                location.reload();
            }
        }
        Mousetrap.bind('ctrl+shift+r', function(e) {
            e.preventDefault();
            reloadDt();
        });
        Mousetrap.bind('ctrl+shift+s', function(e) {
            e.preventDefault();
            if ($('#modal.in, #modal.show').length) {
                $("#leadForm").trigger('submit');
            }
        });
        Mousetrap.bind('ctrl+shift+u', function(e) {
            e.preventDefault();
            if ($('#editModal.in, #editModal.show').length) {
                $("#editLeadForm").trigger('submit');
            }
        });
        Mousetrap.bind('esc', function(e) {
            e.preventDefault();
            if ($('#editModal.in, #editModal.show').length) {
                $("#editModal").modal('hide');
            } else if ($('#modal.in, #modal.show').length) {
                $('#modal').modal('hide');
            }
        });
    </script>
@endsection
