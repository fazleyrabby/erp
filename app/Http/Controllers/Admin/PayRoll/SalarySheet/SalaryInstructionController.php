<?php

namespace App\Http\Controllers\Admin\PayRoll\SalarySheet;

use App\Http\Controllers\Controller;
use App\Models\payroll\Email;
use App\Models\payroll\FinalSalarySheet;
use App\Models\payroll\SalaryInstruction;
use App\Models\payroll\SalarySheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class SalaryInstructionController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'salary_instructions.id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = DB::table('salary_instructions')
            ->join('salary_sheets', 'salary_instructions.sheet_id', '=', 'salary_sheets.id')
            ->select('salary_instructions.*', 'salary_sheets.sheet_name')
            ->where('salary_instructions.deleted', '=', 'No');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('salary_sheets.sheet_name', 'like', "%{$searchTerm}%")
                    ->orWhere('salary_instructions.bank_name', 'like', "%{$searchTerm}%")
                    ->orWhere('salary_instructions.branch_name', 'like', "%{$searchTerm}%")
                    ->orWhere('salary_instructions.month_year', 'like', "%{$searchTerm}%");
            });
        }

        $items = $query->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        $sheets = SalarySheet::where('deleted', '=', 'No')->get();

        return view('admin.payroll.salarySheet.SheetInstruction.salarySheetInstruction', compact('items', 'sheets'));
    }

    public function getSalaryInformationData()
    {

        $salaryInstruction = DB::table('salary_instructions')
            ->join('salary_sheets', 'salary_instructions.sheet_id', '=', 'salary_sheets.id')
            ->select('salary_instructions.*', 'salary_sheets.sheet_name')
            ->where('salary_instructions.deleted', '=', 'No')
            ->orderby('salary_instructions.id', 'DESC')
            ->get();

        $output = ['data' => []];
        $i = 1;
        foreach ($salaryInstruction as $instruction) {
            $status = '';
            if ($instruction->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$instruction->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$instruction->status.'"></i></center>';
            }
            /*$button = '<button type="button"  class="btn btn-xs btn-warning btnEdit" title="Edit Party" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="" title="Delete Party"><i class="fa fa-trash"> </i></button>';*/
            $button = '<div class="btn-grade">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">

<li class="action"><a href="#/" onclick="viewInstruction('.$instruction->id.')" class="btn"><i class="fas fa-calendar-alt"></i> View </a></li>
<li class="action"><a href="#/" onclick="editSalarySheetInstruction('.$instruction->id.')" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
<li class="action"><a href="#/" class="btn" onclick="confirmDelete('.$instruction->id.')"><i class="fas fa-trash"></i> Delete </a></li>
                </li>

                </ul>
            </div>';
            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$instruction->id.'" />',
                $instruction->month_year.'<input type="hidden" name="month_year" id="month_year" value="'.$instruction->month_year.'" />',
                $instruction->sheet_name.'<input type="hidden" name="sheet_id" id="sheet_id" value="'.$instruction->sheet_id.'" />',

                $instruction->bank_name,
                $instruction->branch_name,
                $status,
                $button,
            ];
        }

        return $output;
    }

    public function viewInstruction(Request $request)
    {
        $instructions = SalaryInstruction::find($request->id);
        $monthYear = $instructions->month_year;
        $sheetId = $instructions->sheet_id;
        $salaryinstruction = DB::table('salary_instructions')
            ->join('our_teams', 'salary_instructions.sheet_id', '=', 'our_teams.sheet_id')
            ->join('final_salary_sheets', 'our_teams.id', '=', 'final_salary_sheets.employee_id')
            ->select('salary_instructions.*',
                'our_teams.member_name',
                'our_teams.account_no',
                'our_teams.priority',
                'our_teams.salary',
                'our_teams.id',
                'final_salary_sheets.net_total'
            )
            ->where('salary_instructions.month_year', '=', $monthYear)
            ->Where('our_teams.sheet_id', '=', $sheetId)
            ->where('final_salary_sheets.month_year', '=', $monthYear)
            ->Where('final_salary_sheets.sheet_id', '=', $sheetId)
            ->orderBy('our_teams.priority', 'ASC')
            ->Where('final_salary_sheets.deleted', '=', 'No')
            ->get();

        $letterInstructions = SalaryInstruction::where('month_year', '=', $monthYear)->Where('sheet_id', '=', $sheetId)->where('deleted', '=', 'No')->where('status', '=', 'Active')->get();
        $netamounts = FinalSalarySheet::where('sheet_id', '=', $sheetId)->where('month_year', '=', $monthYear)->sum('net_total');

        $data = '';
        $i = 1;
        foreach ($letterInstructions as $letInstruction) {
            $data .= ' <h4 style="text-align:center;">Bank Sheet</h4>
                             <p>'.$letInstruction->letter_body.'</p>
                            <p>Mother Account: '.$letInstruction->mother_account_no.'</p>
                            <table  style="width:100%;text-align:center;margin-top:1px;">
                            <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Employee</th>   
                                        <th>Account No</th>
                                        <th>Salary</th>
                                        <th>Bank</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>';
        }

        foreach ($salaryinstruction as $instruction) {
            $data .= '<tbody >
                                        <tr> 
                                            <td>'.$i++.'</td>
                                            <td>'.$instruction->member_name.'</td>
                                            <td>'.$instruction->account_no.'</td>
                                            <td>'.$instruction->net_total.' Taka</td>
                                            <td>'.$instruction->bank_name.'<br>('.$instruction->branch_name.')</td>
                                            <td><a class="btn btn-primary" style="color:#fff;" onclick=finalSheet('.$instruction->id.') ><i class="fas fa-calendar-day"></i> Sheet</a></td>
                                        </tr>
                                    </tbody>';

        }

        $data .= '
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Net Payable: </th>
                                        <th>'.$netamounts.' Taka Only</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                            ';
        $data .= '</table><br>';
        foreach ($letterInstructions as $pdfInstruction) {

            $data .= '<button class="btn btn-success" onclick="generatePdf('.$pdfInstruction->id.')" ><i class="fas fa-file-pdf"></i> Generate PDF</button>';
        }

        return view('admin.payroll.salarySheet.SheetInstruction.sheetInstructionGenerateView', ['letterInstructions' => $letterInstructions, 'salaryinstruction' => $salaryinstruction, 'netamounts' => $netamounts]);
    }

    public function create()
    {

        $sheets = SalarySheet::where('deleted', '=', 'No')->get();

        return view('admin.payroll.salarySheet.SheetInstruction.salarySheetInstructionAdd', ['sheets' => $sheets]);

    }

    public function store(Request $request)
    {

        /*  $validated = $request->validate([
            'mother_account_no' => 'numeric' ,
            'sheet_id' => 'required',
            'bank_name' => 'required',
            'branch_name' => 'required',
            'footer_instruction' => 'required',
            'letter_body' => 'required'
        ]);   */

        $salaryInstruction = new SalaryInstruction;
        $salaryInstruction->sheet_id = $request->sheet_id;

        $salaryInstruction->month_year = $request->month_year;
        $salaryInstruction->bank_name = $request->bank_name;
        $salaryInstruction->branch_name = $request->branch_name;
        $salaryInstruction->mother_account_no = $request->mother_account_no;
        $salaryInstruction->footer_instruction = $request->footer_instruction;
        $salaryInstruction->letter_body = $request->letter_body;

        $salaryInstruction->deleted = 'No';
        $salaryInstruction->status = 'Active';
        $salaryInstruction->created_by = Auth::user()->id;
        $salaryInstruction->save();

        return redirect()->route('SalaryInstructionView')->with('message', 'New Salary Sheet Instruction created successfully!');
    }

    public function edit(Request $request)
    {
        $salaryInstruction = SalaryInstruction::find($request->id);

        return $salaryInstruction;
    }

    public function update(Request $request)
    {

        /*   $validated = $request->validate([
              'total_amount' => 'numeric',
              'mother_account_no' => 'numeric'
          ]); */

        $salaryInstruction = SalaryInstruction::find($request->id);

        $salaryInstruction->month_year = $request->month_year;
        $salaryInstruction->sheet_id = $request->sheet_id;
        $salaryInstruction->total_amount = $request->total_amount;
        $salaryInstruction->month_year = $request->month_year;
        $salaryInstruction->bank_name = $request->bank_name;
        $salaryInstruction->branch_name = $request->branch_name;
        $salaryInstruction->mother_account_no = $request->mother_account_no;
        $salaryInstruction->footer_instruction = $request->footer_instruction;
        $salaryInstruction->letter_body = $request->letter_body;

        $salaryInstruction->status = $request->status;
        $salaryInstruction->last_updated_by = Auth::user()->id;
        $salaryInstruction->save();

        return response()->json(['success' => $request->sheet_id.' updated successfully']);
    }

    public function delete(Request $request)
    {

        $salaryInstruction = SalaryInstruction::find($request->id);
        $salaryInstruction->status = 'Inactive';
        $salaryInstruction->deleted = 'Yes';
        $salaryInstruction->deleted_by = Auth::user()->id;
        $salaryInstruction->deleted_date = date('Y-m-d H:i:s');
        $salaryInstruction->save();

        return response()->json(['success' => 'Sheet deleted successfully']);

    }

    public function sheetGenerateView()
    {
        $count = Email::where('replied_by', null)->count();
        $salaryinstructionss = SalaryInstruction::where('deleted', '=', 'No')->select('month_year')->distinct()->get();
        $sheets = SalarySheet::where('deleted', '=', 'No')->get();

        return view('admin.payroll.salarySheet.SheetInstruction.sheetInstructionGenerateView', ['salaryinstructionss' => $salaryinstructionss, 'sheets' => $sheets, 'count' => $count]);
    }

    public function generatePdfData(Request $request)
    {
        $instructions = SalaryInstruction::find($request->id);
        $monthYear = $instructions->month_year;
        $sheetId = $instructions->sheet_id;
        $salaryinstructionss = SalaryInstruction::where('deleted', '=', 'No')->select('month_year')->distinct()->get();
        $sheets = SalarySheet::where('deleted', '=', 'No')->get();

        $salaryinstruction = DB::table('salary_instructions')
            ->join('our_teams', 'salary_instructions.sheet_id', '=', 'our_teams.sheet_id')
            ->join('final_salary_sheets', 'our_teams.id', '=', 'final_salary_sheets.employee_id')
            ->select('salary_instructions.*',
                'our_teams.member_name',
                'our_teams.account_no',
                'our_teams.priority',
                'our_teams.salary',
                'our_teams.id',
                'final_salary_sheets.net_total'
            )
            ->where('salary_instructions.month_year', '=', $monthYear)
            ->Where('our_teams.sheet_id', '=', $sheetId)
            ->where('final_salary_sheets.month_year', '=', $monthYear)
            ->Where('final_salary_sheets.sheet_id', '=', $sheetId)
            ->orderBy('our_teams.priority', 'ASC')
            ->Where('salary_instructions.deleted', '=', 'No')
            ->get();

        $letterInstructions = SalaryInstruction::where('month_year', '=', $monthYear)->Where('sheet_id', '=', $sheetId)->first();
        $netamounts = FinalSalarySheet::where('sheet_id', '=', $sheetId)->where('month_year', '=', $monthYear)->sum('net_total');

        $pdf = PDF::loadView('admin.payroll.salarySheet.SheetInstruction.bankSalaryReport',
            ['salaryinstruction' => $salaryinstruction, 'letterInstructions' => $letterInstructions, 'netamounts' => $netamounts]);

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(530, 820, 'Page {PAGE_NUM} of {PAGE_COUNT}', null, 10, [0, 0, 0]);

        return $pdf->stream('bank-salary-pdf.pdf', ['Attachment' => false]);
    }

    public function generateInstructionBody(Request $request)
    {
        $memberID = $request->member_id;

        $sheets = DB::table('our_teams')
            ->join('salary_instructions', 'salary_instructions.sheet_id', '=', 'our_teams.sheet_id')
            ->select('our_teams.*',
                'salary_instructions.month_year',
                'salary_instructions.bank_name',
                'salary_instructions.branch_name',
                'salary_instructions.mother_account_no',
                'salary_instructions.footer_instruction',
                'salary_instructions.letter_body'
            )
            ->where('our_teams.id', '=', $memberID)
            ->Where('our_teams.deleted', '=', 'No')
            ->Where('our_teams.status', '=', 'Active')
            ->get();

        foreach ($sheets as $sheet) {
            $sheet = '<li class="list-group-item"><b>Employee Name:</b> '.$sheet->member_name.'</li>
                    <li class="list-group-item"><b>Month Year:</b> '.$sheet->month_year.'</li>
                    <li class="list-group-item"><b>Bank:</b> '.$sheet->bank_name.'</li>
                    <li class="list-group-item"><b>Branch:</b> '.$sheet->branch_name.'</li>
                    <li class="list-group-item"><b>Company Account No:</b> '.$sheet->mother_account_no.'</li>
                    <li class="list-group-item"><b>Letter Body:</b><br> '.$sheet->letter_body.'</li>
                    <li class="list-group-item"><b>Footer Text:</b><br> '.$sheet->footer_instruction.'</li>';
        }

        return $sheet;
    }
}
