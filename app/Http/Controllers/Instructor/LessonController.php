<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Process\Process;

class LessonController extends Controller
{
    public function create(Course $course)
    {
        $this->authorize('update', $course);
        $course->load('modules');
        return view('instructor.lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,webm|max:512000',
            'video_url' => 'nullable|string',
            'uploaded_video_path' => 'nullable|string',
            'video_duration' => 'nullable|integer|min:0',
            'subtitle_url' => 'nullable|string',
            'uploaded_subtitle_path' => 'nullable|string',
            'is_free' => 'boolean',
            'module_id' => 'nullable|exists:modules,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
            'uploaded_attachments' => 'nullable|array',
            'uploaded_attachments.*.path' => 'required_with:uploaded_attachments|string',
            'uploaded_attachments.*.original_name' => 'required_with:uploaded_attachments|string',
        ]);

        $valid['course_id'] = $course->id;
        if (empty($valid['module_id']) || !\App\Models\Module::where('id', $valid['module_id'])->where('course_id', $course->id)->exists()) {
            $valid['module_id'] = null;
        }
        $valid['slug'] = Str::slug($valid['title']);
        $valid['is_free'] = $request->boolean('is_free');
        $valid['order'] = $course->lessons()->max('order') + 1;

        if ($request->hasFile('video')) {
            $valid['video_url'] = $this->storeVideoForWebPlayback($request->file('video'));
        } elseif (!empty(trim($valid['uploaded_video_path'] ?? ''))) {
            $valid['video_url'] = trim($valid['uploaded_video_path']);
        } elseif (!empty(trim($valid['video_url'] ?? ''))) {
            $valid['video_url'] = trim($valid['video_url']);
        } else {
            unset($valid['video_url']);
        }
        unset($valid['video'], $valid['uploaded_video_path']);

        $valid['video_duration'] = $valid['video_duration'] ?? null;
        if (!empty(trim($valid['uploaded_subtitle_path'] ?? ''))) {
            $valid['subtitle_url'] = trim($valid['uploaded_subtitle_path']);
        } elseif (!empty(trim($valid['subtitle_url'] ?? ''))) {
            $valid['subtitle_url'] = trim($valid['subtitle_url']);
        } else {
            $valid['subtitle_url'] = null;
        }
        unset($valid['uploaded_subtitle_path']);

        $lesson = Lesson::create(collect($valid)->except(['attachments', 'uploaded_attachments'])->all());
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('lesson-attachments', 'public');
                $lesson->attachments()->create(['path' => '/storage/' . $path, 'original_name' => $file->getClientOriginalName()]);
            }
        }
        foreach ($request->input('uploaded_attachments', []) ?: [] as $att) {
            if (!empty($att['path'] ?? '') && !empty($att['original_name'] ?? '')) {
                $lesson->attachments()->create(['path' => $att['path'], 'original_name' => $att['original_name']]);
            }
        }
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Lesson added.');
    }

    public function edit(Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);
        if ($lesson->course_id !== $course->id) abort(404);
        $lesson->load(['videoQuizzes', 'attachments']);
        return view('instructor.lessons.edit', compact('course', 'lesson'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);
        if ($lesson->course_id !== $course->id) abort(404);

        $valid = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'video' => 'nullable|file|mimes:mp4,mov,webm|max:512000',
            'video_url' => 'nullable|string',
            'uploaded_video_path' => 'nullable|string',
            'video_duration' => 'nullable|integer|min:0',
            'subtitle_url' => 'nullable|string',
            'uploaded_subtitle_path' => 'nullable|string',
            'is_free' => 'boolean',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx,ppt,pptx|max:20480',
            'uploaded_attachments' => 'nullable|array',
            'uploaded_attachments.*.path' => 'required_with:uploaded_attachments|string',
            'uploaded_attachments.*.original_name' => 'required_with:uploaded_attachments|string',
        ]);

        $valid['is_free'] = $request->boolean('is_free');

        if ($request->hasFile('video')) {
            $valid['video_url'] = $this->storeVideoForWebPlayback($request->file('video'));
        } elseif (!empty(trim($valid['uploaded_video_path'] ?? ''))) {
            $valid['video_url'] = trim($valid['uploaded_video_path']);
        } elseif (!empty(trim($valid['video_url'] ?? ''))) {
            $valid['video_url'] = trim($valid['video_url']);
        } else {
            unset($valid['video_url']);
        }
        unset($valid['video'], $valid['uploaded_video_path']);

        if (!empty(trim($valid['uploaded_subtitle_path'] ?? ''))) {
            $valid['subtitle_url'] = trim($valid['uploaded_subtitle_path']);
        } elseif (!empty(trim($valid['subtitle_url'] ?? ''))) {
            $valid['subtitle_url'] = trim($valid['subtitle_url']);
        } else {
            $valid['subtitle_url'] = null;
        }
        unset($valid['uploaded_subtitle_path']);

        $lesson->update(collect($valid)->except(['attachments', 'uploaded_attachments'])->all());
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('lesson-attachments', 'public');
                $lesson->attachments()->create(['path' => '/storage/' . $path, 'original_name' => $file->getClientOriginalName()]);
            }
        }
        foreach ($request->input('uploaded_attachments', []) ?: [] as $att) {
            if (!empty($att['path'] ?? '') && !empty($att['original_name'] ?? '')) {
                $lesson->attachments()->create(['path' => $att['path'], 'original_name' => $att['original_name']]);
            }
        }
        return redirect()->route('instructor.lessons.edit', [$course, $lesson])->with('success', 'Lesson updated.');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $this->authorize('update', $course);
        if ($lesson->course_id !== $course->id) abort(404);
        $lesson->delete();
        return redirect()->route('instructor.courses.edit', $course)->with('success', 'Lesson deleted.');
    }

    public function uploadVideo(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        $request->validate(['video' => 'required|file|mimes:mp4,mov,webm|max:512000']);
        return response()->json(['path' => $this->storeVideoForWebPlayback($request->file('video'))]);
    }

    public function uploadAttachment(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        $request->validate(['attachment' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:20480']);
        $file = $request->file('attachment');
        $path = $file->store('lesson-attachments', 'public');
        return response()->json(['path' => '/storage/' . $path, 'original_name' => $file->getClientOriginalName()]);
    }

    public function uploadSubtitle(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        $request->validate([
            'subtitle' => [
                'required',
                'file',
                'max:1024',
                function ($attribute, $value, $fail) {
                    if (! str_ends_with(strtolower($value->getClientOriginalName()), '.vtt')) {
                        $fail('The subtitle must be a WebVTT (.vtt) file.');
                    }
                },
            ],
        ]);
        $path = $request->file('subtitle')->store('subtitles', 'public');
        return response()->json(['path' => '/storage/' . $path]);
    }

    private function storeVideoForWebPlayback(UploadedFile $video): string
    {
        $ext = strtolower((string) $video->getClientOriginalExtension());
        if ($ext !== 'mov') {
            return '/storage/' . $video->store('videos', 'public');
        }

        $targetRelative = 'videos/' . Str::uuid() . '.mp4';
        $targetAbsolute = storage_path('app/public/' . $targetRelative);
        if (!is_dir(dirname($targetAbsolute))) {
            mkdir(dirname($targetAbsolute), 0755, true);
        }

        // Convert MOV into MP4 (H.264/AAC) for broad browser support.
        $process = new Process([
            'ffmpeg',
            '-y',
            '-i',
            $video->getRealPath(),
            '-c:v',
            'libx264',
            '-preset',
            'medium',
            '-crf',
            '23',
            '-c:a',
            'aac',
            '-b:a',
            '128k',
            '-movflags',
            '+faststart',
            $targetAbsolute,
        ]);
        $process->setTimeout(1800);
        $process->run();

        if (! $process->isSuccessful()) {
            throw ValidationException::withMessages([
                'video' => 'Could not process this MOV file for web playback. Please convert it to MP4 (H.264/AAC) and upload again.',
            ]);
        }

        return '/storage/' . $targetRelative;
    }
}
