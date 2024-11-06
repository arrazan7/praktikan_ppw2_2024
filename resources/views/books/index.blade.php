@extends('layout.master')

@section('title', 'Book List')

@section('content')
    <h2 class="text-center mb-4">
        Book List</h2>
    <form action="{{ route('books.search') }}" method="GET">
        @csrf
        <input type="text" name="search" class="form-control mb-2" placeholder="Search Book">
    </form>
    <a href="{{ route('books.create') }}" class="btn btn-primary float-end mb-2">Add Book</a>
    <table class="table table-striped table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Writer</th>
                <th>Picture</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_buku as $index => $buku)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $buku->title }}</td>
                    <td>{{ $buku->writer }}</td>
                    <td>
                        @if (is_null($buku->picture))
                            Not Available
                        @else
                            <img src="{{ asset('storage/images/books/' . $buku->picture) }}" alt="Book Picture"
                                width="300">
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('books.show', $buku->id) }}">
                            <button type="submit" class="btn btn-primary">Detail</button>
                        </form>
                        <form action="{{ route('books.edit', $buku->id) }}">
                            <button type="submit" class="btn btn-warning">Edit</button>
                        </form>
                        <form action="{{ route('books.destroy', $buku->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin mau dihapus?')" type="submit"
                                class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tampilkan pagination links -->
    <div class="d-flex justify-content-center">
        {{ $data_buku->links() }}
    </div>

    <!-- Menampilkan jumlah buku dan total harga -->
    <div class="row mt-3">
        <div class="col-md-6">
            <p><strong>Total Books:</strong> {{ $jumlah_buku }}</p>
        </div>
        <div class="col-md-6 text-end">
            <p><strong>Total Price:</strong> {{ 'Rp. ' . number_format($total_harga_buku, 2, ',', '.') }}</p>
        </div>
    </div>
@endsection
