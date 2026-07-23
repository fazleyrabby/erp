<?php

namespace App\Http\Controllers\Admin\CRM;

use App\Http\Controllers\Controller;
use App\Models\Crm\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $leads = Lead::where('deleted', 'No');

        if ($searchTerm) {
            $leads->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                    ->orWhere('last_name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('phone', 'like', "%{$searchTerm}%")
                    ->orWhere('company', 'like', "%{$searchTerm}%");
            });
        }

        $leads = $leads->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        $users = User::where('deleted', 'No')->get();

        return view('admin.crm.lead.index', compact('leads', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'nullable|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:20',
            'company' => 'nullable|max:255',
            'designation' => 'nullable|max:255',
            'source' => 'required',
            'lead_status' => 'required',
            'potential_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable',
            'address' => 'nullable',
            'website' => 'nullable|max:255',
            'social_link' => 'nullable|max:255',
            'follow_up_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $lead = new Lead;
        $lead->first_name = $request->first_name;
        $lead->last_name = $request->last_name;
        $lead->email = $request->email;
        $lead->phone = $request->phone;
        $lead->company = $request->company;
        $lead->designation = $request->designation;
        $lead->source = $request->source;
        $lead->lead_status = $request->lead_status;
        $lead->potential_value = $request->potential_value ?? 0;
        $lead->notes = $request->notes;
        $lead->address = $request->address;
        $lead->website = $request->website;
        $lead->social_link = $request->social_link;
        $lead->follow_up_date = $request->follow_up_date;
        $lead->assigned_to = $request->assigned_to;
        $lead->created_by = auth()->user()->id;
        $lead->created_date = date('Y-m-d H:i:s');
        $lead->deleted = 'No';
        $lead->status = 'Active';
        $lead->save();

        return response()->json(['success' => 'Lead created successfully']);
    }

    public function edit(Request $request)
    {
        $lead = Lead::find($request->id);
        return $lead;
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'nullable|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:20',
            'company' => 'nullable|max:255',
            'designation' => 'nullable|max:255',
            'source' => 'required',
            'lead_status' => 'required',
            'potential_value' => 'nullable|numeric|min:0',
            'notes' => 'nullable',
            'address' => 'nullable',
            'website' => 'nullable|max:255',
            'social_link' => 'nullable|max:255',
            'follow_up_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $lead = Lead::find($request->id);
        $lead->first_name = $request->first_name;
        $lead->last_name = $request->last_name;
        $lead->email = $request->email;
        $lead->phone = $request->phone;
        $lead->company = $request->company;
        $lead->designation = $request->designation;
        $lead->source = $request->source;
        $lead->lead_status = $request->lead_status;
        $lead->potential_value = $request->potential_value ?? 0;
        $lead->notes = $request->notes;
        $lead->address = $request->address;
        $lead->website = $request->website;
        $lead->social_link = $request->social_link;
        $lead->follow_up_date = $request->follow_up_date;
        $lead->assigned_to = $request->assigned_to;
        $lead->status = $request->status ?? 'Active';
        $lead->updated_by = auth()->user()->id;
        $lead->updated_date = date('Y-m-d H:i:s');
        $lead->save();

        return response()->json(['success' => 'Lead updated successfully']);
    }

    public function delete(Request $request)
    {
        $lead = Lead::find($request->id);
        $lead->deleted = 'Yes';
        $lead->status = 'Inactive';
        $lead->deleted_by = auth()->user()->id;
        $lead->deleted_date = date('Y-m-d H:i:s');
        $lead->save();

        return response()->json(['success' => 'Lead deleted successfully']);
    }
}
