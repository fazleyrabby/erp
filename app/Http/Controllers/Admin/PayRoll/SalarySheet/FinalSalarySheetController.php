<?php

namespace App\Http\Controllers\Admin\PayRoll\SalarySheet;

use App\Http\Controllers\Controller;
use App\Models\payroll\BonusList;
use App\Models\payroll\Facility;
use App\Models\payroll\FinalSalarySheet;
use App\Models\payroll\MonthlyAmount;
use App\Models\payroll\SalaryInstruction;
use App\Models\payroll\SalaryLoanDetails;
use App\Models\payroll\SalarySheet;
use App\Models\payroll\SavedSalarySheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class FinalSalarySheetController extends Controller
{
    public function index()
    {

        $sheets = SavedSalarySheet::with('salarySheet')
            ->where('deleted', 'No')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.payroll.salarySheet.finalSalarySheet.finalSalarySheetView', ['sheets' => $sheets]);
    }

    public function checkSalaryInstruction(Request $request)
    {
        $Id = $request->id;
        $salarySheet = SavedSalarySheet::find($Id);
        $monthYear = $salarySheet->month_year;
        $sheetId = $salarySheet->sheet_id;
        $instructions = SalaryInstruction::where('month_year', '=', $monthYear)->where('deleted', '=', 'No')->where('status', '=', 'Active')->first();

        return $instructions;
    }

    public function create()
    {

        $sheets = SalarySheet::where('deleted', '=', 'No')->where('deleted', 'No')->where('status', 'Active')->get();

        return view('admin.payroll.salarySheet.finalSalarySheet.salarySheetFinalAdd', ['sheets' => $sheets]);
    }

    public function generateFinalSalary(Request $request)
    {

        $monthYear = $request->month_year;
        $sheetId = $request->sheet_id;

        $salary = DB::table('our_teams')
            ->join('steps', 'our_teams.current_step', '=', 'steps.id')
            ->select('our_teams.*', 'steps.salary_amount')
            ->Where('our_teams.sheet_id', '=', $sheetId)
            ->orderBy('our_teams.priority', 'ASC')
            ->Where('our_teams.deleted', '=', 'No')
            ->get();

        $data = '';
        $i = 1;

        foreach ($salary as $sal) {

            $employeeGroupId = $sal->group_id;
            $userId = $sal->id;

            $facilities = Facility::where('deleted', '=', 'No')->where('group_id', '=', $employeeGroupId)->get();

            foreach ($facilities as $facility) {
                if ($facility->facility_name == 'House Rent') {
                    $houseRent = $facility->amount;
                } elseif ($facility->facility_name == '') {
                    $houseRent = '0n';
                }

            }

            $facilities2 = Facility::where('deleted', '=', 'No')->where('group_id', '=', $employeeGroupId)->get();
            foreach ($facilities2 as $facility2) {
                if ($facility2->facility_name == 'Medical') {
                    $medical = $facility2->amount;
                } elseif ($facility2->facility_name == '') {
                    $medical = '0n';
                }
            }

            $facilities3 = Facility::where('deleted', '=', 'No')->where('group_id', '=', $employeeGroupId)->get();
            foreach ($facilities3 as $facility3) {
                if ($facility3->facility_name == 'Provident Fund') {
                    $p_fund = $facility3->amount;
                } elseif ($facility3->facility_name == '') {
                    $p_fund = '0n';
                }
            }

            $facilities = Facility::where('deleted', '=', 'No')->where('group_id', '=', $employeeGroupId)->get();
            $cc = '0n';
            foreach ($facilities as $facility) {
                if ($facility->facility_name == 'Company Contribution') {
                    $cc = $facility->amount;
                } elseif ($facility->facility_name == '') {

                }
            }

            $adjustments = MonthlyAmount::where('deleted', '=', 'No')->where('user_id', '=', $userId)->where('month_year', '=', $monthYear)->get();

            $adj = 0;
            $due = 0;
            foreach ($adjustments as $adjustment) {

                if ($adjustment->type == 'Add') {
                    $adj += $adjustment->amount;
                } elseif ($adjustment->type == 'Deduct') {
                    $due += $adjustment->amount;
                } else {
                    $due = 0;
                    $adj = 0;
                }
            }

            $loans = SalaryLoanDetails::where('deleted', '=', 'No')->where('loan_status', 'Pending')->where('user_id', '=', $userId)->where('month_year', '=', $monthYear)->get();
            $tenure = 0;
            foreach ($loans as $loan) {
                if ($loan->loan_status == 'Pending') {
                    $tenure = $loan->installment;
                } else {

                }

            }
            $bonusAmount = 0;
            $percentage = 0;
            $bonusAmountPercentage = 0;
            $bonuses = BonusList::where('deleted', '=', 'No')->where('status', '=', 'Active')->where('group_id', '=', $employeeGroupId)->where('month_year', '=', $monthYear)->get();
            foreach ($bonuses as $bonus) {
                if ((substr($bonus->amount, -1) == '%')) {
                    $percentage += substr_replace($bonus->amount, '', -1);
                    $bonusAmountPercentage = ($percentage / 100) * $sal->salary_amount;
                } else {
                    $bonusAmount += $bonus->amount;
                }
            }
            $totalBonus = $bonusAmount + $bonusAmountPercentage;

            /* Consolated salary check */
            if (($sal->salary_type) == 'scale') {
                $consolated = 0;
            } else {
                $consolated = $sal->salary;
                $sal->salary = 0;
                $houseRent = 0;
                $medical = 0;

                $p_fund = 0;
                $sal->salary_amount = 0;
                $sal->ta_da = 0;

            }

            $month_year_seq[] = $monthYear;
            $basic = $sal->salary;
            $laundry = $sal->laundry;
            $telephone = $sal->phone_bill;
            $taDa = $sal->ta_da;
            $salaryAmount = $sal->salary_amount;

            if ((substr($houseRent, -1) == '%')) {
                $house_rent = ((substr_replace($houseRent, '', -1)) / 100) * $salaryAmount;
            } else {
                $house_rent = $houseRent;
            }

            if ((substr($medical, -1) == '%')) {
                $medical_allowence = ((substr_replace($medical, '', -1)) / 100) * $salaryAmount;
            } else {
                $medical_allowence = $medical;
            }

            if ((substr($cc, -1) == '%')) {
                $company_contribution = ((substr_replace($cc, '', -1)) / 100) * $salaryAmount;
            } else {
                $company_contribution = $cc;
            }

            if ((substr($p_fund, -1) == '%')) {
                $pf = ((substr_replace($p_fund, '', -1)) / 100) * $salaryAmount;
            } else {
                $pf = $p_fund;
            }

            $d_pf = $pf + $pf;

            $total = $salaryAmount + $laundry + $telephone + $taDa + $house_rent + $medical_allowence + $consolated + $adj + $d_pf + $company_contribution + $totalBonus;
            $netTotal = $total - ($due + $d_pf + $tenure);

            /* Table Data */
            $data .= '<tr > 
                                <td>'.$i++.'</td>
                                <td><span id="account_no_'.$i.'">'.$sal->account_no.'</span><input type="hidden" name="account_no_seq[]" value="'.$sal->account_no.'"></td>
                                <td><span id="member_name_'.$i.'">'.$sal->member_name.'</span><input type="hidden" name="member_id_seq[]" value="'.$sal->id.'"></td>
                                <td><span id="joining_date_'.$i.'">'.$sal->joining_date.'</span><input type="hidden" name="joining_date_seq[]" value="'.$sal->joining_date.'"></td>
                                <td><span id="consolated_'.$i.'">'.$consolated.'</span><input type="hidden" name="consolated_seq[]" value="'.$consolated.'"></td>
                                <td><span id="basic_'.$i.'">'.$basic.'</span><input type="hidden" name="basic_seq[]" value="'.$basic.'"></td>
                                <td><span id="house_rent_'.$i.'">'.$house_rent.'</span><input type="hidden" name="house_rent_seq[]" value="'.$house_rent.'"></td> 
                                <td><span id="medical_allowence_'.$i.'">'.$medical_allowence.' </span><input type="hidden" name="medical_allowence_seq[]" value="'.$medical_allowence.'"></td>
                                <td><span id="company_contribution_'.$i.'">'.$company_contribution.'</span><input type="hidden" name="company_contribution_seq[]" value="'.$company_contribution.'"></td>
                                <td><span id="laundry_'.$i.'">'.$laundry.'</span><input type="hidden" name="laundry_seq[]" value="'.$laundry.'"></td>
                                <td><span id="telephone_'.$i.'">'.$telephone.'</span><input type="hidden" name="telephone_seq[]" value="'.$telephone.'"></td>
                                <td><span id="taDa_'.$i.'">'.$taDa.'</span><input type="hidden" name="taDa_seq[]" value="'.$taDa.'"></td>
                                <td><span id="pf_'.$i.'">'.$pf.'</span><input type="hidden" name="pf_seq[]" value="'.$pf.'"></td>
                                <td><span id="c_pf_'.$i.'">'.$pf.'</span><input type="hidden" name="c_pf_seq[]" value="'.$pf.'"></td>
                                <td><span id="bonus_'.$i.'">'.$totalBonus.'</span><input type="hidden" name="bonus_seq[]" value="'.$totalBonus.'"></td>

                                <td><span id="adjustment_'.$i.'">'.$adj.'</span><input type="hidden" name="adjustment_seq[]" value="'.$adj.'"></td>
                                <td><span id="salaryAmount_'.$i.'">'.$salaryAmount.' Taka</span><input type="hidden" name="salaryAmount_seq[]" value="'.$salaryAmount.'"></td>
                                <td><span id="total_'.$i.'">'.$total.'</span><input type="hidden" name="total_seq[]" value="'.$total.'"></td>
                                <td><span id="due_'.$i.'">'.$due.'</span><input type="hidden" name="due_seq[]" value="'.$due.'"></td>
                                <td><span id="d_pf_'.$i.'">'.$d_pf.'</span><input type="hidden" name="d_pf_seq[]" value="'.$d_pf.'"></td>
                                <td><span id="tenure_'.$i.'">'.$tenure.'</span><input type="hidden" name="tenure_seq[]" value="'.$tenure.'"></td>
                                <td><span id="netTotal_'.$i.'">'.$netTotal.'</span><input type="hidden" name="netTotal_seq[]" value="'.$netTotal.'"></td>
                              
                            </tr>';
        }

        return $data;
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'member_id_seq' => 'required',

        ]);
        $savedSheets = new SavedSalarySheet;
        $savedSheets->month_year = $request->month_year;
        $savedSheets->sheet_id = $request->sheet_id;
        $savedSheets->deleted = 'No';
        $savedSheets->status = 'Active';
        $savedSheets->created_by = Auth::user()->id;
        $result = $savedSheets->save();
        $last_id = $savedSheets->id;
        $sum = 0;
        for ($i = 0; $i < count($request->account_no_seq); $i++) {
            $sheets = new FinalSalarySheet;
            $sheets->month_year = $request->month_year;
            $sheets->sheet_id = $request->sheet_id;
            $sheets->account_no = $request->account_no_seq[$i];
            $sheets->employee_id = $request->member_id_seq[$i];
            $sheets->joining_date = $request->joining_date_seq[$i];
            $sheets->consulate = $request->consolated_seq[$i];
            $sheets->basic = $request->basic_seq[$i];
            $sheets->house_rent = $request->house_rent_seq[$i];
            $sheets->medical_allowence = $request->medical_allowence_seq[$i];
            $sheets->company_contribution = $request->company_contribution_seq[$i];
            $sheets->laundry = $request->laundry_seq[$i];
            $sheets->phone_bill = $request->telephone_seq[$i];
            $sheets->ta_da = $request->taDa_seq[$i];
            $sheets->provident_fund = $request->pf_seq[$i];
            $sheets->company_provident_fund = $request->c_pf_seq[$i];
            $sheets->monthly_bonus = $request->bonus_seq[$i];
            $sheets->adjustment = $request->adjustment_seq[$i];
            $sheets->step_amount = $request->salaryAmount_seq[$i];
            $sheets->total = $request->total_seq[$i];
            $sheets->due = $request->due_seq[$i];
            $sheets->deduct_provident_fund = $request->d_pf_seq[$i];
            $sheets->loan_installment = $request->tenure_seq[$i];
            $sheets->net_total = $request->netTotal_seq[$i];
            $sheets->saved_sheet_id = $last_id;

            $sheets->deleted = 'No';
            $sheets->status = 'Active';
            $sheets->created_by = Auth::user()->id;
            $result = $sheets->save();

            if ($request->tenure_seq[$i] > 0) {

                $tenures = SalaryLoanDetails::where('month_year', '=', $request->month_year)->where('user_id', '=', $request->member_id_seq[$i])->first();
                $tenures->deleted = 'Yes';
                $tenures->loan_status = 'Paid';
                $tenures->save();
            }
            $sum += $request->netTotal_seq[$i];
        }

        $sheets = SavedSalarySheet::find($last_id);
        $sheets->company_payable_net_salary = $sum;
        $sheets->save();

        if ($result) {
            return redirect()->route('finalSheetIndex')->with('message', 'New Salary Sheet  created successfully!');

        } else {
            return back()->with('message', 'Generate the sheet first!');

        }

    }

    public function delete(Request $request, $id)
    {

        $sheets = SavedSalarySheet::find($id);

        $sheets->status = 'Inactive';
        $sheets->deleted = 'Yes';
        $sheets->deleted_by = Auth::user()->id;
        $sheets->deleted_date = date('Y-m-d H:i:s');
        $result = $sheets->save();

        $sheetdata = FinalSalarySheet::find($id);
        $sheetdata->deleted = 'Yes';
        $sheetdata->status = 'Inactive';
        $result = $sheetdata->save();

        if ($result) {
            return back()->with('message', 'Sheet deleted!');
        } else {
            return back()->with('message', 'Something went wrong!');
        }

        return redirect('module')->with('message', 'Module In-Active secessfully');
    }

    public function view($id)
    {

        $sheets = DB::table('final_salary_sheets')
            ->join('salary_sheets', 'final_salary_sheets.sheet_id', '=', 'salary_sheets.id')
            ->join('our_teams', 'final_salary_sheets.employee_id', '=', 'our_teams.id')
            ->select('final_salary_sheets.*', 'salary_sheets.sheet_name', 'salary_sheets.id as salary_sheet_id', 'our_teams.member_name')
            ->where('final_salary_sheets.saved_sheet_id', '=', $id)
            ->where('final_salary_sheets.deleted', '=', 'No')
            ->get();
        foreach ($sheets as $sheet) {
            $monthYear = $sheet->month_year;
            $sheetID = $sheet->salary_sheet_id;
        }

        $instructions = SalaryInstruction::where('month_year', '=', $monthYear)->where('sheet_id', '=', $sheetID)->where('deleted', '=', 'No')->where('status', '=', 'Active')->first();

        $sheetid = SavedSalarySheet::find($id);
        $sumsheets = FinalSalarySheet::where('saved_sheet_id', '=', $id)->where('deleted', '=', 'No')->sum('net_total');

        return view('admin.payroll.salarySheet.finalSalarySheet.finalSalarySheetDataView',
            ['sumsheets' => $sumsheets, 'sheets' => $sheets, 'sheetid' => $sheetid, 'instructions' => $instructions]);

    }

    public function generateSalarySheetPdf(Request $request)
    {

        $monthYear = $request->month_year;
        $sheetId = $request->sheet_id;
        $ID = $request->id;

        $footertext = '';
        $sheets = DB::table('final_salary_sheets')
            ->leftjoin('salary_sheets', 'final_salary_sheets.sheet_id', '=', 'salary_sheets.id')
            ->join('our_teams', 'final_salary_sheets.employee_id', '=', 'our_teams.id')
            ->select('final_salary_sheets.*', 'salary_sheets.sheet_name', 'our_teams.member_name')
            ->where('final_salary_sheets.saved_sheet_id', '=', $ID)
            ->where('final_salary_sheets.deleted', '=', 'No')
            ->get();

        $footertext = SalaryInstruction::where('month_year', '=', $monthYear)->where('deleted', '=', 'No')->where('status', '=', 'Active')->Where('sheet_id', '=', $sheetId)->first();

        $pdf = PDF::loadView('admin.payroll.salarySheet.finalSalarySheet.salarySheetReport',
            ['sheets' => $sheets, 'footertext' => $footertext])->setPaper('legal', 'landscape');

        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(930, 587, 'Page {PAGE_NUM} of {PAGE_COUNT}', null, 10, [0, 0, 0]);

        return $pdf->stream('salary-sheet-pdf.pdf', ['Attachment' => false]);

    }
}
