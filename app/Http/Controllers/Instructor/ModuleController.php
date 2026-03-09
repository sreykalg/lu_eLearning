<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        $valid = $request->validate(['title' => 'required|string|max:255']);
        $valid['order'] = $course->modules()->max('order') + 1;
        $course->modules()->create($valid);
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Module added.');
    }

    public function update(Request $request, Course $course, Module $module)
    {
        $this->authorize('update', $course);
        if ($module->course_id !== $course->id) abort(404);
        $module->update($request->validate(['title' => 'required|string|max:255']));
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Module updated.');
    }

    public function destroy(Course $course, Module $module)
    {
        $this->authorize('update', $course);
        if ($module->course_id !== $course->id) abort(404);
        $module->delete();
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Module deleted.');
    }
}
