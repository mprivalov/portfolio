<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Profile Image') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile image.") }}
        </p>
    </header>

    <form action="/profile" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf

        <p class="mt-2 text-sm text-gray-600">
            {{ __('Upload small images: Max height - 200px, Max width - 200px.') }}
        </p>
        <div class="flex flex-row">
            <input type="file" name="avatar" class="mt-2">
            <x-secondary-button type="submit">{{ __('Upload Image') }}</x-secondary-button>
        </div>
    </form>
</section>
