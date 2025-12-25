@extends('layouts.pemerintah')

@section('title','Detail Laporan')

@section('content')

<h1 class="text-2xl font-bold mb-6 flex items-center gap-2 text-green-700">
    <i class="fa-solid fa-file-lines"></i>
    Detail Laporan
</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- INFORMASI LAPORAN --}}
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow space-y-4">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-user text-green-600"></i>
            <span class="font-semibold text-gray-700">Pelapor:</span>
        </div>
        <p class="text-gray-800 ml-6">{{ $report->user->name }}</p>

        <div class="flex items-center gap-2">
            <i class="fa-solid fa-file-lines text-green-600"></i>
            <span class="font-semibold text-gray-700">Judul Laporan:</span>
        </div>
        <p class="text-gray-800 ml-6">{{ $report->title }}</p>

        <div class="flex items-center gap-2">
            <i class="fa-solid fa-align-left text-green-600"></i>
            <span class="font-semibold text-gray-700">Deskripsi:</span>
        </div>
        <p class="text-gray-800 ml-6 leading-relaxed mt-1">{{ $report->description }}</p>

        <div class="flex items-center gap-2">
            <i class="fa-solid fa-location-dot text-green-600"></i>
            <span class="font-semibold text-gray-700">Alamat:</span>
        </div>
        <p class="text-gray-800 ml-6">{{ $report->address ?? '-' }}</p>

        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-green-600"></i>
            <span class="font-semibold text-gray-700">Status Saat Ini:</span>
        </div>
        @php
            $statusStyle = match($report->status) {
                'pending'  => 'bg-yellow-100 text-yellow-700',
                'selesai'  => 'bg-green-100 text-green-700',
                'batal'    => 'bg-red-100 text-red-700',
                default    => 'bg-gray-100 text-gray-600'
            };
        @endphp
        <span class="inline-block mt-1 ml-6 px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyle }}">
            {{ ucfirst($report->status) }}
        </span>

        {{-- FOTO --}}
        @if($report->photo)
            <div class="mt-4">
                <span class="font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fa-solid fa-image text-green-600"></i> Foto Bukti
                </span>
                <img src="{{ asset('storage/'.$report->photo) }}"
                     alt="Foto Laporan"
                     class="rounded-xl shadow max-h-96 object-contain border mt-2">
            </div>
        @else
            <p class="text-gray-400 italic mt-2 ml-6">
                Tidak ada foto yang diunggah
            </p>
        @endif

        {{-- PETA --}}
        @if($report->latitude && $report->longitude)
            <div class="mt-4">
                <span class="font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-green-600"></i> Lokasi Laporan
                </span>
                <div id="report-map" class="w-full h-64 rounded-xl border mt-2"></div>
            </div>
        @endif

    </div>

    {{-- UPDATE STATUS --}}
    <div class="bg-white p-6 rounded-xl shadow h-fit space-y-4">
        <h2 class="font-semibold mb-2 flex items-center gap-2 text-green-700">
            <i class="fa-solid fa-pen-to-square"></i> Update Status
        </h2>

        <form method="POST" action="{{ route('admin.reports.updateStatus',$report) }}" class="space-y-3">
            @csrf

            <label class="block font-semibold text-gray-700" for="status">
                <i class="fa-solid fa-circle-notch text-green-600"></i> Pilih Status:
            </label>
            <select name="status"
                    id="status"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-green-200">
                <option value="pending"  {{ $report->status=='pending'?'selected':'' }}>Pending</option>
                <option value="selesai"  {{ $report->status=='selesai'?'selected':'' }}>Selesai</option>
                <option value="batal"    {{ $report->status=='batal'?'selected':'' }}>Batal</option>
            </select>

            <button type="submit"
                class="w-full bg-green-600 text-white py-2 rounded-xl hover:bg-green-700 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </form>

        <a href="{{ route('admin.reports') }}"
           class="block text-center text-sm text-gray-500 mt-2 hover:underline">
            ← Kembali ke daftar laporan
        </a>
    </div>

</div>

@endsection

@push('scripts')
@if($report->latitude && $report->longitude)
<script>
    const map = L.map('report-map').setView([{{ $report->latitude }}, {{ $report->longitude }}], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    L.marker([{{ $report->latitude }}, {{ $report->longitude }}])
        .addTo(map)
        .bindPopup("<strong>{{ $report->title }}</strong><br>{{ $report->address ?? '' }}")
        .openPopup();
</script>
@endif
@endpush
