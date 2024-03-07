<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Post Create') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-1 sm:py-2 mt-1 sm:mt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-3 sm:p-6 text-gray-900">
                    <form action="/create-post" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input
                            class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm p-2"
                            type="text" name="title" id="title" placeholder="Title of your post..." required>
                        <textarea
                            class="w-full resize-none border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm p-2"
                            rows="5" type="text" name="body" id="body" placeholder="Content of your post..." required></textarea>
                        <p class="my-2 text-sm text-gray-600">
                            {{ __('Upload your image. Max - 2MB. JPEG, JPG, PNG, GIF.') }}
                        </p>
                        <div class="flex flex-row justify-between">
                            <input type="file" id="image" name="image">
                            <x-primary-button>{{ __('Post') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
