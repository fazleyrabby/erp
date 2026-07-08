@extends('admin.master')
@section('title')
Admin Salary Sheet Intruction Add -View
@endsection

@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header py-3">
                            <h3 class="text">Add Salary Instruction
                                @if (session('status'))
                                <span class=" alert alert-success">
                                    {{ session('status') }}
                                </span>
                                @endif
                            </h3>
                        </div>
                        <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                        <form id="SalaySheetFormStore" action="{{route('sheetInstructionStore')}}" method="post" enctype="multipart/form-data" >
                            @csrf
                            <div class="form-group mb-3 row col-md-12">
                                <div class="col-md-6">
                                    <label for="carousalCaptionOffer">Month Year</label>
                                    <select class="form-control" id="month_year" name="month_year">
                                        @php
                                        $inc = 36;
                                        for($i = 0; $i < 36; $i++)
                                        {
                                        echo '<option>'.Date(SESSION::get('companySettings')[0]['month_year'], strtotime(Date("Y-m-d").' '.$i.' Month -1 Day')).'</option>';
                                        }   
                                        @endphp
                                    </select>
                                    <span class="text-danger" id="month_yearError"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="carousalCaptionOffer">Sheet Name</label>
                                    <select class="form-control" id="sheet_id" name="sheet_id" required>
                                        <option value="" selected disabled>Choose Sheet</option>
                                        @foreach($sheets as $sheet)
                                        <option value="{{$sheet->id}}">{{$sheet->sheet_name}}</option>
                                        @endforeach                                   
                                    </select>        
                                    <span class="text-danger" id="sheet_idError"></span>
                                </div>
                            </div>
                            <div class="form-group mb-3 row col-md-12">
                                <div class="col-md-6">
                                    <label for="carousalCaptionOffer">Bank Name</label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder=" Write Bank Name" >                                     
                                    <span class="text-danger" id="bank_nameError"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="carousalCaptionOffer">Branch Name</label>
                                    <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder=" Write Branch Name" >                                     
                                    <span class="text-danger" id="branch_nameError"></span>
                                </div>
                            </div>
                            <div class="form-group mb-3 row col-md-12">
                                <div class="col-md-6">
                                    <label for="carousalCaptionOffer">Account No</label>
                                    <input type="text" class="form-control" id="mother_account_no" name="mother_account_no" placeholder=" Write Account Number">                                     
                                    <span class="text-danger" id="mother_account_noError"></span>
                                </div>
                            </div>
                            <div class="form-group mb-3 row col-md-12">
                                <div class="col-md-6">
                                    <label>Footer Description</label>
                                    <textarea  class="form-control ckeditor" id="contentDescriptionCkeditor" name="footer_instruction"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label >Letter Body</label>
                                    <textarea  class="form-control ckeditor" id="contentDescriptionCkeditor22" name="letter_body"></textarea>
                                </div>
                            </div>
                            <div class="form-group mb-3 row col-md-12">
                                <div class="col-md-12">
                                <button type="submit" class="btn btn-primary float-right " id="saveSheet"><i class="fa fa-save"></i> Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection