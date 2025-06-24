@extends('layouts.client')

@section('title', $lesson->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Video Player -->
        <div class="aspect-w-16 aspect-h-9">
            @if($lesson->video_url)
                <video class="w-full h-full object-cover" controls>
                    <source src="{{ Storage::url($lesson->video_url) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @else
                <div class="flex items-center justify-center h-full bg-gray-100">
                    <p class="text-gray-500">No video available</p>
                </div>
            @endif
        </div>

        <!-- Lesson Content -->
        <div class="p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $lesson->title }}</h1>
            
            <div class="prose max-w-none">
                {!! $lesson->description !!}
            </div>

            <!-- Course Navigation -->
            <div class="mt-8 flex justify-between">
                @if($lesson->previous())
                    <a href="{{ route('client.lessons.show', $lesson->previous()) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Previous Lesson
                    </a>
                @endif

                @if($lesson->next())
                    <a href="{{ route('client.lessons.show', $lesson->next()) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Next Lesson
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 