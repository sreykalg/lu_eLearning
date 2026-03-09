<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
}
