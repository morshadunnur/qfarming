<?php
    use Carbon\Carbon;
?>
@extends('template.app')

@section('title', 'Update - Bank')

@push('css')
   
@endpush

@section('content')
    <div class="page-bar">
        <div class="page-title-breadcrumb">
            
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-6">
            <div class="card card-box">
                <div class="card-head text-white " style="background-color:#3FCC7E;">
                    <header>Update Bank</header>
                </div>
                <div class="card-body " id="bar-parent">
                    <form method="post" action="{{ route('admin.bank.update', $bank->id) }}">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="simpleFormEmail">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" id="simpleFormEmail" 
                            value="{{ $bank->bank_name }}">
                        </div>
                        <div class="form-group">
                            <label for="simpleFormEmail">Account Name</label>
                            <input type="text" name="account_name" class="form-control" id="simpleFormEmail" 
                            value="{{ $bank->account_name }}">
                        </div><div class="form-group">
                            <label for="simpleFormEmail">Account Number</label>
                            <input type="text" name="account_number" class="form-control" id="simpleFormEmail" 
                            value="{{ $bank->account_number }}">
                        </div>
                        <div class="form-group">
                            <label for="simpleFormEmail">Opening Balance</label>
                            <input type="text" name="opening_balance" class="form-control" id="simpleFormEmail" 
                            value="{{ $bank->opening_balance }}">
                        </div>
                        
                        <a class="btn btn-danger m-t-15 waves-effect" href="{{ route('admin.bank.index') }}">BACK</a>
                        <button type="submit" class="btn btn-success m-t-15 waves-effect">UPDATE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    
@endpush
