@extends('layout.master')

@section('title', 'Book Gallery')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <div class="row">
                        @if (count($galleries) > 0)
                            @foreach ($galleries as $gallery)
                                <div class="col-sm-2">
                                    <div>
                                        <a class="example-image-link" href="{{ 'storage/images/books/' . $gallery->picture }}"
                                            data-lightbox="roadtrip" data-title="{{ $gallery->description }}">
                                            <img class="example-image img-fluid mb-2"
                                                src="{{ asset('storage/images/books/' . $gallery->picture) }}"
                                                alt="image-gallery" width="200" />
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h3>Tidak ada data.</h3>
                        @endif
                        <div class="d-flex">
                            {{ $galleries->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
