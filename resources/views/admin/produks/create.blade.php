@extends('layouts.admin')

@section('content')


<div class="card p-4">
    <h3> Tambah Produk 
    <a href="{{url ('admin/produks') }}" class="btn btn-warning float-end rounded-pill">Kembali</a>           
    </h3>   
    <div class="shadow p-4 mt-4 m-2 card-header">
        <form action="/admin/produks" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama" class="form-control"/>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-select mt-1">
                    <option selected>PILIH KATEGORI</option>
                    @foreach($categories as $category )
                    <option value="{{ $category->id }}">{{ $category->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="text" name="stok" class="form-control"/>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="text" name="harga" class="form-control"/>
            </div>
            <div class=" mb-3">
                <label class="form-label">Foto</label>
                <input type="file" name="image" class="form-control"/ >
                </div>
            <div class="mb-3">
                <label class="form-label">Keterangan Produk</label>
                <input type="text" name="keterangan" class="form-control"/>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi Produk</label>
                <input type="text" name="deskripsi" class="form-control"/>
            </div>
            <button class="btn btn-warning float-end mt-3 rounded-pill">Submit</button>
        </form>    
    </div>
</div>

@endsection