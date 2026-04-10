<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->orderBy('name');
        if ($request->filled('role')) {
            if ($request->role === 'head_of_dept') {
                $query->whereIn('role', ['head_of_dept', 'admin']);
            } else {
                $query->where('role', $request->role);
            }
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
            });
        }
        $users = $query->paginate(15)->withQueryString();
        return view('hod.users', compact('users'));
    }

    public function instructors(Request $request): View
    {
        $query = $this->scopedInstructorsQuery($request)->orderBy('name');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function (Builder $qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $instructors = $query->paginate(15)->withQueryString();

        return view('hod.instructors.index', compact('instructors'));
    }

    public function storeInstructor(Request $request): RedirectResponse
    {
        $hod = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
            'department' => ['nullable', 'string', 'max:120'],
        ]);

        $department = $hod->isAdmin()
            ? ($validated['department'] ?: null)
            : ($hod->department ?: null);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'instructor',
            'department' => $department,
        ]);

        return redirect()->route('hod.instructors.index')->with('success', 'Instructor account created.');
    }

    public function updateInstructor(Request $request, User $instructor): RedirectResponse
    {
        abort_unless($this->canManageInstructor($request, $instructor), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($instructor->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'department' => ['nullable', 'string', 'max:120'],
        ]);

        $hod = $request->user();
        $instructor->name = $validated['name'];
        $instructor->email = $validated['email'];
        $instructor->department = $hod->isAdmin()
            ? ($validated['department'] ?: null)
            : ($hod->department ?: null);

        if (! empty($validated['password'])) {
            $instructor->password = Hash::make($validated['password']);
        }

        $instructor->save();

        return redirect()->route('hod.instructors.index')->with('success', 'Instructor account updated.');
    }

    public function destroyInstructor(Request $request, User $instructor): RedirectResponse
    {
        abort_unless($this->canManageInstructor($request, $instructor), 403);

        $instructor->delete();

        return redirect()->route('hod.instructors.index')->with('success', 'Instructor account deleted.');
    }

    private function scopedInstructorsQuery(Request $request): Builder
    {
        $hod = $request->user();
        $query = User::query()->where('role', 'instructor');

        if ($hod->isAdmin()) {
            return $query;
        }

        if (empty($hod->department)) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('department', $hod->department);
    }

    private function canManageInstructor(Request $request, User $instructor): bool
    {
        if ($instructor->role !== 'instructor') {
            return false;
        }

        $hod = $request->user();
        if ($hod->isAdmin()) {
            return true;
        }

        if (empty($hod->department)) {
            return false;
        }

        return $instructor->department === $hod->department;
    }
}
