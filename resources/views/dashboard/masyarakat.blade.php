@extends('layouts.masyarakat')
@section('title','Dashboard Masyarakat')
@section('content')

<h1 class="text-2xl font-bold mb-6">Dashboard Masyarakat</h1>

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    {{-- Total Laporan --}}
    <div class="bg-blue-100 p-6 rounded-xl shadow flex items-center gap-4 hover:shadow-lg transition">
        <i class="fa-solid fa-inbox text-4xl text-blue-600"></i>
        <div>
            <p class="text-gray-700 font-medium">Total Laporan</p>
            <h2 class="text-3xl font-bold">{{ $total_laporan ?? 0 }}</h2>
        </div>
    </div>

    {{-- Wilayah Rawan --}}
    <div class="bg-red-100 p-6 rounded-xl shadow flex items-center gap-4 hover:shadow-lg transition">
        <i class="fa-solid fa-map-location-dot text-4xl text-red-600"></i>
        <div>
            <p class="text-gray-700 font-medium">Wilayah Rawan</p>
            <h2 class="text-3xl font-bold">{{ $wilayah_rawan_count ?? 0 }}</h2>
        </div>
    </div>

    {{-- Potensi Banjir --}}
    <div class="bg-orange-100 p-6 rounded-xl shadow flex items-center gap-4 hover:shadow-lg transition">
        <i class="fa-solid fa-water text-4xl text-orange-600"></i>
        <div>
            <p class="text-gray-700 font-medium">Potensi Banjir</p>
            <h2 class="text-3xl font-bold">{{ $potensi_banjir_percent ?? 0 }}%</h2>
        </div>
    </div>

</div>

{{-- DAFTAR WILAYAH RAWAN --}}
@if($wilayah_rawan_count)
<div class="bg-white p-6 rounded-xl shadow mb-6">
    <h2 class="font-semibold mb-4 text-lg">Daftar Wilayah Rawan</h2>
    <ul class="list-disc pl-5 text-gray-700 space-y-1">
        @foreach($wilayah_rawan as $w)
            <li>{{ $w['address'] ?? 'Wilayah Rawan' }} ({{ $w['count'] ?? 0 }} laporan)</li>
        @endforeach
    </ul>
</div>
@else
<div class="bg-white p-6 rounded-xl shadow mb-6 text-center text-gray-500">
    Belum ada wilayah rawan.
</div>
@endif

{{-- PETA --}}
<div class="bg-white p-6 rounded-xl shadow">
    <h2 class="font-semibold mb-4 text-lg">Persebaran Banjir</h2>
    <div id="map" class="w-full h-[400px] rounded"></div>
</div>

@endsection

@push('scripts')
<script>
const map = L.map('map').setView([-6.2, 106.8], 11);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    attribution: '© OpenStreetMap'
}).addTo(map);

const reports = @json($reportMarkers ?? []);
const rawan = @json($wilayah_rawan ?? []);

// Tambahkan circle merah untuk wilayah rawan
rawan.forEach(w => {
    L.circle([w.lat, w.lng], {
        color: 'red',
        fillColor: '#f87171',
        fillOpacity: 0.3,
        radius: 100
    }).addTo(map)
    .bindPopup(`<b>${w.address}</b>`);
});

// Tambahkan marker untuk semua laporan
reports.forEach(r => {
    L.marker([r.lat, r.lng])
     .addTo(map)
     .bindPopup(`<b>${r.title}</b><br>${r.address ?? '-'}`);
});

// Fit bounds ke semua marker jika ada
if(reports.length){
    const allCoords = reports.map(r=>[r.lat,r.lng]);
    map.fitBounds(allCoords,{padding:[50,50]});
}
</script>
@endpush
