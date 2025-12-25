@extends(auth()->user()->role === 'pemerintah'
    ? 'layouts.pemerintah'
    : 'layouts.masyarakat')

@section('title','Chat')
@section('content')

<div class="max-w-4xl mx-auto flex flex-col h-[80vh] bg-white rounded-xl shadow overflow-hidden">

    {{-- HEADER --}}
    <div class="px-6 py-4 border-b flex items-center gap-3 bg-gray-50">
        <img src="https://ui-avatars.com/api/?name={{ $conversation->otherUser()->name }}"
             class="w-10 h-10 rounded-full">
        <div>
            <p class="font-semibold">{{ $conversation->otherUser()->name }}</p>
            <p class="text-xs text-gray-500 capitalize">
                {{ $conversation->otherUser()->role }}
            </p>
        </div>
    </div>

    {{-- MESSAGES --}}
    <div id="chatBox"
         class="flex-1 overflow-y-auto px-4 py-6 space-y-4 bg-[#efeae2]">

        @foreach($messages as $msg)
            @php $isMe = $msg->sender_id === auth()->id(); @endphp

            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs rounded-xl px-4 py-2 text-sm
                    {{ $isMe ? 'bg-[#dcf8c6]' : 'bg-white' }} shadow">

                    {{-- IMAGE --}}
                    @if($msg->image)
                        <img src="{{ asset('storage/'.$msg->image) }}"
                             class="rounded-lg mb-2 max-w-full">
                    @endif

                    {{-- TEXT --}}
                    @if($msg->message)
                        <p class="text-gray-800">{{ $msg->message }}</p>
                    @endif

                    {{-- WAKTU --}}
                    <p class="text-[10px] text-right text-gray-500 mt-1">
                        {{ $msg->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- IMAGE PREVIEW --}}
    <div id="imagePreview" class="hidden border-t p-4 bg-gray-50">
        <p class="text-sm mb-2 text-gray-600">Preview gambar:</p>
        <div class="relative inline-block">
            <img id="previewImg" class="rounded-lg max-h-40">
            <button type="button"
                    onclick="removeImage()"
                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 text-xs">
                ✕
            </button>
        </div>
    </div>

    {{-- INPUT --}}
    <form method="POST"
          action="{{ route('chat.store', $conversation) }}"
          enctype="multipart/form-data"
          class="border-t px-4 py-3 flex items-end gap-2 bg-white">

        @csrf

        {{-- IMAGE PICKER --}}
        <input type="file" name="image" id="imageInput"
               accept="image/*" class="hidden">

        <label for="imageInput"
               class="cursor-pointer text-gray-500 text-xl">
            <i class="fa-solid fa-image"></i>
        </label>

        {{-- TEXT --}}
        <textarea name="message"
                  rows="1"
                  placeholder="Tulis pesan..."
                  class="flex-1 resize-none border rounded-lg px-4 py-2 focus:ring-emerald-500"></textarea>

        <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </form>

</div>

{{-- JS --}}
<script>
    // IMAGE PREVIEW
    const imageInput = document.getElementById('imageInput');
    const previewBox = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            previewImg.src = URL.createObjectURL(file);
            previewBox.classList.remove('hidden');
        }
    });

    function removeImage() {
        imageInput.value = '';
        previewBox.classList.add('hidden');
    }

    // AUTO SCROLL KE BAWAH
    const chatBox = document.getElementById('chatBox');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

@endsection
