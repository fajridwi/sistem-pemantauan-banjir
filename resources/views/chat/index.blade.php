@extends(auth()->user()->role === 'pemerintah'
    ? 'layouts.pemerintah'
    : 'layouts.masyarakat')

@section('title','Pesan')
@section('content')

<div class="max-w-4xl mx-auto bg-white rounded-xl shadow overflow-hidden">

    <div class="px-6 py-4 border-b font-semibold text-lg flex items-center gap-2">
        <i class="fa-solid fa-comments"></i> Pesan
    </div>

    {{-- ================= MASYARAKAT ================= --}}
    @if(auth()->user()->role === 'masyarakat')
        @forelse($users as $user)
            @php
                // hitung pesan dari pemerintah ke masyarakat yang belum dibaca
                $unreadFromPemerintah = \App\Models\Message::whereHas('conversation', function($q) use ($user) {
                    $q->where('masyarakat_id', auth()->id())
                      ->where('pemerintah_id', $user->id);
                })->where('sender_id', $user->id)
                  ->where('is_read', false)
                  ->count();
            @endphp

            <a href="{{ route('chat.show', $user) }}"
               class="flex items-center justify-between gap-4 px-6 py-4 border-b hover:bg-gray-50">

                <div class="flex items-center gap-4">
                    <img src="https://ui-avatars.com/api/?name={{ $user->name }}"
                         class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-semibold">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">Pemerintah</p>
                    </div>
                </div>

                @if($unreadFromPemerintah > 0)
                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                        {{ $unreadFromPemerintah }}
                    </span>
                @endif
            </a>
        @empty
            <p class="p-6 text-center text-gray-500">Tidak ada akun pemerintah</p>
        @endforelse
    @endif

    {{-- ================= PEMERINTAH ================= --}}
    @if(auth()->user()->role === 'pemerintah')
        @forelse($conversations as $chat)
            <a href="{{ route('chat.show', $chat->masyarakat) }}"
               class="flex items-center justify-between px-6 py-4 border-b hover:bg-gray-50">

                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ $chat->masyarakat->name }}"
                         class="w-10 h-10 rounded-full">
                    <div>
                        <p class="font-semibold">{{ $chat->masyarakat->name }}</p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ $chat->lastMessage->message ?? 'Pesan baru' }}
                        </p>
                    </div>
                </div>

                @if($chat->unreadMessagesForPemerintah() > 0)
                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">
                        {{ $chat->unreadMessagesForPemerintah() }}
                    </span>
                @endif
            </a>
        @empty
            <p class="p-6 text-center text-gray-500">Belum ada pesan</p>
        @endforelse
    @endif

</div>
@endsection
