@extends('layout.master')

@section('title', 'Edit Profile')

@section('content')
    <h2 class="text-center mb-4">Edit Profile</h2>

    <form action="{{ route('users.update', $pengguna->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <!-- Untuk metode update -->

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $pengguna->name) }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{ old('email', $pengguna->email) }}" required>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Photo Profile</label>
            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo"
                value="{{ old('photo') }}">
            @error('photo')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
@endsection
