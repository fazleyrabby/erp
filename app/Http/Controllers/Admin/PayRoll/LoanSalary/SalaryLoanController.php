<?php

namespace App\Http\Controllers\Admin\PayRoll\LoanSalary;

use App\Http\Controllers\Controller;
use App\Models\payroll\OurTeam;
use App\Models\payroll\SalaryLoan;
use App\Models\payroll\SalaryLoanDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class SalaryLoanController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = DB::table('salary_loans')
            ->join('our_teams', 'salary_loans.user_id', '=', 'our_teams.id')
            ->select('salary_loans.*', 'our_teams.member_name')
            ->where('salary_loans.deleted', 'No');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('our_teams.member_name', 'like', "%{$searchTerm}%");
            });
        }

        $loans = $query->orderBy('salary_loans.'.$sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        $employees = OurTeam::where('deleted', 'No')->get();

        return view('admin.payroll.salaryLoan.salaryLoanView', compact('loans', 'employees'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'tenure' => 'required|numeric',
        ]);

        $loans = new SalaryLoan;
        $loans->user_id = $request->user_id;
        $loans->amount = $request->amount;
        $loans->installment = $request->installment;
        $loans->percent = $request->percent;
        $loans->month_year = $request->month_year;
        $loans->tenure = $request->tenure;
        $loans->adjustment = '500';
        $loans->applicable_from = $request->applicable_from;
        $loans->cause = $request->cause;

        $loans->deleted = 'No';
        $loans->status = 'Active';
        $loans->created_by = Auth::user()->id;
        $loans->save();

        $last_id = $loans->id;

        /*$userIdArray = explode(",",$request->user_id);
        $monthYearArray = explode(",",$request->month_year);
        $installmentArray = explode(",",$request->installment);*/

        /*for($i=0; $i< count($request->month_year_seq); $i++){
            dd($request->month_year_seq[$i]);
        }*/

        for ($i = 0; $i < count($request->month_year_seq); $i++) {
            $tenure = new SalaryLoanDetails;

            $tenure->month_year = $request->month_year_seq[$i];
            $tenure->installment = $request->installment_seq[$i];
            $tenure->user_id = $request->user_id_seq[$i];
            $tenure->loan_id = $last_id;
            $tenure->deleted = 'No';
            $tenure->loan_status = 'Pending';
            $tenure->created_by = Auth::user()->id;
            $tenure->save();
        }

        return redirect()->route('loanIndex')->with('message', 'Salary Loan Data created successfully!');

    }

    public function edit(Request $request)
    {

        $loans = SalaryLoan::find($request->id);

        return $loans;
    }

    public function update(Request $request)
    {
        $request->validate([
            'tenure' => 'numeric',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        $loans = SalaryLoan::find($request->id);
        $loans->user_id = $request->user_id;
        $loans->amount = $request->amount;
        $loans->adjustment = $request->adjustment;
        $loans->tenure = $request->tenure;
        $loans->applicable_from = $request->applicable_from;
        $loans->cause = $request->cause;
        $loans->last_updated_by = Auth::user()->id;
        $result = $loans->save();

        return response()->json(['success' => $request->user_id.' updated successfully']);
    }

    public function delete(Request $request)
    {

        $loans = SalaryLoan::find($request->id);
        $loans->user_id = $loans->user_id;
        $loans->status = 'Inactive';
        $loans->deleted = 'Yes';
        $loans->deleted_by = Auth::user()->id;
        $loans->deleted_date = date('Y-m-d H:i:s');
        $loans->save();

        $tenures = SalaryLoanDetails::where('loan_id', '=', $request->id)->get();
        foreach ($tenures as $tenure) {
            $tenure->loan_status = 'Reject';
            $tenure->deleted = 'Yes';
            $tenure->save();
        }

        return response()->json(['success' => 'Deleted successfully']);

    }

    public function addTenureIndex()
    {

        $employees = OurTeam::where('deleted', '=', 'No')->get();

        return view('admin.payroll.salaryLoan.salaryLoanAdd', ['employees' => $employees]);

    }

    public function getTenureData(Request $request)
    {

        $tenure = $request->tenure;
        $installment = $request->installment;
        $amount = $request->tenure;
        $user_id = $request->user_id;
        $startingMonthYear = $request->startingMonthYear;
        $startDate = strtotime('1-'.$startingMonthYear);
        $newformatStartDate = date('Y-m-d', $startDate);

        $data = '<table class="table table-bordered table-striped">
        <tr> 
            <th>SL</th> 
            <th>User_id</th> 
            <th>Month Year</th> 
            <th>Installment</th> 
            <th>Action</th> 
        </tr>';

        for ($i = 0; $i < $tenure; $i++) {
            $monthYear = date(Session::get('companySettings')[0]['month_year'], strtotime($newformatStartDate.' '.($i + 1).' Month -1 Day'));

            $data .= '<tr>
                            <td>'.($i + 1).'</td>
                            <td><span id="user_id_'.$i.'">'.$user_id.'</span><input type="hidden" name="user_id_seq[]" value="'.$user_id.'"></td>
                            <td><span id="month_year_'.$i.'">'.$monthYear.'</span><input type="hidden" name="month_year_seq[]" value="'.$monthYear.'"></td>
                            <td><span id="installment_'.$i.'">'.$installment.'</span><input type="hidden" name="installment_seq[]" value="'.$installment.'"></td>
                            <td> <a href="#"><i class="fa fa-trash"></i> </a> </td>
                    </tr>';
        }

        $data .= '</table>';

        return $data;

    }

    public function tenureDataSave(Request $request)
    {

        $userIdArray = explode(',', $request->user_id);
        $monthYearArray = explode(',', $request->month_year);
        $installmentArray = explode(',', $request->installment);

        for ($i = 0; $i < count($monthYearArray); $i++) {
            $tenure = new SalaryLoanDetails;
            $tenure->user_id = $userIdArray[$i];
            $tenure->month_year = $monthYearArray[$i];
            $tenure->installment = $installmentArray[$i];

            $tenure->deleted = 'No';
            $tenure->loan_status = 'Pending';
            $tenure->created_by = Auth::user()->id;

            $tenure->save();
        }

        return response()->json(['message' => ' Tenure data Stored successfully']);

    }

    public function tenureView(Request $request)
    {

        $tenures = SalaryLoanDetails::where('loan_id', $request->loan_id)->get();

        /*  $tenures= DB::table('salary_loan_details')
                ->join('final_salary_sheets','salary_loan_details.user_id','=','final_salary_sheets.employee_id')
                ->select('salary_loan_details.*','final_salary_sheets.employee_id')
                ->where('loan_id', $request->loan_id)
                ->get(); */

        return $tenures;
    }

    public function tenurePending(Request $request)
    {

        $tenures = SalaryLoanDetails::find($request->id);
        $tenures->loan_status = $request->status;
        $tenures->save();

        return response()->json(['success' => ' Tenure status changed successfully', 'loan_id' => $tenures->loan_id]);
    }

    public function generateLoanTenurePdf($id)
    {

        $loans = DB::table('salary_loan_details')
            ->join('our_teams', 'salary_loan_details.user_id', '=', 'our_teams.id')
            ->select('salary_loan_details.*', 'our_teams.member_name')
            ->where('salary_loan_details.loan_id', '=', $id)
            ->get();

        $loan = DB::table('salary_loans')
            ->join('our_teams', 'salary_loans.user_id', '=', 'our_teams.id')
            ->select('salary_loans.*', 'our_teams.member_name')
            ->where('salary_loans.id', '=', $id)
            ->first();

        $nettotal = SalaryLoanDetails::where('loan_id', '=', $id)->sum('installment');

        $pdf = PDF::loadView('admin.payroll.salaryLoan.salaryPdfReport',
            ['loans' => $loans, 'loan' => $loan, 'nettotal' => $nettotal])->setPaper('legal', 'portrait');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(545, 987, 'Page {PAGE_NUM} of {PAGE_COUNT}', null, 10, [0, 0, 0]);

        return $pdf->stream('EmployeeLoan-pdf.pdf', ['Attachment' => false]);
    }
}
