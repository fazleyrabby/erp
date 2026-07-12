<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\inventory\TransportInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:transport.view', ['only' => ['index']]);
        $this->middleware('permission:transport.store', ['only' => ['store']]);
        $this->middleware('permission:transport.edit', ['only' => ['edit', 'udpate']]);
        $this->middleware('permission:transport.delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'tbl_transportinfo.id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = DB::table('tbl_transportinfo')
            ->where('tbl_transportinfo.deleted', 'No');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('tbl_transportinfo.transportName', 'like', "%{$searchTerm}%")
                    ->orWhere('tbl_transportinfo.address', 'like', "%{$searchTerm}%")
                    ->orWhere('tbl_transportinfo.contactNo', 'like', "%{$searchTerm}%")
                    ->orWhere('tbl_transportinfo.contactPerson', 'like', "%{$searchTerm}%");
            });
        }

        $transports = $query->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        return view('admin.inventory.transport.transport', compact('transports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transportName' => 'required|max:255',
            'address' => 'required',
            'contactNumber' => 'required|max:15',
        ]);
        $transport = new TransportInfo;
        $transport->transportName = $request->transportName;
        $transport->contactPerson = $request->contactPerson;
        $transport->contactNo = $request->contactNumber;
        $transport->email = $request->contactEmail;
        $transport->address = $request->address;
        $transport->remarks = $request->remarks;
        $transport->createdDate = date('Y-m-d H:i:s');
        $transport->createdBy = auth()->user()->id;
        $transport->save();

        return response()->json('Transport saved successfulluy!');
    }

    public function edit(Request $request)
    {
        $transportInfo = TransportInfo::find($request->id);

        return response()->json($transportInfo);
    }

    public function udpate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'transportName' => 'required|max:255',
            'address' => 'required',
            'contactNumber' => 'required|max:15',
            'status' => 'required',
        ]);
        $transport = TransportInfo::find($request->id);
        $transport->transportName = $request->transportName;
        $transport->contactPerson = $request->contactPerson;
        $transport->contactNo = $request->contactNumber;
        $transport->email = $request->contactEmail;
        $transport->address = $request->address;
        $transport->remarks = $request->remarks;
        $transport->status = $request->status;
        $transport->lastUpdatedDate = date('Y-m-d H:i:s');
        $transport->lastUpdatedBy = auth()->user()->id;
        $transport->save();

        return response()->json('Transport udpated successfulluy!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete(Request $request)
    {
        $transport = TransportInfo::find($request->id);
        $transport->deleted = 'Yes';
        $transport->deletedDate = date('Y-m-d H:i:s');
        $transport->deletedBy = auth()->user()->id;
        $transport->save();

        return response()->json('Transport Info Deleted!');
    }
}
