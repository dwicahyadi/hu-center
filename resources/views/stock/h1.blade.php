@extends('layouts.app')

@section('content')

    @include('stock._nav',['navs'=> ['Stock']])
    <div class="card">
        <div class="card-body">
            <strong>Warehouse</strong>
            <h3>
                {{ \Illuminate\Support\Facades\Auth::user()->cluster }} /
                {{ \Illuminate\Support\Facades\Auth::user()->micro_cluster }} /
                {{ \Illuminate\Support\Facades\Auth::user()->city }}
            </h3>
            <hr>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('stock.hu2') }}">Warehouse</a></li>
                <li class="breadcrumb-item active">{{ $hu2_no }}</li>
            </ol>
        </div>
    </div>
    <div style="display: none">
        <form  action="{{ route('stock.list') }}" method="get" class="form-inline p-2">
            <div class="form-group mr-4">
                <label class="mr-4">Urutkan Berdasar</label>
                <select name="order" class="form-control mx-2">
                    <option value="">Default</option>
                    @foreach($thead as $order)
                        @php($value = \Illuminate\Support\Str::snake(strtolower($order)))
                        <option @if( request('order') == $value) selected
                                @endif value="{{ $value }}">{{ $order }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mr-4">
                <label class="mr-4">Status</label>
                <select name="status" class="form-control mx-2">
                    @foreach($statuses as $status)
                        <option @if( request('status') == $status) selected
                                @endif value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mr-4">
                <button class="btn btn-sm btn-primary">Tampilkan</button>
            </div>
        </form>
    </div>

    @if(request('s'))
        <div class="bg-warning p-2">
            <a href="{{ route('stock.search', request()->all()) }}">Edit Parameter Pencarian</a>
        </div>
    @endif
    <div class="p-2 d-flex justify-content-between">
        @php($current = $data->perPage() * $data->currentPage())
        {{ number_format($current - $data->perPage() ) }} - {{ number_format($current) }} dari {{ number_format($data->total()) }} {{ $data->links() }}
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped bg-white">
            <thead class="thead-dark sticky-top position-sticky">
            <tr>
                <th width="5rem">Option</th>
                @foreach($thead as $head)
                    <th>{{ $head }}</th>
                @endforeach
            </tr>
            </thead>

            <tbody style="height: 75vh; overflow: auto">

            @forelse($data as $row)
                <tr>
                    <td>
                        <a href="{{ route('stock.box',['hu2_no'=>$hu2_no, 'hu1_no'=>$row->hu1_no]) }}"><i class="icon-th-list mx-2 text-info" title="Open Detail"></i> Open</a>
                    </td>
                    @foreach($thead as $head)
                        <td>{{ $row->{\Illuminate\Support\Str::snake(strtolower($head))} }}</td>
                    @endforeach
                </tr>
            @empty

            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-2 d-flex justify-content-between">
        @php($current = $data->perPage() * $data->currentPage())
        {{ number_format($current - $data->perPage() ) }} - {{ number_format($current) }} dari {{ number_format($data->total()) }} {{ $data->links() }}
    </div>

@endsection
