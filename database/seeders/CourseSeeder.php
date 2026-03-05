<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\User;
use App\Models\VideoQuiz;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::firstOrCreate(
            ['email' => 'instructor@example.com'],
            ['name' => 'Dr. Instructor', 'password' => bcrypt('password'), 'role' => 'instructor']
        );

        $course = Course::firstOrCreate(
            ['slug' => 'introduction-to-web-development'],
            [
                'instructor_id' => $instructor->id,
                'title' => 'Introduction to Web Development',
                'description' => 'Learn the basics of web development including HTML, CSS, and JavaScript. Perfect for beginners who want to start building websites.',
                'level' => 'beginner',
                'order' => 0,
                'is_published' => true,
            ]
        );

        $lessons = [
            [
                'title' => 'Welcome & Course Overview',
                'content' => "Welcome to the course! In this lesson we'll cover what you'll learn and how to get the most out of this course.",
                'video_url' => null,
                'video_duration' => 300,
                'order' => 0,
                'is_free' => true,
            ],
            [
                'title' => 'HTML Basics',
                'content' => "HTML is the structure of every webpage. We'll learn about tags, elements, and how to create your first HTML page.",
                'video_url' => null,
                'video_duration' => 600,
                'order' => 1,
                'is_free' => false,
            ],
            [
                'title' => 'CSS Fundamentals',
                'content' => "CSS makes your pages beautiful. Learn about selectors, properties, and basic styling.",
                'video_url' => null,
                'video_duration' => 720,
                'order' => 2,
                'is_free' => false,
            ],
        ];

        foreach ($lessons as $i => $data) {
            $lesson = Lesson::firstOrCreate(
                [
                    'course_id' => $course->id,
                    'slug' => \Illuminate\Support\Str::slug($data['title']),
                ],
                array_merge($data, ['course_id' => $course->id])
            );

            if ($lesson->id === 2) {
                VideoQuiz::firstOrCreate(
                    ['lesson_id' => $lesson->id, 'timestamp_seconds' => 120],
                    [
                        'question' => 'What does HTML stand for?',
                        'options' => [
                            ['text' => 'Hyper Text Markup Language', 'is_correct' => true],
                            ['text' => 'High Tech Modern Language', 'is_correct' => false],
                            ['text' => 'Home Tool Markup Language', 'is_correct' => false],
                        ],
                        'order' => 0,
                    ]
                );
            }
        }

        $quiz = Quiz::firstOrCreate(
            [
                'course_id' => $course->id,
                'lesson_id' => null,
                'title' => 'Module 1 Quiz',
            ],
            [
                'description' => 'Test your understanding of HTML and CSS basics.',
                'duration_minutes' => 10,
                'order' => 0,
                'type' => 'practice',
                'passing_score' => 70,
                'max_attempts' => 3,
                'is_required' => false,
            ]
        );

        QuizQuestion::firstOrCreate(
            ['quiz_id' => $quiz->id, 'question' => 'Which tag is used for the largest heading?'],
            [
                'type' => 'multiple_choice',
                'options' => [
                    ['text' => '<h6>', 'is_correct' => false],
                    ['text' => '<h1>', 'is_correct' => true],
                    ['text' => '<head>', 'is_correct' => false],
                ],
                'order' => 0,
                'points' => 1,
            ]
        );

        Assignment::firstOrCreate(
            [
                'course_id' => $course->id,
                'title' => 'Build Your First Page',
            ],
            [
                'lesson_id' => null,
                'instructions' => 'Create a simple HTML page with a heading, paragraph, and list. Submit the HTML file.',
                'order' => 0,
                'max_score' => 100,
                'due_at' => now()->addDays(7),
                'is_required' => true,
            ]
        );
    }
}
