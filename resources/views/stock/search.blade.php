@extends('layouts.app')

@section('content')
    @include('stock._nav',['navs'=> ['Stock', 'Search']])

    <div class="container-fluid py-4">
        <div class="card card-body">
            <form method="post" action="{{ route('stock.postSearch') }}" enctype="multipart/form-data">
                @csrf
                <table class="table table-bordered table-hover table-striped bg-white w-50">
                    <thead class="thead-dark">
                    <tr>
                        <th>Field</th>
                        <th>Operator</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    @foreach($fields as $field)
                        @php($name = \Illuminate\Support\Str::snake(strtolower($field)))
                        <tr>
                            <input type="hidden" name="s" value="1">
                            <td><label>{{ $field }}</label></td>
                            <td><select name="operator-{{ $name }}" id="">
                                    <option value="=">=</option>
                                    <option value="!=">!=</option>
                                    <option value="like">like</option>
                                </select>
                            </td>
                            <td><input type="text" name="{{ $name }}" class="form-control" value="{{ @request($name) }}"></td>
                        </tr>
                    @endforeach
                </table>
                <hr>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="icon-ok"></i> Cari</button>
                </div>

            </form>
        </div>
    </div>
@endsection
