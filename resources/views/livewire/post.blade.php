<?php

use function Livewire\Volt\{state, usesFileUploads, computed,rules};
use App\Models\Post;

usesFileUploads();
state([
    'title' => '',
    'body' => '',
    'image' => null,
]);
rules(['title'=>'required','body'=>'required','image'=>'image|max:1024']);

$posts = computed(fn() => Post::all());

$addPost = function () {
    $this->validate();
    Post::create([
        'title' => $this->title,
        'body' => $this->body,
        'image' => $this->image->store('posts'),
    ]);
    $this->title = '';
    $this->body = '';
    // $this->image='';
};
$deletePost= function (Post $post){
Storage::delete($post->image);
$post->delete();
}
?>

<div class="max-w-6xl mx-auto bg-slate-100 rounded">
    <div class="my-4">
        <form wire:submit="addPost" class="p-4 space-y-2" enctype="multipart/form-data">
            <div>
                <label for="title">Title</label>
                <input type="text" name="title" id="title" wire:model="title"
                    class="p-3 block w-full px-4 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow">
                @error('title')
                    <span class="text-red-400">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="body">description</label>

                <textarea name="body" id="body" wire:model="body"
                    class="p-3 block w-full px-4 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow"></textarea>
                @error('body')
                    <span class="text-red-400">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="image">Image</label>
                <input type="file" name="image" id="image" wire:model="image"
                    class="p-3 block w-full px-4 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow">
                @error('image')
                    <span class="text-red-400">{{ $message }}</span>
                @enderror

            </div>
            <button type="submit" class="px-3 py-2 bg-indigo-500 rounded">Submit</button>
        </form>
    </div>
    <div class="max-w-md mx-auto flex flex-col space-y-2">
        @foreach ($this->posts as $post)
            <div class="flex flex-col items-center bg-white border border-gray-200 round-lg shadow md:flex-row">
                <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded"
                    src="{{ asset('/storage/' . $post->image) }}" alt=""/>
                <div class="flex flex-col justify-between p-4 leading-normal">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-light">
                        {{ $post->title }}
                    </h5>
                </div>
                <button wire:click="deletePost({{ $post->id }})" class="px-3 py-2 bg-red-500 rounded">delete</button>
            </div>
        @endforeach
    </div>
</div>
