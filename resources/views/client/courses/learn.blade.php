@extends('layouts.client')

@section('title', $course->title . ' - Learning')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8 mt-16"
         data-video-progress="{{ $progress && $progress->where('lesson_id', $lesson->id)->first() ? $progress->where('lesson_id', $lesson->id)->first()->video_progress : 0 }}"
         data-lesson-duration="{{ $lesson->duration * 60 }}"
         data-is-completed="{{ $progress && $progress->where('lesson_id', $lesson->id)->where('completed', true)->first() ? 'true' : 'false' }}"
         data-course-is-free="{{ $course->is_free ? 'true' : 'false' }}">
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
            <!-- Course Content Sidebar -->
            <div class="w-full lg:w-[280px] xl:w-[300px] flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm sticky top-4">
                    <div class="p-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">{{ $course->title }}</h2>
                        <p class="text-sm text-gray-500 mt-1.5">Instructor: {{ $course->instructor->name }}</p>
                    </div>
                    <div class="p-3 max-h-[calc(100vh-220px)] overflow-y-auto">
                        <div class="space-y-2">
                            @foreach($course->lessons as $courseLesson)
                                <a href="{{ route('client.courses.learn', ['course' => $course->slug, 'lesson' => $courseLesson->id]) }}" 
                                   class="lesson-link flex items-center p-3.5 rounded-lg hover:bg-gray-50 transition-colors duration-200 {{ $lesson && $lesson->id === $courseLesson->id ? 'bg-indigo-50 ring-1 ring-indigo-200' : '' }}">
                                    <div class="flex-shrink-0 mr-3">
                                        @if($courseLesson->video_url)
                                            <svg class="w-5 h-5 {{ $lesson && $lesson->id === $courseLesson->id ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 {{ $lesson && $lesson->id === $courseLesson->id ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium {{ $lesson && $lesson->id === $courseLesson->id ? 'text-indigo-600' : 'text-gray-900' }} truncate">
                                            {{ $courseLesson->title }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $courseLesson->duration }} min
                                        </p>
                                    </div>
                                    @if($progress && $progress->where('lesson_id', $courseLesson->id)->where('completed', true)->first())
                                        <div class="flex-shrink-0 ml-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="mr-1 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3" />
                                                </svg>
                                                Done
                                            </span>
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="w-full lg:flex-1">
                @if($lesson)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <!-- Video Player -->
                        @if($lesson->video_url)
                            <div class="relative w-full max-w-4xl mx-auto bg-black">
                                <div class="relative w-full aspect-[16/9]">
                                    @if(Str::startsWith($lesson->video_url, ['http://', 'https://']))
                                        <iframe 
                                            src="{{ $lesson->video_url }}" 
                                            class="absolute top-0 left-0 w-full h-full"
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen>
                                        </iframe>
                                    @else
                                        <video 
                                            id="lessonVideo" 
                                            class="absolute top-0 left-0 w-full h-full object-contain" 
                                            controls 
                                            playsinline
                                            preload="metadata">
                                            <source src="{{ asset('storage/' . $lesson->video_url) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                                <div class="relative">
                                    <div class="flex mb-3 items-center justify-between">
                                        <div>
                                            <span class="text-xs font-semibold inline-block py-1 px-2.5 uppercase rounded-full text-indigo-600 bg-indigo-100">
                                                Progress
                                            </span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-semibold inline-block text-indigo-600" id="progressPercentage">
                                                0%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden h-2.5 mb-3 text-xs flex rounded-full bg-indigo-100">
                                        <div id="progressBar" style="width:0%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-600 transition-all duration-500 rounded-full"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500">
                                        <span id="currentTime">0:00</span>
                                        <span id="totalTime">0:00</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Lesson Content -->
                        <div class="p-8">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                                <h1 class="text-2xl font-bold text-gray-900">{{ $lesson->title }}</h1>
                                @if($progress && $progress->where('lesson_id', $lesson->id)->where('completed', true)->first())
                                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        Completed
                                    </span>
                                @endif
                            </div>

                            <div class="prose prose-lg max-w-none mb-10">
                                {!! $lesson->description !!}
                            </div>

                            <!-- Mark as Complete Button -->
                            <button type="button" 
                                    onclick="markAsComplete()"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 border border-transparent rounded-lg text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Mark as Complete
                            </button>

                            @if(!$course->is_free && $lesson->assignments && $lesson->assignments->count() > 0)
                                <!-- Assignments Section -->
                                <div id="assignmentsSection" class="border-t border-gray-200 mt-10 pt-10" style="display: none;">
                                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Assignments</h2>
                                    <div class="space-y-6">
                                        @foreach($lesson->assignments as $assignment)
                                            <div class="bg-gray-50 rounded-xl p-6">
                                                <h3 class="text-lg font-medium text-gray-900 mb-3">{{ $assignment->title }}</h3>
                                                <p class="text-gray-600 mb-6">{{ $assignment->description }}</p>
                                                
                                                @php
                                                    $submission = $submissions->where('assignment_id', $assignment->id)->first();
                                                @endphp

                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                                    <div>
                                                        <span class="text-sm text-gray-500">Due: {{ $assignment->due_date->format('M d, Y') }}</span>
                                                        @if($submission)
                                                            <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $submission->status === 'submitted' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                {{ ucfirst($submission->status) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    
                                                    @if(!$submission)
                                                        <a href="{{ route('client.assignments.show', $assignment->id) }}" 
                                                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                                                            Start Assignment
                                                        </a>
                                                    @else
                                                        <a href="{{ route('client.assignments.show', $assignment->id) }}" 
                                                           class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                                                            View Submission
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-3">Welcome to {{ $course->title }}</h2>
                        <p class="text-gray-600 text-lg">Select a lesson from the sidebar to begin learning.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-sm w-full mx-4 shadow-xl transform transition-all">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-green-100 mb-5">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Lesson Completed!</h3>
            <p class="text-base text-gray-600">You have successfully completed this lesson.</p>
        </div>
    </div>
</div>

<!-- Warning Modal -->
<div id="warningModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-8 max-w-sm w-full mx-4 shadow-xl transform transition-all">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-yellow-100 mb-5">
                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Skip Limit Reached</h3>
            <p class="text-base text-gray-600">您不能跳过视频内容。</p>
            <button onclick="hideWarningModal()" class="mt-6 w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                OK
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
let youtubePlayer = null;
let youtubeProgressInterval = null;
let isAutoCompleting = false;
let hasWatchedEntireVideo = false;
let video = null;
let lastValidTime = 0;
let skipAttempts = 0;
const maxSkipAttempts = 3;
const maxSeekAhead = 120; // Maximum seconds to skip ahead (2 minutes)

document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-video-progress]');
    const videoProgress = parseInt(container.dataset.videoProgress);
    const lessonDuration = parseInt(container.dataset.lessonDuration);
    const isCompleted = container.dataset.isCompleted === 'true';
    
    // Set initial state
    if (isCompleted || videoProgress >= lessonDuration) {
        hasWatchedEntireVideo = true;
        showAssignments();
    }

    // Initialize video player
    video = document.getElementById('lessonVideo');
    initializeVideoPlayer();
});

function initializeVideoPlayer() {
    if (video) {
        setupLocalVideo();
    } else {
        setupYoutubeVideo();
    }
}

function setupLocalVideo() {
    let lastUpdateTime = 0;
    const updateInterval = 1000;

    // 初始化视频加载事件
    video.addEventListener('loadedmetadata', function() {
        updateVideoProgress(video.currentTime, video.duration);
        lastValidTime = video.currentTime;
    });

    // 监控进度更新
    video.addEventListener('timeupdate', function() {
        const now = Date.now();
        if (now - lastUpdateTime >= updateInterval) {
            const currentTime = video.currentTime;
            const skipDistance = currentTime - lastValidTime;
            
            // 检查是否有不正常的跳转
            if (skipDistance > maxSeekAhead && !hasWatchedEntireVideo) {
                skipAttempts++;
                if (skipAttempts >= maxSkipAttempts) {
                    video.currentTime = lastValidTime;
                    showWarningModal('您已多次尝试跳过视频。请按顺序观看课程内容。');
                    skipAttempts = 0;
                } else {
                    video.currentTime = lastValidTime;
                    showWarningModal(`请不要跳过视频内容。剩余${maxSkipAttempts - skipAttempts}次警告。`);
                }
            } else if (skipDistance <= maxSeekAhead || hasWatchedEntireVideo) {
                lastValidTime = currentTime;
                skipAttempts = Math.max(0, skipAttempts - 1); // 正常观看时逐渐减少警告次数
            }

            lastUpdateTime = now;
            updateVideoProgress(video.currentTime, video.duration);
            saveProgress(video.currentTime);
        }
    });

    // 视频结束事件
    video.addEventListener('ended', function() {
        hasWatchedEntireVideo = true;
        updateVideoProgress(video.duration, video.duration);
        showAssignments();
    });

    // 监听播放速度变化
    video.addEventListener('ratechange', function() {
        if (video.playbackRate > 2) {
            video.playbackRate = 2;
            showWarningModal('播放速度不能超过2倍速。');
        }
    });
}

function setupYoutubeVideo() {
    const iframe = document.querySelector('iframe');
    if (!iframe || !iframe.src.includes('youtube.com')) return;

    if (!window.YT) {
        const tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }

    let iframeSrc = new URL(iframe.src);
    iframeSrc.searchParams.set('enablejsapi', '1');
    iframeSrc.searchParams.set('origin', window.location.origin);
    iframeSrc.searchParams.set('modestbranding', '1');
    iframeSrc.searchParams.set('rel', '0');
    iframe.src = iframeSrc.toString();
    iframe.id = 'youtubePlayer';

    window.onYouTubeIframeAPIReady = function() {
        youtubePlayer = new YT.Player('youtubePlayer', {
            events: {
                'onReady': onYoutubeReady,
                'onStateChange': onYoutubeStateChange,
                'onPlaybackRateChange': onYoutubePlaybackRateChange
            },
            playerVars: {
                'playsinline': 1,
                'modestbranding': 1,
                'rel': 0
            }
        });
    };
}

function onYoutubeReady(event) {
    const container = document.querySelector('[data-video-progress]');
    const videoProgress = parseInt(container.dataset.videoProgress);
    
    if (videoProgress > 0) {
        event.target.seekTo(videoProgress, true);
    }

    if (youtubeProgressInterval) {
        clearInterval(youtubeProgressInterval);
    }
    youtubeProgressInterval = setInterval(updateYoutubeProgress, 1000);

    // 限制播放速度选项
    event.target.setPlaybackRate(1);
}

function onYoutubeStateChange(event) {
    if (event.data === YT.PlayerState.ENDED) {
        hasWatchedEntireVideo = true;
        if (youtubePlayer && typeof youtubePlayer.getDuration === 'function') {
            const duration = youtubePlayer.getDuration();
            updateVideoProgress(duration, duration);
        }
        showAssignments();
        if (youtubeProgressInterval) {
            clearInterval(youtubeProgressInterval);
        }
    }
}

function onYoutubePlaybackRateChange(event) {
    // 限制播放速度
    if (youtubePlayer && typeof youtubePlayer.getPlaybackRate === 'function') {
        const rate = youtubePlayer.getPlaybackRate();
        if (rate > 2) {
            youtubePlayer.setPlaybackRate(2);
            showWarningModal('播放速度不能超过2倍速。');
        }
    }
    
    if (youtubePlayer && typeof youtubePlayer.getCurrentTime === 'function') {
        lastValidTime = youtubePlayer.getCurrentTime();
    }
}

function updateYoutubeProgress() {
    if (!youtubePlayer || typeof youtubePlayer.getCurrentTime !== 'function') {
        return;
    }

    try {
        const currentTime = youtubePlayer.getCurrentTime();
        const skipDistance = currentTime - lastValidTime;
        
        if (skipDistance > maxSeekAhead && !hasWatchedEntireVideo) {
            skipAttempts++;
            if (skipAttempts >= maxSkipAttempts) {
                youtubePlayer.seekTo(lastValidTime, true);
                showWarningModal('您已多次尝试跳过视频。请按顺序观看课程内容。');
                skipAttempts = 0;
            } else {
                youtubePlayer.seekTo(lastValidTime, true);
                showWarningModal(`请不要跳过视频内容。剩余${maxSkipAttempts - skipAttempts}次警告。`);
            }
        } else if (skipDistance <= maxSeekAhead || hasWatchedEntireVideo) {
            lastValidTime = currentTime;
            skipAttempts = Math.max(0, skipAttempts - 1);
        }

        const duration = youtubePlayer.getDuration();
        if (!isNaN(currentTime) && !isNaN(duration) && duration > 0) {
            updateVideoProgress(currentTime, duration);
            saveProgress(currentTime);
        }
    } catch (error) {
        console.error('Error updating YouTube progress:', error);
    }
}

function updateVideoProgress(currentTime, duration) {
    if (typeof currentTime !== 'number' || typeof duration !== 'number' || duration <= 0) return;
    
    const percentage = Math.min((currentTime / duration) * 100, 100);
    
    document.getElementById('progressBar').style.width = `${percentage}%`;
    document.getElementById('progressPercentage').textContent = `${Math.round(percentage)}%`;
    document.getElementById('currentTime').textContent = formatTime(currentTime);
    document.getElementById('totalTime').textContent = formatTime(duration);
    
    // Set hasWatchedEntireVideo to true if we've watched at least 95% of the video
    if (percentage >= 95) {
        hasWatchedEntireVideo = true;
        showAssignments();
    }
}

function formatTime(seconds) {
    if (!seconds || seconds < 0) return '0:00';
    const minutes = Math.floor(seconds / 60);
    seconds = Math.floor(seconds % 60);
    return `${minutes}:${seconds.toString().padStart(2, '0')}`;
}

function saveProgress(currentTime) {
    if (typeof currentTime !== 'number' || isNaN(currentTime) || currentTime < 0) {
        console.error('Invalid currentTime value:', currentTime);
        return;
    }

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('video_progress', Math.floor(currentTime));

    fetch('{{ route("client.lessons.progress", ["course" => $course->slug, "lesson" => $lesson->id]) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.error('Failed to save progress:', data.message);
        }
    })
    .catch(error => {
        console.error('Error saving progress:', error);
    });
}

function showAssignments() {
    const isFree = document.querySelector('[data-course-is-free]').dataset.courseIsFree === 'true';
    if (!isFree) {
        const assignmentsSection = document.getElementById('assignmentsSection');
        if (assignmentsSection) {
            assignmentsSection.style.display = 'block';
        }
    }
}

function showSuccessModal() {
    const modal = document.getElementById('successModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Hide modal after 3 seconds
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        window.location.reload();
    }, 3000);
}

function markAsComplete() {
    if (isAutoCompleting) return;
    isAutoCompleting = true;

    let currentProgress = 0;
    let duration = 0;
    try {
        if (video && !isNaN(video.currentTime)) {
            currentProgress = Math.floor(video.currentTime);
            duration = Math.floor(video.duration);
        } else if (youtubePlayer && typeof youtubePlayer.getCurrentTime === 'function') {
            currentProgress = Math.floor(youtubePlayer.getCurrentTime());
            duration = Math.floor(youtubePlayer.getDuration());
        }
    } catch (error) {
        console.error('Error getting current time:', error);
    }

    // Calculate current progress percentage
    const progressPercentage = (currentProgress / duration) * 100;
    
    // Allow completion if watched at least 95% of the video
    if (progressPercentage < 95) {
        alert('You must watch at least 95% of the video before marking it as complete.');
        isAutoCompleting = false;
        return;
    }

    // Get the lesson duration in seconds from the data attribute
    const lessonDuration = parseInt(document.querySelector('[data-lesson-duration]').dataset.lessonDuration);
    
    // Set the progress to the full lesson duration to ensure completion
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('video_progress', lessonDuration);
    formData.append('completed', 'true');

    fetch('{{ route("client.lessons.progress", ["course" => $course->slug, "lesson" => $lesson->id]) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessModal();
        } else {
            throw new Error(data.message || 'Failed to mark lesson as complete');
        }
    })
    .catch(error => {
        console.error('Error marking lesson as complete:', error);
        alert(error.message || 'Failed to mark lesson as complete. Please try again.');
    })
    .finally(() => {
        isAutoCompleting = false;
    });
}

// 更新警告模态框显示函数
function showWarningModal(message = '您不能跳过视频内容。') {
    const modal = document.getElementById('warningModal');
    if (modal) {
        const messageElement = modal.querySelector('p.text-gray-600');
        if (messageElement) {
            messageElement.textContent = message;
        }
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideWarningModal();
            }
        });
    }
}

function hideWarningModal() {
    const modal = document.getElementById('warningModal');
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
}
</script>
@endpush

@endsection