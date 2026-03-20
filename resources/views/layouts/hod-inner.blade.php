@extends('layouts.app-inner')

@section('roleLabel', 'HEAD OF DEPT')

@section('sidebar-nav')
    <a class="nav-link {{ request()->routeIs('overview') ? 'active' : '' }}" href="{{ route('overview') }}" data-drawer-close>
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        <span>Course Overview</span>
    </a>
    <a class="nav-link {{ request()->routeIs('hod.dashboard') ? 'active' : '' }}" href="{{ route('hod.dashboard') }}" data-drawer-close>
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
        <span>Dashboard</span>
    </a>
    <a class="nav-link {{ request()->routeIs('hod.approval.*') ? 'active' : '' }}" href="{{ route('hod.approval') }}" data-drawer-close>
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        <span>Course Approval</span>
    </a>
    <a class="nav-link {{ request()->routeIs('hod.reports.*') ? 'active' : '' }}" href="{{ route('hod.reports') }}" data-drawer-close>
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        <span>Reports</span>
    </a>
    <a class="nav-link {{ request()->routeIs('hod.students.*') ? 'active' : '' }}" href="{{ route('hod.students.index') }}" data-drawer-close>
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        <span>Student Enrollment</span>
    </a>
    <a class="nav-link {{ request()->routeIs('hod.users.*') ? 'active' : '' }}" href="{{ route('hod.users') }}" data-drawer-close>
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        <span>User Management</span>
    </a>
@endsection
