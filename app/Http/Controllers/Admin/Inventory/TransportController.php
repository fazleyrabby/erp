<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\inventory\TransportInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransportController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:transport.view', ['only' => ['index', 'getTransports']]);
        $this->middleware('permission:transport.store', ['only' => ['store']]);
        $this->middleware('permission:transport.edit', ['only' => ['edit', 'udpate']]);
        $this->middleware('permission:transport.delete', ['only' => ['delete']]);
    }
    
    public function index()
    {
        return view('admin.inventory.transport.transport');
    }

    public function getTransports()
    {
        $transports = DB::table('tbl_transportinfo')
            ->where('tbl_transportinfo.deleted', 'No')
            ->orderBy('tbl_transportinfo.id', 'DESC')
            ->get();
        $output = array('data' => array());
        $i = 1;
        foreach ($transports as $transport) {
            $status = "";
            if ($transport->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;"></i></center>';
            }

            $button = '<td style="width: 12%;">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                                <li class="action liDropDown" onclick="editTransportInfo(' . $transport->id . ')"  ><a  class="btn" ><i class="fas fa-edit"></i> Edit </a></li>
                                </li>
                            </li>
                                <li class="action liDropDown"><a   class="btn"  onclick="confirmDelete(' . $transport->id . ')" ><i class="fas fa-trash-alt"></i> Delete </a></li>
                                </li> 
                                </ul>
                            </div>
                        </td>';

            $output['data'][] = array(
                $i++ . '<input type="hidden" name="id" id="id" value="' . $transport->id . '" />',
                $transport->transportName,
                $transport->address,
                'Contact Person: '.$transport->contactPerson.'<br>Contact No: '.$transport->contactNo,
                $status,
                $button
            );
        }
        return $output;
    }

    public function store(Request $request)
    {
        $request->validate([
            'transportName' => 'required|max:255',
            'address' => 'required',
            'contactNumber' => 'required|max:15',
        ]);
        $transport = new TransportInfo();
        $transport->transportName = $request->transportName;
        $transport->contactPerson = $request->contactPerson;
        $transport->contactNo = $request->contactNumber;
        $transport->email = $request->contactEmail;
        $transport->address = $request->address;
        $transport->remarks = $request->remarks;
        $transport->createdDate  = date('Y-m-d H:i:s');
        $transport->createdBy  = auth()->user()->id;
        $transport->save();
        return response()->json("Transport saved successfulluy!");
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
        $transport =  TransportInfo::find($request->id);
        $transport->transportName = $request->transportName;
        $transport->contactPerson = $request->contactPerson;
        $transport->contactNo = $request->contactNumber;
        $transport->email = $request->contactEmail;
        $transport->address = $request->address;
        $transport->remarks = $request->remarks;
        $transport->status = $request->status;
        $transport->lastUpdatedDate  = date('Y-m-d H:i:s');
        $transport->lastUpdatedBy  = auth()->user()->id;
        $transport->save();
        return response()->json("Transport udpated successfulluy!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $transport =  TransportInfo::find($request->id);
        $transport->deleted = 'Yes';
        $transport->deletedDate = date('Y-m-d H:i:s');
        $transport->deletedBy = auth()->user()->id;
        $transport->save();
        return response()->json("Transport Info Deleted!");
    }
}
