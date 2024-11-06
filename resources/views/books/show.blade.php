@extends('layout.master')

@section('title', 'Book Details')

@section('content')
    <h2 class="text-center mb-4">Book Details</h2>
    <h3>Title</h3>
    <p class="mb-3">{{ $buku->title }}</p>
    <h3>Writer</h3>
    <p class="mb-3">{{ $buku->writer }}</p>
    <h3>Penerbit</h3>
    <p class="mb-3">{{ $penerbit->name }}</p>
    <h3>Tahun Terbit</h3>
    <p class="mb-3">{{ $buku->publication_year }}</p>
    <h3>Jumlah Halaman</h3>
    <p class="mb-3">{{ $buku->number_of_pages }}</p>
    <h3>Deskripsi</h3>
    <p class="mb-3">{{ $buku->description }}</p>
    <h3>Price</h3>
    <p class="mb-3">{{ 'Rp. ' . number_format($buku->price, 2, ',', '.') }}</p>
    <h3>Picture</h3>
    @if (is_null($buku->picture))
        <p class="mb-3">Not Available</p>
    @else
        <img src="{{ asset('storage/images/books/' . $buku->picture) }}" alt="Book Picture" width="500">
    @endif

    <form action="{{ route('books.edit', $buku->id) }}">
        <button type="submit" class="btn btn-warning">Edit</button>
    </form>
    <form action="{{ route('books.destroy', $buku->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button onclick="return confirm('Yakin mau dihapus?')" type="submit" class="btn btn-danger">Delete</button>
    </form>
@endsection
