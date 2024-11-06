@extends('layout.master')

@section('title', 'Edit Book')

@section('content')
    <h2 class="text-center mb-4">Edit Book</h2>

    <form action="{{ route('books.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Untuk metode update -->

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $buku->title) }}">
            @error('title')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="writer" class="form-label">Writer</label>
            <input type="text" class="form-control" id="writer" name="writer"
                value="{{ old('writer', $buku->writer) }}">
            @error('writer')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="publisher_id" class="form-label">Publisher</label>
            <select class="form-control" id="publisher_id" name="publisher_id" required>
                <option value="">Select Publisher</option>
                @foreach ($publishers as $publisher)
                    <option value="{{ $publisher->id }}"
                        {{ old('publisher_id', $buku->publisher_id) == $publisher->id ? 'selected' : '' }}>
                        {{ $publisher->name }}
                    </option>
                @endforeach
            </select>
            @error('publisher_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="publication_year" class="form-label">Publication Year</label>
            <input type="text" class="form-control" id="publication_year" name="publication_year"
                value="{{ old('publication_year', $buku->publication_year) }}">
            @error('publication_year')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="number_of_pages" class="form-label">Number of Pages</label>
            <input type="text" class="form-control" id="number_of_pages" name="number_of_pages"
                value="{{ old('number_of_pages', $buku->number_of_pages) }}">
            @error('number_of_pages')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="text" class="form-control" id="price" name="price"
                value="{{ old('price', $buku->price) }}">
            @error('price')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $buku->description) }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="picture" class="form-label">Book Picture</label>
            <input type="file" class="form-control @error('picture') is-invalid @enderror" id="picture" name="picture"
                value="{{ old('picture') }}">
            @error('picture')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Book</button>
    </form>
@endsection
