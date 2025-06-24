<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new enrollment.
     */
    public function store(Request $request, Course $course)
    {
        // Check if user is already enrolled
        $existingEnrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->route('client.courses.show', $course->slug)
                ->with('error', 'You are already enrolled in this course.');
        }

        // Create enrollment
        Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'payment_status' => 'pending',
            'amount_paid' => $course->price,
            'enrolled_at' => now(),
        ]);

        return redirect()->route('client.enrollments.index')
            ->with('success', 'Successfully enrolled in the course. You can now start learning!');
    }

    /**
     * Display a paginated list of enrollments.
     */
    public function index()
    {
        // 获取统计数据
        $stats = Enrollment::where('user_id', Auth::id())
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            ->selectRaw('SUM(CASE WHEN status = "in_progress" THEN 1 ELSE 0 END) as in_progress')
            ->first();

        // 获取分页数据
        $enrollments = Enrollment::with(['course.category', 'course.instructor', 'course.lessons'])
            ->where('user_id', Auth::id())
            ->latest('enrolled_at')
            ->simplePaginate(6);  // 使用simplePaginate来显示简单的"上一页/下一页"导航，每页6个课程

        return view('client.enrollments.index', compact('enrollments', 'stats'));
    }
}