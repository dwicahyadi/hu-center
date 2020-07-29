@extends('layouts.app')

@section('content')
    @include('stock._nav',['navs'=> ['Stock', 'Stock In']])
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <div class="container-fluid py-4">
        <div class="card card-body">
            <form method="POST" action="{{ route('stock.stock_in') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-md-3">
                        <label for="user">User</label>
                        <input type="text" name="user" id="user" class="form-control" readonly value="{{ Auth::user()->name }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="cluster">Cluster</label>
                        <input type="text" name="cluster" id="cluster" class="form-control" readonly value="{{ Auth::user()->cluster }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="micro-cluster">Micro Cluster</label>
                        <input type="text" name="micro_cluster" id="micro-cluster" class="form-control" readonly value="{{ Auth::user()->micro_cluster }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="city">City</label>
                        <input type="text" name="city" id="city" class="form-control" readonly value="{{ Auth::user()->city }}">
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label class="clearfix">Source</label>
                    <label class="mx-4"><input type="radio" name="source" value="po" required> PO XL</label>
                    <label class="mx-4"><input type="radio" name="source" value="transfer" required> Transfer dari warehouse lain</label>
                </div>
                <hr>
                <div class="form-group w-25">
                    <label for="erp_item">ERP Item Name</label>
                    <x-select-erp-item name="erp_item" />
                </div>
                <hr>
                <div class="form-group">
                    <label class="mr-4">Upload List HU</label>
                    <input type="file" name="file" required>
                    <br>
                    <span class="text-muted">
                        <i class="icon-info text-info"></i> Format file excel silakan <a href="" class="font-weight-bold ">download di sini</a></span>
                </div>
                <hr>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="icon-ok"></i> Simpan</button>
                </div>

            </form>
        </div>
    </div>
@endsection
