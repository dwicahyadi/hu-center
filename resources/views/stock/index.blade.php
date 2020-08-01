@extends('layouts.app')

@section('content')

    @include('stock._nav',['navs'=> ['Stock']])
    <div>
        <form action="{{ route('stock.list') }}" method="get" class="form-inline p-2">
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
                <th>Option</th>
                @foreach($thead as $head)
                    <th>{{ $head }}</th>
                @endforeach
            </tr>
            </thead>

            <tbody style="height: 75vh; overflow: auto">
            @forelse($data as $row)
                <tr>
                    <td>
                        <a href=""><i class="icon-pencil mx-2 text-info" title="Edit"></i></a>
                        <a href=""><i class="icon-doc mx-2 text-secondary" title="Log"></i></a>
                        <a href=""><i class="icon-trash mx-2 text-danger" title="Hapus"></i></a>
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
