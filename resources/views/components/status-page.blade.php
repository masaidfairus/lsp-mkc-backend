<x-guest-layout auth="test" class="bg-gray-50 flex items-center justify-center min-h-screen p-6">
    <div
      class="max-auto max-w-lg w-full bg-white shadow-xl rounded-2xl overflow-hidden border-t-8 {{ $type == 'danger' ? 'border-red-500' : 'border-amber-500' }}">
      <div class="p-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
        <p class="mt-4 text-gray-600">{{ $message }}</p>

        @if(!empty($details))
          <ul class="mt-6 space-y-2">
            @foreach($details as $item)
              <li class="flex items-center text-sm text-gray-500">
                <span class="mr-2">&bull;</span> {{ $item }}
              </li>
            @endforeach
          </ul>
        @endif

        <a href="{{ $button_url }}"
          class="mt-8 block w-full text-center py-3 rounded-lg font-semibold text-white {{ $type == 'danger' ? 'bg-red-600' : 'bg-amber-600' }}">
          {{ $button_text }}
        </a>
      </div>
    </div>
</x-guest-layout>