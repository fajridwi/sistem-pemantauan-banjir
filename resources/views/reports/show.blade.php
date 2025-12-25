@extends('layouts.masyarakat')

@section('content')
<h1 class="text-xl font-bold mb-4">{{ $report->title }}</h1>

<div class="bg-white p-6 rounded shadow">
    <p><b>Deskripsi:</b> {{ $report->description }}</p>
    <p><b>Status:</b> {{ $report->status }}</p>
    <p><b>Alamat:</b> {{ $report->address }}</p>

    @if($report->photo)
        <img src="{{ asset('storage/'.$report->photo) }}"
             class="mt-4 rounded">
    @endif
</div>
@endsection
