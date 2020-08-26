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
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Form Pengeluaran Stok</h3>
                <form method="POST" onkeydown="return event.key != 'Enter';" id="form-out">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label>Cari Menggunakan</label>
                                <select name="field" id="" class="form-control">
                                    <option value="hu1_no">HU1 No</option>
                                    <option value="hu2_no">HU2 No</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Scan/Input</label>
                                <input type="text" id="key" name="key" class="form-control" placeholder="scan atau ketik kemudian Enter" required>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary  btn-block my-4">Keluarkan Stok Dari Warehouse</button>
                                <label><input type="checkbox" id="auto-submit"> Auto-Submit</label>
                            </div>

                        </div>

                        <div class="col-md-3">
                            <div class="bg-info text-white p-2 rounded">
                                <p>Ketika <strong>Auto-Submit</strong> aktifkan, maka setelah berhasil scan Stok akan otomatis dileuarkan tanpa harus klik Tombol</p>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
        <div class="card card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped" id="tbl-item">
                        <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>HU1 No</th>
                            <th>Item Code</th>
                            <th>Prima ERP Item Name</th>
                            <th>Prod Code</th>
                            <th>Exp Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function buildTable(result){
            if (!result.length) return
            var html = '';
            $.each(result, function (key, value) {
                html += '<tr>';
                html += '<td>'+value.id+'</td>';
                html += '<td>'+value.hu1_no+'</td>';
                html += '<td>'+value.item_code+'</td>';
                html += '<td>'+value.prima_erp_item_name+'</td>';
                html += '<td>'+value.production_code+'</td>';
                html += '<td>'+value.expire_date+'</td>';
                html += '</tr>';
            });
            return html;
        }
        $('#key').bind("enterKey",function(e){
            $('#tbl-item tbody').html('');
            $.ajax({
                url: '{{ route('ajax.stock.search') }}',
                type: 'get',
                data: $('#form-out').serialize(),
                success: function (result){
                    if(result.length)
                    {
                        html = buildTable(result);
                        $('#tbl-item tbody').html(html);
                        if($('#auto-submit').is(":checked")) $('#form-out').submit()
                    }else{
                        alert('Tidak ditemukan');
                        $('#key').val('')
                    }
                },
                error: function (code, type, error){
                    alert(error)
                }
            })
        });
        $('#key').keyup(function(e){
            if(e.keyCode == 13)
            {
                $(this).trigger("enterKey");
            }
        });
        $('#form-out').submit(function (e){
            e.preventDefault();
            $.ajax({
                url: '{{ route('ajax.stock.outToCanvasser') }}',
                type: 'post',
                data: $('#form-out').serialize(),
                success: function (result){
                    alert('Sukses Mengeluarkan Stok');
                    $('#key').val('')
                    $('#key').focus();
                },
                error: function (code, type, error){
                    alert(error)
                }
            })

        })
    </script>
@endpush
