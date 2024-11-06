@extends('layout.master')

@section('title', 'User List')

@section('content')
    <h2 class="text-center mb-4">User List</h2>
    <table class="table table-striped table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Photo</th>
                <th>Name</th>
                <th>Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_pengguna as $index => $pengguna)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if (is_null($pengguna->photo))
                            Not Available
                        @else
                            <img src="{{ asset('storage/images/users/square/' . $pengguna->photo . '_Square.' . $pengguna->photo_ext) }}"
                                alt="User Photo" width="200">
                        @endif
                    </td>
                    <td>{{ $pengguna->name }}</td>
                    <td>{{ $pengguna->level }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
