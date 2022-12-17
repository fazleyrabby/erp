<?php

/*For Payroll Start*/
use App\Http\Controllers\Admin\PayRoll\user\OurTeamController;
use App\Http\Controllers\Admin\PayRoll\Facility\FacilityController;
use App\Http\Controllers\Admin\PayRoll\gradeAndStep\GradeController;
use App\Http\Controllers\Admin\PayRoll\gradeAndStep\StepsController;
use App\Http\Controllers\Admin\PayRoll\Group\GroupController;
use App\Http\Controllers\Admin\PayRoll\gradeAndStep\GradeNewController;
use App\Http\Controllers\Admin\PayRoll\gradeAndStep\StepNewController;
use App\Http\Controllers\Admin\PayRoll\MonthlyAmount\MonthlyAmountController;
use App\Http\Controllers\Admin\PayRoll\LoanSalary\SalaryLoanController;
use App\Http\Controllers\Admin\PayRoll\SalarySheet\SalarySheetController;
use App\Http\Controllers\Admin\PayRoll\SalarySheet\FinalSalarySheetController;
use App\Http\Controllers\Admin\PayRoll\SalarySheet\SalaryInstructionController;
use App\Http\Controllers\Admin\PayRoll\SalarySheet\BonusController;
use App\Http\Controllers\Admin\PayRoll\Setting\PayrollSettingController;
use App\Http\Controllers\Admin\PayRoll\Attendence\AttendenceController;
use App\Http\Controllers\Admin\PayRoll\Attendence\AttendenceGroupWiseController;
use App\Http\Controllers\Admin\PayRoll\Group\TimeGroupController;
use App\Http\Controllers\Admin\PayRoll\Group\UserTimeScheduleController;
use App\Http\Controllers\Admin\PayRoll\LeaveManagement\LeaveController;
/*For Payroll End*/








/*Payroll Start*/
/*-------------------------Payroll Routes----------------> */
Route::group(['middleware' => ['auth']], function(){
	Route::prefix('payroll')->group(function(){
		
	
	
	/*-----Grade Routes start---------*/ 
	
	Route::get('gradeIndex',[GradeNewController::class,'index'])->name('gradeIndex');
	Route::get('getGradeData',[GradeNewController::class,'getGradeData'])->name('getGradeData');
	Route::post('gradeStore',[GradeNewController::class,'store'])->name('gradeStore');
	Route::get('editGrade',[GradeNewController::class,'edit'])->name('editGrade');
	Route::post('gradeUpdate',[GradeNewController::class,'update'])->name('gradeUpdate');
	Route::get('changeGradeStatus{id}',[GradeNewController::class,'changeGradeStatgradeUpdateus'])->name('changeGradeStatus');
	Route::post('deleteGrade',[GradeNewController::class,'delete'])->name('gradeDelete');
	/*-----Grade Routes End---------*/
	
	
	
	/*-----Steps Routes start---------*/
	Route::get('allStep',[StepNewController::class,'index'])->name('stepsIndex');
	Route::get('getSteps',[StepNewController::class,'getSteps'])->name('getSteps');
	Route::post('stepStore',[StepNewController::class,'store'])->name('stepStore');
	Route::get('editStep',[StepNewController::class,'edit'])->name('editStep');
	Route::post('stepUpdate',[StepNewController::class,'update'])->name('stepUpdate');
	Route::get('changeStepStatus{id}',[StepNewController::class,'changeStepStatus'])->name('changeStepStatus');
	Route::post('stepDelete',[StepNewController::class,'delete'])->name('stepDelete');
	/*-----Steps Routes End---------*/
	
	
	
	/*-----Groups Routes start---------*/
	
		Route::get('allGroup',[GroupController::class,'index'])->name('groupIndex');
		Route::get('getGroup',[GroupController::class,'getGroups'])->name('getGroup');
		//Route::get('createGroup',[GroupController::class,'create'])->name('createGroup');
		Route::post('groupStore',[GroupController::class,'store'])->name('groupStore');
		Route::get('editGroup',[GroupController::class,'edit'])->name('editGroup');
		Route::post('groupUpdate',[GroupController::class,'update'])->name('groupUpdate');
		
		Route::post('deleteGroup',[GroupController::class,'delete'])->name('groupDelete');
	 
	  Route::get('changeGroupStatus{id}',[GroupController::class,'changeGroupStatus'])->name('changeGroupStatus');
	
	/*-----Groups Routes End---------*/ 
	
	
	
	
	/*-----Facility Routes start---------*/
	Route::get('allFacility',[FacilityController::class,'index'])->name('facilityIndex');
	Route::get('getFacilityData',[FacilityController::class,'getFacilityData'])->name('getFacilityData');
	Route::post('facilityStore',[FacilityController::class,'store'])->name('facilityStore');
	Route::get('editFacility',[FacilityController::class,'edit'])->name('editFacility');
	Route::post('facilityUpdate',[FacilityController::class,'update'])->name('facilityUpdate');
	Route::get('changeFacilityStatus{id}',[FacilityController::class,'changefacilityStatus'])->name('changefacilityStatus');
	Route::post('facilityDelete',[FacilityController::class,'delete'])->name('facilityDelete');
	/*-----Facility Routes End---------*/
	
	
	/*-----Monthly amount Routes start---------*/
	
	Route::get('monthly/Amount/Index',[MonthlyAmountController::class,'index'])->name('monthlyAmountIndex');
	Route::get('monthly/Amount/get',[MonthlyAmountController::class,'getMonthlyAmountData'])->name('getMonthlyAmountData');
	Route::post('monthly/Amount/Store',[MonthlyAmountController::class,'store'])->name('monthlyAmountStore');
	Route::get('monthly/Amount/edit',[MonthlyAmountController::class,'edit'])->name('editMonthlyAmount');
	Route::post('monthly/Amount/Update',[MonthlyAmountController::class,'update'])->name('AmountDataUpdate');
	
	Route::post('monthly/Amount/Delete',[MonthlyAmountController::class,'delete'])->name('amountDelete');
	
	/*-----Facility Routes End---------*/
	
	
	
	/*-----Loan Salary Routes start---------*/
	Route::get('loan/Amount/Index',[SalaryLoanController::class,'index'])->name('loanIndex');
	Route::get('loan/Amount/get',[SalaryLoanController::class,'getLoanData'])->name('getLoanData');
	Route::post('loan/Amount/Store',[SalaryLoanController::class,'store'])->name('loanStore');
	Route::get('loan/Amount/edit',[SalaryLoanController::class,'edit'])->name('loanEdit');
	Route::post('loan/Amount/Update',[SalaryLoanController::class,'update'])->name('loanUpdate');
	Route::post('loan/Amount/Delete',[SalaryLoanController::class,'delete'])->name('loanDelete');
	Route::get('loan/amount/add',[SalaryLoanController::class,'addTenureIndex'])->name('addSalaryLoan');
	Route::get('loan/amount/tenureData',[SalaryLoanController::class,'getTenureData'])->name('getTenureData');
	Route::post('loan/amount/tenureData/Save',[SalaryLoanController::class,'tenureDataSave'])->name('tenureDataSave');
	Route::get('tenure/Amount/view',[SalaryLoanController::class,'tenureView'])->name('tenureView');
	Route::get('tenure/Amount/pending',[SalaryLoanController::class,'tenurePending'])->name('tenurePending');
	Route::get('tenure/Amount/generate/pdf/{id}',[SalaryLoanController::class,'generateLoanTenurePdf'])->name('generateLoanTenurePdf');
	/*-----Loan Salary Routes End---------*/
	
	
	
	/*-----Salary Sheet Routes start---------*/
	Route::get('salary/sheet/view',[SalarySheetController::class,'index'])->name('SalarySheetView');
	Route::get('salary/sheet/get/data',[SalarySheetController::class,'getSalarySheetData'])->name('getSalarySheetData');
	Route::post('salary/sheet/storeData',[SalarySheetController::class,'store'])->name('salarySheetStore');
	Route::get('salary/sheet/edit',[SalarySheetController::class,'edit'])->name('editSalarySheet');
	Route::post('salary/sheet/update',[SalarySheetController::class,'update'])->name('sheetUpdate');
	Route::post('salary/sheet/Delete',[SalarySheetController::class,'delete'])->name('salarySheetDelete');
	/*-----Salary Sheet Routes End---------*/
	
	
	
	
	/*-----Salary Instruction Routes start---------*/
	Route::get('salary/instruction/view',[SalaryInstructionController::class,'index'])->name('SalaryInstructionView');
	Route::get('salary/instruction/get/data',[SalaryInstructionController::class,'getSalaryInformationData'])->name('getSalaryInformationData');
	Route::get('salary/instruction/view/details/data/',[SalaryInstructionController::class,'viewInstruction'])->name('viewInstruction');
	Route::get('salary/instruction/add',[SalaryInstructionController::class,'create'])->name('sheetInstructionAdd');
	Route::post('salary/instruction/storeData',[SalaryInstructionController::class,'store'])->name('sheetInstructionStore');
	Route::get('salary/instruction/edit',[SalaryInstructionController::class,'edit'])->name('editSalarySheetInstruction');
	Route::post('salary/instruction/update',[SalaryInstructionController::class,'update'])->name('sheetInstructionUpdate');
	Route::post('salary/instruction/Delete',[SalaryInstructionController::class,'delete'])->name('sheetInstructionDelete');
	Route::get('salary/instruction/generate/view',[SalaryInstructionController::class,'sheetGenerateView'])->name('sheetInstructionGenerate');
	Route::get('salary/instruction/generate/instruction/',[SalaryInstructionController::class,'getSalaryInstructionData'])->name('getSalaryInstructionData');
	Route::get('salary/instruction/generate/body',[SalaryInstructionController::class,'generateInstructionBody'])->name('generateInstructionBody');
	Route::get('salary/instruction/generate/letter',[SalaryInstructionController::class,'getLetterInstructionData'])->name('getLetterInstructionData');
	Route::get('salary/instruction/generate/pdf',[SalaryInstructionController::class,'generatePdfData'])->name('generatePdfData');
	
	/*-----Salary Instruction Routes End---------*/
	
	
	
	
	
	/*-----Final Salary Sheet Routes start---------*/
	Route::get('salary/net/sheet/view',[FinalSalarySheetController::class,'index'])->name('finalSheetIndex');
	Route::get('salary/net/sheet/get/data',[FinalSalarySheetController::class,'getData'])->name('getFinalSheetData');
	Route::get('salary/net/sheet/generate',[FinalSalarySheetController::class,'generateFinalSalary'])->name('generateFinalSalary');
	Route::post('salary/net/sheet/store',[FinalSalarySheetController::class,'store'])->name('sheetDataStore');
	Route::get('salary/net/sheet/create',[FinalSalarySheetController::class,'create'])->name('finalSalarySheetAdd');
	Route::get('salary/Sheet/Delete/{id}',[FinalSalarySheetController::class,'delete'])->name('finalSalarySheetDelete');
	Route::get('salary/Sheet/View/{id}',[FinalSalarySheetController::class,'view'])->name('finalSalarySheetView');
	Route::get('salary/Sheet/generate/pdf/',[FinalSalarySheetController::class,'generateSalarySheetPdf'])->name('generateSalarySheetPdf');
	Route::get('salary/Sheet/instruction/check/',[FinalSalarySheetController::class,'checkSalaryInstruction'])->name('checkSalaryInstruction');
	
	
	
	/*-----Bonus List Routes start---------*/
	Route::get('salary/bonus/list/view',[BonusController::class,'index'])->name('bonusListView');
	Route::get('salary/bonus/get/list',[BonusController::class,'getBonusData'])->name('getBonusData');
	Route::post('salary/bonus/store',[BonusController::class,'store'])->name('bonusDataStore');
	Route::get('salary/bonus/edit',[BonusController::class,'edit'])->name('editBonusSheet');
	Route::post('salary/bonus/update',[BonusController::class,'update'])->name('bonusListUpdate');
	Route::post('salary/bonus/Delete',[BonusController::class,'delete'])->name('bonusListDelete');
	
	
	/*-----Employee Out Team Start---------*/
	Route::get('ourTeam',[OurTeamController::class,('index')])->name('ourTeam');
	Route::get('/ourTeam/add',[OurTeamController::class,('create')])->name('teamAdd');
	Route::match(['get','post'],'storeTeamMember',[OurTeamController::class,'store'])->name('storeTeamMember');
	Route::get('memberEdit/{member_id}',[OurTeamController::class, 'edit'])->name('memberEdit');
	Route::match(['get','post'],'UpdateOurTeam',[OurTeamController::class,'update'])->name('UpdateOurTeam');
	Route::get('/changeMemberStatus/{member_id}',[OurTeamController::class,'memberChangeStatus'])->name('changeMemberStatus');
	Route::get('/member/get/salary',[OurTeamController::class,'getSalary'])->name('getSalaryData');
	Route::get('/member/get/steps',[OurTeamController::class,'getSteps'])->name('loadSteps');

	/*-----Payroll Settings Start---------*/
	Route::get('/setting/index',[PayrollSettingController::class,'index'])->name('settingIndex');
	Route::get('/setting/activation/Status',[PayrollSettingController::class,'activationStatus'])->name('activationStatus');
	Route::post('/setting/deduct/update',[PayrollSettingController::class,'deductDayUpdate'])->name('deductDayUpdate');



	


	/* payroll Attendence */ 
	Route::get('/attendence/view',[AttendenceController::class,'index'])->name('attendenceIndex');
	Route::post('/attendence/store',[AttendenceController::class,'store'])->name('attendenceStore');
	Route::get('/attendence/get/entry/data',[AttendenceController::class,'getEntryData'])->name('getEntryData');
	Route::post('/attendence/exit/update',[AttendenceController::class,'exitUpdate'])->name('exitUpdate');

	/* Group Attendence */
	Route::get('/group/attendence/view',[AttendenceGroupWiseController::class,'index'])->name('groupAttendence');
	Route::get('/group/attendence/get/Group/MonthYear',[AttendenceGroupWiseController::class,'getGroupMonthYear'])->name('getGroupMonthYear');
	Route::get('/group/attendence/get/MonthYear/date/from/to',[AttendenceGroupWiseController::class,'getMonthYearDatesFromTo'])->name('getMonthYearDatesFromTo');
	

	/* Monthly Attendence */
	Route::get('/attendence/monthly/check',[AttendenceController::class,'monthlyAttendence'])->name('monthlyAttendence');
	Route::get('/attendence/monthly/get/attendence',[AttendenceController::class,'getMonthlyAttendence'])->name('getMonthlyAttendence');
	



	/* payroll Time Schedule*/
	Route::get('/time/schedule/index',[TimeGroupController::class,'index'])->name('timeScheduleGroupIndex');
	Route::get('/time/schedule/get/data',[TimeGroupController::class,'getScheduleGroupData'])->name('getScheduleGroupData');
	Route::post('/time/schedule/store',[TimeGroupController::class,'store'])->name('scheduleDataAdd');
	Route::post('/time/schedule/delete',[TimeGroupController::class,'delete'])->name('scheduleDataDelete');
	Route::get('/time/schedule/edit/data',[TimeGroupController::class,'edit'])->name('editScheduleGroup');
	Route::post('/time/schedule/update',[TimeGroupController::class,'update'])->name('scheduleGroupUpdate');


	/* payroll user schedule group */ 
	Route::get('/user/schedule/group/index',[UserTimeScheduleController::class,'index'])->name('userTimeGroupIndex');
	Route::get('/user/schedule/group/get/data',[UserTimeScheduleController::class,'getData'])->name('getUserScheduleGroupData');
	Route::post('/user/schedule/group/store',[UserTimeScheduleController::class,'store'])->name('getUserScheduleGroupStore');
	Route::get('/user/schedule/group/edit',[UserTimeScheduleController::class,'edit'])->name('getUserScheduleGroupEdit');
	Route::post('/user/schedule/group/update',[UserTimeScheduleController::class,'update'])->name('userScheduleGroupUpdate');
	Route::post('/user/schedule/group/delete',[UserTimeScheduleController::class,'delete'])->name('userScheduleGroupDelete');


	/* Leave Mangement system routes*/
	Route::get('leave/management/index',[LeaveController::class,'index'])->name('leaveIndex');
	Route::post('leave/management/store',[LeaveController::class,'store'])->name('leaveStore');
	Route::get('leave/management/get/data',[LeaveController::class,'getData'])->name('getLeaveData');
	Route::get('leave/management/get/edit',[LeaveController::class,'edit'])->name('leaveEdit');
	Route::post('leave/management/get/update',[LeaveController::class,'update'])->name('leaveUpdate');
	Route::post('leave/management/get/delete',[LeaveController::class,'delete'])->name('leaveDelete');

	});
        
         






	});
	
/*Payroll End*/






































