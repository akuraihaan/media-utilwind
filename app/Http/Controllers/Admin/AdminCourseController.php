<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCourseController extends Controller
{
    public function index()
    {
        return view('admin.courses.index', [
            'courses' => Course::latest()->get()
        ]);
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $r)
    {
        $r->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        Course::create([
            'title' => $r->title,
            'slug' => Str::slug($r->title),
            'description' => $r->description,
        ]);

        return redirect()->route('admin.courses.index');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $r, Course $course)
    {
        $course->update($r->only('title','description'));
        return back();
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return back();
    }
}
