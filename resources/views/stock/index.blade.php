@extends('layouts.app')

@section('content')

    @include('stock._nav',['navs'=> ['Stock']])

    <div class="container-fluid">
        <nav class="breadcrumb my-2">
            @forelse($nav as $item => $link)
                <span class="breadcrumb-item"><a href="{{ $link }}">{{ $item }}</a></span>
            @empty

            @endforelse
            <span class="breadcrumb-item active">{{ $pageTitle ?? 'Warehouse' }}</span>
        </nav>
        <div class="d-flex justify-content-between">
            @php($current = $data->perPage() * $data->currentPage())
            {{ number_format($current - $data->perPage() ) }} - {{ number_format($current) }} dari {{ number_format($data->total()) }} {{ $data->links() }}
        </div>

        <div class="table-responsive">
            <table class="table table-responsive table-bordered table-hover table-striped bg-white">
                <thead class="thead-dark sticky-top position-sticky">
                <tr>

                    @foreach($thead as $head)
                        <th>{{ $head }}</th>
                    @endforeach
                    <th>Qty</th>
                    <th width="5rem">Option</th>
                </tr>
                </thead>

                <tbody style="height: 75vh; overflow: auto">
                @forelse($data as $row)
                    <tr>
                        @foreach($thead as $head)
                            <td>{{ $row->{\Illuminate\Support\Str::snake(strtolower($head)) } }}</td>
                        @endforeach
                        <td>
                            {{ number_format($row->qty ?? 1) }}
                        </td>
                        <td>
                            <?php $target = $row->$targetField ? $row->$targetField : '-' ?>
                            <a href="{{ Request::url()."/$target"}}" class="btn btn-sm btn-primary">Select</a>
                        </td>
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
    </div>


@endsection
