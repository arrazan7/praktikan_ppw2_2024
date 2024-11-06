@extends('layout.master')

@section('title', 'Create Book')

@section('content')
    <h2 class="text-center mb-4">Create New Book</h2>

    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="writer" class="form-label">Writer</label>
            <input type="text" class="form-control" id="writer" name="writer" value="{{ old('writer') }}" required>
            @error('writer')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="publisher_id" class="form-label">Publisher</label>
            <select class="form-control" id="publisher_id" name="publisher_id" required>
                <option value="">Select Publisher</option>
                @foreach ($publishers as $publisher)
                    <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                        {{ $publisher->name }}
                    </option>
                @endforeach
            </select>
            @error('publisher_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="publication_year" class="form-label">Publication Year</label>
            <input type="number" class="form-control" id="publication_year" name="publication_year"
                value="{{ old('publication_year') }}" required>
            @error('publication_year')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="number_of_pages" class="form-label">Number of Pages</label>
            <input type="number" class="form-control" id="number_of_pages" name="number_of_pages"
                value="{{ old('number_of_pages') }}" required>
            @error('number_of_pages')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
            @error('price')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
            @error('description')
                <small class="text-danger">{{ $message }}</small>
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

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
