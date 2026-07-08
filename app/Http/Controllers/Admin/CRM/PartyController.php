<?php

namespace App\Http\Controllers\Admin\CRM;

use App\Http\Controllers\Controller;
use App\Models\Crm\Party;
use App\Models\inventory\PaymentVoucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PartyController extends Controller
{
    public function index(Request $request, $type)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $parties = Party::where('deleted', 'No')
            ->where(function ($query) use ($type) {
                $query->where('party_type', $type)
                    ->orWhere('party_type', 'Both');
            });

        if ($searchTerm) {
            $parties->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('contact', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%");
            });
        }

        $parties = $parties->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        return view('admin.party.view-party', compact('parties', 'type'));
    }

    public function getParty(Request $request)
    {
        $data = '';
        $partyType = $request->type;
        $parties = Party::where('deleted', 'No')
            ->where(function ($query) use ($partyType) {
                $query->where('party_type', $partyType)
                    ->orWhere('party_type', 'Both');
            })
            ->orderBy('id', 'DESC')->get();
        $output = ['data' => []];
        $i = 1;
        foreach ($parties as $party) {
            $status = '';
            if ($party->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$party->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$party->status.'"></i></center>';
            }

            $button = '<td style="width: 12%;">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                                    <li class="action" onclick="editParty('.$party->id.')"  ><a  class="btn" ><i class="fas fa-edit"></i> Edit </a></li>
                                    <li class="action"><a   class="btn" onclick="updateDue('.$party->id.')" ><i class="fas fa-edit"></i> Update Due </a></li>
                                    <li class="action"><a   class="btn" onclick="confirmDelete('.$party->id.')" ><i class="fas fa-trash-alt"></i> Delete </a></li>
                                </ul>
                            </div>
                        </td>';
            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$party->id.'" />',
                '<b>Name: </b>'.$party->name.'<br><b>Code:</b>#'.$party->code.'<br><b>Cr. Limit: </b>'.Session::get('companySettings')[0]['currency'].' '.$party->credit_limit.'<br><b>Method: </b>'.$party->customer_type,
                '<b>Address: </b>'.$party->address.'<br><b>District: </b>'.$party->district.'<br><b>Country: </b>'.$party->country_name,
                '<b>Phone: </b>'.$party->contact.'<br><b>Alt: </b>'.$party->alternate_contact.'<br><b>Email:</b>'.$party->email,
                $party->party_type,
                $status,
                $button,
            ];
        }

        return $output;
    }

    public function getParties(Request $request)
    {

        $partyType = $request->type;
        $parties = Party::where('deleted', 'No')
            ->where(function ($query) use ($partyType) {
                $query->where('party_type', $partyType)
                    ->orWhere('party_type', 'Both');
            })->orderBy('id', 'DESC')->get();

        return $parties;
    }

    public function getPartyData(Request $request)
    {

        $partyType = $request->party_type;
        $parties = Party::where(function ($query) use ($partyType) {
            $query->where('party_type', $partyType)
                ->orWhere('party_type', 'Both');
        })->where('deleted', 'No')->get();
        $data = "<option value='' selected>Select Party</option>";
        foreach ($parties as $party) {
            $data .= "<option value='".$party->id."'>".$party->name.'</option>';
        }

        return $data;
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'email' => 'required|email|max:255|unique:users|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'contact_person' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'contact' => 'required|min:11|max:14|regex:/^([0-9\+]*)$/',
            'country_name' => 'required',
            'alternate_contact' => 'nullable',
            'district' => 'required',
            'party_variety' => 'required',
            'credit_limit' => 'required|max:13|regex:/^\d+(\.\d{1,2})?$/',
            'address' => 'required|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'party_type' => 'required',
        ]);
        $maxCode = Party::where('party_type', $request->party_type)->orwhere('party_type', 'Both')->max('code');
        $maxCode++;
        $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
        $party = new Party;
        $party->name = $request->name;
        $party->country_name = $request->country_name;
        $party->email = $request->email;
        $party->district = $request->district;
        $party->customer_type = $request->party_variety;
        $party->contact_person = $request->contact_person;
        $party->code = $maxCode;
        $party->address = $request->address;
        $party->contact = $request->contact;
        $party->alternate_contact = $request->alternate_contact;
        $party->credit_limit = $request->credit_limit;
        $party->current_due = '0.00';
        $party->opening_due = '0.00';
        $party->party_type = $request->party_type;
        $party->created_by = auth()->user()->id;
        $party->created_date = date('Y-m-d H:i:s');
        $party->deleted = 'No';
        $party->status = 'Active';
        $party->save();

        return response()->json(['success' => $request->party_type.' saved successfully']);
    }

    public function edit(Request $request)
    {
        $party = Party::find($request->id);

        return $party;

    }

    public function update(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            // 'code' => 'required|max:255|unique:parties,code|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'address' => 'required|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'contact' => 'required|min:11|max:14|regex:/^([0-9\+]*)$/',
            'credit_limit' => 'required|max:13|regex:/^\d+(\.\d{1,2})?$/',
            'party_type' => 'required',
        ]);

        $party = Party::find($request->id);
        $party->name = $request->name;
        $party->country_name = $request->country_name;
        $party->email = $request->email;
        $party->district = $request->district;
        $party->customer_type = $request->party_variety;
        $party->contact_person = $request->contact_person;
        $party->address = $request->address;
        $party->contact = $request->contact;
        $party->alternate_contact = $request->alternate_contact;
        $party->credit_limit = $request->credit_limit;
        $party->party_type = $request->party_type;
        $party->status = $request->status;
        $party->last_updated_by = auth()->user()->id;
        $party->updated_date = date('Y-m-d H:i:s');
        $party->deleted = 'No';
        $party->save();

        return response()->json(['success' => $request->party_type.' updated successfully']);
    }

    public function updatePartyOpeningDue(Request $request)
    {
        $party = Party::find($request->partyId);
        $dueType = $request->dueType;
        $openingDue = floatval($request->openingDue) - floatval($party->opening_due); // Adjust with previous opening due if exists
        if ($party->party_type == 'Customer' || $party->party_type == 'Walkin_Customer') {
            if ($dueType == 'due') {
                $party->increment('current_due', $openingDue); // Decrement for due
                $party->opening_due = floatval($request->openingDue);
                $party->save();
                $maxCode = PaymentVoucher::max('voucherNo');
                $maxCode++;
                $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
                $paymentVoucher = new PaymentVoucher;
                $paymentVoucher->voucherNo = $maxCode;
                $paymentVoucher->party_id = $request->partyId;
                $paymentVoucher->amount = floatval($request->openingDue);
                $paymentVoucher->payment_method = 'Cash';
                $paymentVoucher->paymentDate = date('Y-m-d H:i:s');
                $paymentVoucher->type = 'Party Payable';
                $paymentVoucher->voucherType = 'PartySale';
                $paymentVoucher->remarks = 'Update opening due';
                $paymentVoucher->entryBy = auth()->user()->id;
                $paymentVoucher->save();
            } else {
                $party->decrement('current_due', $openingDue); // Decrement for due
                $party->opening_due = floatval(-$request->openingDue); // Opening due will be (-) for due
                $party->save();
                $maxCode = PaymentVoucher::max('voucherNo');
                $maxCode++;
                $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
                $paymentVoucher = new PaymentVoucher;
                $paymentVoucher->voucherNo = $maxCode;
                $paymentVoucher->party_id = $request->partyId;
                $paymentVoucher->amount = floatval($request->openingDue);
                $paymentVoucher->payment_method = 'Cash';
                $paymentVoucher->paymentDate = date('Y-m-d H:i:s');
                $paymentVoucher->type = 'Payment Received';
                $paymentVoucher->voucherType = 'PartySale';
                $paymentVoucher->remarks = 'Update opening due';
                $paymentVoucher->entryBy = auth()->user()->id;
                $paymentVoucher->save();
            }
        } else {
            if ($dueType == 'due') {
                $party->decrement('current_due', $openingDue); // Decrement for due
                $party->opening_due = floatval(-$request->openingDue); // Opening due will be (-) for due
                $party->save();
                $maxCode = PaymentVoucher::max('voucherNo');
                $maxCode++;
                $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
                $paymentVoucher = new PaymentVoucher;
                $paymentVoucher->voucherNo = $maxCode;
                $paymentVoucher->party_id = $request->partyId;
                $paymentVoucher->amount = floatval($request->openingDue);
                $paymentVoucher->payment_method = 'Cash';
                $paymentVoucher->paymentDate = date('Y-m-d H:i:s');
                $paymentVoucher->type = 'Payable';
                $paymentVoucher->voucherType = 'Local Purchase';
                $paymentVoucher->remarks = 'Update opening due';
                $paymentVoucher->entryBy = auth()->user()->id;
                $paymentVoucher->save();
            } else {
                $party->increment('current_due', $openingDue); // Decrement for due
                $party->opening_due = floatval($request->openingDue);
                $party->save();
                $maxCode = PaymentVoucher::max('voucherNo');
                $maxCode++;
                $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
                $paymentVoucher = new PaymentVoucher;
                $paymentVoucher->voucherNo = $maxCode;
                $paymentVoucher->party_id = $request->partyId;
                $paymentVoucher->amount = floatval($request->openingDue);
                $paymentVoucher->payment_method = 'Cash';
                $paymentVoucher->paymentDate = date('Y-m-d H:i:s');
                $paymentVoucher->type = 'Payment';
                $paymentVoucher->voucherType = 'Local Purchase';
                $paymentVoucher->remarks = 'Update opening due';
                $paymentVoucher->entryBy = auth()->user()->id;
                $paymentVoucher->save();
            }

        }
    }

    public function delete(Request $request)
    {
        $party = Party::find($request->id);
        $party->deleted = 'Yes';
        $party->status = 'Inactive';
        $party->name = $party->name.'-Deleted-'.$request->id;
        $party->deleted_by = auth()->user()->id;
        $party->deleted_date = date('Y-m-d H:i:s');
        $party->save();

        return response()->json(['success' => $party->party_type.' deleted successfully']);
    }
}
