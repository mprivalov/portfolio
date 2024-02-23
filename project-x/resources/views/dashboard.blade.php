<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ 'Dashboard' }}
            </h2>
            <x-primary-button>
                <a href="/create-post">{{ __('Create Post') }}</a>
            </x-primary-button>
        </div>
    </x-slot>

    @if (session('delete'))
        <div class="alert alert-delete w-full flex justify-center bg-green-400 py-3">
            {{ session('delete') }}
        </div>
        <script>
            setTimeout(function() {
                document.querySelector('.alert').style.display = 'none';
            }, 5000);
        </script>
    @endif

    @if (session('success'))
        <div class="alert alert-success w-full flex justify-center bg-green-400 py-3">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(function() {
                document.querySelector('.alert').style.display = 'none';
            }, 5000);
        </script>
    @endif

    @if (session('updated'))
        <div class="alert alert-updated w-full flex justify-center bg-green-400 py-3">
            {{ session('updated') }}
        </div>
        <script>
            setTimeout(function() {
                document.querySelector('.alert').style.display = 'none';
            }, 5000);
        </script>
    @endif

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-2 sm:mt-4 flex justify-end">
        <form action="{{ route('dashboard.filter') }}" method="GET" class="flex flex-row justify-between gap-3">
            <select name="filter" id="filter"
                class="p-2 pr-8 w-full border-none focus:border-indigo-500 focus:ring-indigo-500 rounded-md">
                <option value="recent">Most Recent</option>
                <option value="liked">Most Liked</option>
            </select>
            <x-secondary-button type="submit">Apply</x-secondary-button>
        </form>
    </div>

    @foreach ($data as $post)
        <div class="py-1 sm:py-2 mt-1 sm:mt-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 border">
                        <?php
                        $user = auth()->user();
                        $author = $post->user;
                        $userId = $user->id;
                        $postLikes = $post->likesCount;
                        $postIsLiked = $postLikes->where('user_id', $userId)->first();
                        $authorName = $author->name;
                        ?>
                        <div class="flex flex-row justify-between">
                            <button
                                class="inline-flex items-center py-2 border border-transparent text-md leading-4 font-medium rounded-md text-black bg-white hover:text-black hover:underline focus:outline-none transition ease-in-out duration-150">
                                <div class="flex flex-row gap-2 items-center">
                                    @if ($post->user->avatar)
                                        <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="Avatar"
                                            class="w-6 h-6 object-contain object-center rounded-full border border-gray-500 hover:border-2 hover:border-indigo-500">
                                    @else
                                        <img src="/storage/images/empty-avatar.png" alt="Avatar"
                                            class="w-6 h-6 object-contain object-center rounded-full border border-gray-500 hover:border-2 hover:border-indigo-500">
                                    @endif
                                    <a class="hover:cursor-pointer"
                                        href="/user/{{ $post->user->id }}/posts">{{ $authorName }}</a>
                                </div>
                            </button>

                            @if ($post->user_id === auth()->id())
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                            <div class="ms-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                    </x-slot>

                                    <x-slot name="content">
                                        <button
                                            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-indigo-100 focus:outline-none focus:bg-indigo-100 transition duration-150 ease-in-out">
                                            <a href="/posts/{{ $post->id }}/edit"
                                                class="flex">{{ __('Edit') }}</a></button>

                                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-indigo-100 focus:outline-none focus:bg-indigo-100 transition duration-150 ease-in-out">{{ __('Delete') }}</button>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
                        <h1 class="font-semibold">{{ $post->title }}</h1>
                        <p class="p-1">{{ $post->body }}</p>
                        @if ($post->image)
                            <div class="p-1">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Post image"
                                    class="max-w-72 max-h-72 rounded-md">
                            </div>
                        @endif
                        <div class="flex flex-row justify-between py-1 sm:py-2">
                            <div class="flex flex-row gap-2">
                                <form action="/like-post/{{ $post->id }}" method="POST">
                                    @csrf
                                    <button class="flex flex-row items-center">
                                        @if ($postIsLiked)
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="red" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="red" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                            </svg>
                                        @endif
                                        <p class="text-red-500">{{ $post->likes }}</p>
                                    </button>
                                </form>
                            </div>
                            <p class="text-neutral-500">{{ $post->created_at }}</p>
                        </div>
                        <form action="/comment-post/{{ $post->id }}" method="POST">
                            @csrf
                            <textarea
                                class="w-full resize-none border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm p-2"
                                rows="2" id="comment" name="comment" placeholder="Comment this post..." required></textarea>
                            <x-primary-button>
                                Comment
                            </x-primary-button>
                        </form>
                        <div class="w-full min-h-0 max-h-24 overflow-auto touch-auto">
                            <?php
                            $postComments = $post->comments;
                            ?>

                            @foreach ($postComments as $comment)
                                <button
                                    class="inline-flex items-center py-2 border border-transparent text-md leading-4 font-medium rounded-md text-black bg-white hover:text-black hover:underline focus:outline-none transition ease-in-out duration-150">
                                    <div class="flex flex-row gap-2 items-center">
                                        @if ($comment->user->avatar)
                                            <img src="{{ asset('storage/' . $comment->user->avatar) }}" alt="Avatar"
                                                class="w-6 h-6 object-contain object-center rounded-full border border-gray-500 hover:border-2 hover:border-indigo-500">
                                        @else
                                            <img src="/storage/images/empty-avatar.png" alt="Avatar"
                                                class="w-6 h-6 object-contain object-center rounded-full border border-gray-500 hover:border-2 hover:border-indigo-500">
                                        @endif
                                        <a href="/user/{{ $comment->user->id }}/posts">
                                            {{ $comment->user->name }}</a>
                                    </div>
                                </button>
                                <p class="p-1">{{ $comment->body }}</p>
                                <?php
                                $commentLikes = $comment->likesCount;
                                $commentIsLiked = $commentLikes->where('user_id', $userId)->first();
                                ?>
                                <div class="flex flex-row justify-between py-1 sm:py-2">
                                    <form action="/like-comment/{{ $comment->id }}" method="POST">
                                        @csrf
                                        <button class="flex flex-row">
                                            @if ($commentIsLiked)
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="red"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="red"
                                                    class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="red"
                                                    class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            @endif
                                            <p>{{ $comment->likes }}</p>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
