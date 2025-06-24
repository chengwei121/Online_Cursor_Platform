<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    public function show(Lesson $lesson)
    {
        // Check if user is enrolled in the course
        $user = auth()->user();
        $course = $lesson->course;
        
        if (!$user->enrollments()->where('course_id', $course->id)->exists()) {
            return redirect()->route('client.courses.show', $course)
                           ->with('error', 'You must be enrolled in this course to view lessons.');
        }

        return view('client.lessons.show', compact('lesson'));
    }

    public function uploadVideo(Request $request, Lesson $lesson)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mov,ogg,webm|max:102400' // 100MB max
        ]);

        if ($request->hasFile('video')) {
            // Delete old video if exists
            if ($lesson->video_url && Storage::exists($lesson->video_url)) {
                Storage::delete($lesson->video_url);
            }

            // Store new video
            $path = $request->file('video')->store('lessons/videos', 'public');
            $lesson->update(['video_url' => $path]);

            return back()->with('success', 'Video uploaded successfully.');
        }

        return back()->with('error', 'No video file uploaded.');
    }
} 