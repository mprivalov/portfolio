<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ 'Follows' }}
            </h2>
        </div>
    </x-slot>

    <div class="py-1 sm:py-2 mt-1 sm:mt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col sm:flex-row justify-around gap-6">
                        <div class="flex flex-col w-full mb-6">
                            <h2 class="text-lg pb-1 mb-2 border-b-2 border-indigo-400">My
                                Followers:</h2>
                            <ul class="flex flex-col justify-between">
                                @foreach ($followers as $follower)
                                    <li class="flex flex-row gap-2 items-center py-2 border-b border-gray-400">
                                        @if ($follower->avatar)
                                            <img src="{{ asset('storage/' . $follower->avatar) }}" alt="Avatar"
                                                class="w-10 h-10 object-contain object-center rounded-full border border-gray-500 hover:border-2 hover:border-indigo-500">
                                        @else
                                            <img src="/storage/images/empty-avatar.png" alt="Avatar"
                                                class="w-10 h-10 object-contain object-center rounded-full border border-gray-500 hover:border-2 hover:border-indigo-500">
                                        @endif
                                        <a href="{{ route('profile.user-posts', $follower->id) }}"
                                            class="hover:text-indigo-600">{{ $follower->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="flex flex-col w-full mb-6">
                            <h2 class="text-lg pb-1 mb-2 border-b-2 border-indigo-400">Following:</h2>
                            <ul class="flex flex-col justify-between">
                                @foreach ($following as $follow)
                                    <li class="flex flex-row gap-2 items-center py-2 border-b border-gray-400">
                                        @if ($follow->avatar)
                                            <img src="{{ asset('storage/' . $follow->avatar) }}" alt="Avatar"
                                                class="w-10 h-10 object-contain object-center rounded-full border border-gray-500 hover:border-2 hover:border-indigo-500">
                                        @else
                                            <img src="/storage/images/empty-avatar.png" alt="Avatar"
                                                class="w-10 h-10 object-contain object-center rounded-full border border-gray-500 hover:border-2 hover:border-indigo-500">
                                        @endif
                                        <a href="{{ route('profile.user-posts', $follow->id) }}"
                                            class="hover:text-indigo-600">{{ $follow->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
