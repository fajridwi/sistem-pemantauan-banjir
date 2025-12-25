@extends('layouts.masyarakat')

@section('content')
<h1 class="text-xl font-bold mb-4">Daftar Laporan</h1>

<table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2">Judul</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($reports as $report)
        <tr class="border-t">
            <td class="p-2">{{ $report->title }}</td>
            <td>{{ $report->status }}</td>
            <td>{{ $report->created_at->format('d-m-Y') }}</td>
            <td>
                <a href="/reports/{{ $report->id }}"
                   class="text-blue-600">Detail</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
