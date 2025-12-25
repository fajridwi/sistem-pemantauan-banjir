@extends('layouts.pemerintah')

@section('title','Laporan Masuk')

@section('content')

<h1 class="text-2xl font-bold mb-6 flex items-center gap-2 text-green-700">
    <i class="fa-solid fa-inbox"></i>
    Laporan Masuk
</h1>

<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-green-100 text-green-800 uppercase text-xs tracking-wider">
            <tr>
                <th class="p-4 text-center"><i class="fa-solid fa-user"></i> Pelapor</th>
                <th class="p-4 text-center"><i class="fa-solid fa-envelope"></i> Email</th>
                <th class="p-4 text-center"><i class="fa-solid fa-file-lines"></i> Judul</th>
                <th class="p-4 text-center"><i class="fa-solid fa-location-dot"></i> Alamat</th>
                <th class="p-4 text-center"><i class="fa-solid fa-calendar-days"></i> Tanggal</th>
                <th class="p-4 text-center"><i class="fa-solid fa-circle-check"></i> Status</th>
                <th class="p-4 text-center w-36"><i class="fa-solid fa-gear"></i> Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($reports as $report)
            <tr class="border-t hover:bg-green-50 transition">
                {{-- Pelapor --}}
                <td class="p-4 font-medium text-center">{{ $report->user->name ?? '-' }}</td>

                {{-- Email --}}
                <td class="p-4 text-gray-600 text-center">{{ $report->user->email ?? '-' }}</td>

                {{-- Judul --}}
                <td class="p-4 text-center font-semibold">{{ $report->title }}</td>

                {{-- Alamat --}}
                <td class="p-4 text-center">{{ $report->address ?? '-' }}</td>

                {{-- Tanggal --}}
                <td class="p-4 text-center text-gray-600">{{ $report->created_at->format('d M Y') }}</td>

                {{-- Status --}}
                <td class="p-4 text-center">
                    @php
                        $statusStyle = match($report->status) {
                            'pending'  => 'bg-yellow-100 text-yellow-700',
                            'selesai'  => 'bg-green-100 text-green-700',
                            'batal'    => 'bg-red-100 text-red-700',
                            default    => 'bg-gray-100 text-gray-600'
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyle }}">
                        {{ ucfirst($report->status) }}
                    </span>
                </td>

                {{-- Aksi --}}
                <td class="p-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        {{-- Detail --}}
                        <a href="{{ route('admin.reports.show', $report) }}"
                           class="flex items-center gap-1 text-green-700 hover:text-green-900 px-3 py-1 rounded-lg bg-green-50 hover:bg-green-100 transition shadow-sm"
                           title="Detail & Ubah Status">
                            <i class="fa-solid fa-eye"></i>
                            <span class="hidden md:inline">Detail</span>
                        </a>

                        {{-- Hapus --}}
                        <form method="POST"
                              action="{{ route('admin.reports.destroy', $report) }}"
                              class="inline"
                              onsubmit="return confirm('Yakin hapus laporan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="flex items-center gap-1 text-red-700 hover:text-red-900 px-3 py-1 rounded-lg bg-red-50 hover:bg-red-100 transition shadow-sm"
                                    title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                                <span class="hidden md:inline">Hapus</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="p-10 text-center text-gray-500">
                    <i class="fa-solid fa-folder-open text-3xl mb-2"></i><br>
                    Belum ada laporan masuk
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination links --}}
<div class="mt-4">
    {{ $reports->links() }}
</div>

@endsection
