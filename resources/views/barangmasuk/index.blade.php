@extends('layouts.adm-main')


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <div class="pull-left">
		    <h2>DAFTAR BARANG MASUK</h2>
		</div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @elseif ($message = Session::get('error'))
             <div class="alert alert-danger">
                 <p>{{ $message }}</p>
            </div>
        @endif
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('barangmasuk.create') }}" class="btn btn-md btn-success mb-3">TAMBAH ITEM</a>
                    </div>

                </div>


                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>TGL MASUK</th>
                            <th>QTY MASUK</th>
                            <th>BARANG</th>
                            <th style="width: 15%">AKSI</th>


                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rsetBarang as $rowbarang)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $rowbarang->tgl_masuk  }}</td>
                                <td>{{ $rowbarang->qty_masuk  }}</td>
                                <td>{{ $rowbarang->barang-> merk  }}</td>
                                
                                <td class="text-center">
                                    <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('barangmasuk.destroy', $rowbarang->id) }}" method="POST">
                                        <a href="{{ route('barangmasuk.show', $rowbarang->id) }}" class="btn btn-sm btn-dark"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('barangmasuk.edit', $rowbarang->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <div class="alert">
                                Data barang masuk belum tersedia!
                            </div>
                        @endforelse
                    </tbody>
                   
                </table>
                {!! $rsetBarang->links('pagination::bootstrap-5') !!}

            </div>
        </div>
    </div>
@endsection