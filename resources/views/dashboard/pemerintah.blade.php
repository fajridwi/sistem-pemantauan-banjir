@extends('layouts.pemerintah')

@section('title', 'Dashboard Pemerintah')

@section('content')

<h1 class="text-2xl font-bold mb-6">
    Dashboard Pemerintah
</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

    {{-- Total Laporan --}}
    <div class="bg-gradient-to-r from-blue-400 to-blue-600 text-white p-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-300 flex items-center gap-4">
        <i class="fa-solid fa-file-lines text-4xl"></i>
        <div>
            <p class="text-white/80 font-semibold">Total Laporan</p>
            <h2 class="text-3xl font-bold">{{ $total_laporan ?? 0 }}</h2>
        </div>
    </div>

    {{-- Pending --}}
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white p-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-300 flex items-center gap-4">
        <i class="fa-solid fa-hourglass-half text-4xl"></i>
        <div>
            <p class="text-white/80 font-semibold">Pending</p>
            <h2 class="text-3xl font-bold">{{ $laporan_pending ?? 0 }}</h2>
        </div>
    </div>

    {{-- Batal --}}
    <div class="bg-gradient-to-r from-red-400 to-red-600 text-white p-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-300 flex items-center gap-4">
        <i class="fa-solid fa-ban text-4xl"></i>
        <div>
            <p class="text-white/80 font-semibold">Batal</p>
            <h2 class="text-3xl font-bold">{{ $laporan_batal ?? 0 }}</h2>
        </div>
    </div>

    {{-- Selesai --}}
    <div class="bg-gradient-to-r from-green-400 to-green-600 text-white p-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-300 flex items-center gap-4">
        <i class="fa-solid fa-check-circle text-4xl"></i>
        <div>
            <p class="text-white/80 font-semibold">Selesai</p>
            <h2 class="text-3xl font-bold">{{ $laporan_selesai ?? 0 }}</h2>
        </div>
    </div>

</div>

{{-- PETA --}}
<div class="bg-white p-6 rounded shadow mb-8">
    <h2 class="font-semibold mb-4">
        Persebaran Laporan Banjir
    </h2>

    <div id="map" class="w-full h-[400px] rounded"></div>
</div>

{{-- QUICK ACTION --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="bg-white p-6 rounded shadow">
        <h3 class="font-semibold mb-3">
            <i class="fa-solid fa-inbox mr-2"></i>
            Laporan Terbaru
        </h3>

        <p class="text-gray-500">
            Kelola laporan masyarakat yang masuk dan tentukan status penanganannya.
        </p>

        <a href="{{ route('admin.reports') }}"
           class="inline-block mt-4 bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">
            Lihat Laporan Masuk
        </a>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h3 class="font-semibold mb-3">
            <i class="fa-solid fa-chart-pie mr-2"></i>
            Statistik & Analisis
        </h3>

        <p class="text-gray-500">
            Analisis tren banjir dan efektivitas penanganan.
        </p>

        <button
            class="inline-block mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Lihat Statistik
        </button>
    </div>

</div>

@endsection

@push('scripts')
<script>
const map = L.map('map').setView([-6.2, 106.8], 11);

// Tile layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

// Semua laporan
const reports = @json($reportMarkers);

// Tambahkan marker untuk semua laporan
reports.forEach(r => {
    L.marker([r.lat, r.lng])
        .addTo(map)
        .bindPopup(`
            <b>${r.title}</b><br>
            Alamat: ${r.address ?? '-'}<br>
            Status: ${r.status ?? '-'}
        `);
});

// Fit bounds otomatis agar semua marker terlihat
if(reports.length) {
    const allCoords = reports.map(r => [r.lat, r.lng]);
    map.fitBounds(allCoords, { padding: [50, 50] });
}
</script>
@endpush
