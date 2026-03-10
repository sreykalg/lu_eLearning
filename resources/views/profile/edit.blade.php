@php
$layout = auth()->user()->isAdmin() ? 'layouts.admin' : (auth()->user()->isStudent() ? 'layouts.student-inner' : (auth()->user()->isInstructor() ? 'layouts.instructor-inner' : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.app-inner')));
@endphp
@extends($layout)

@push('styles')
<style>
    .profile-page .profile-header { margin-bottom: 1.5rem; }
    .profile-page .profile-hero { display: flex; flex-direction: column; align-items: center; padding: 2rem 1rem 2.5rem; margin-bottom: 1.5rem; }
    .profile-page .profile-hero .name { font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem; }
    .profile-page .profile-hero .avatar { width: 96px; height: 96px; border-radius: 50%; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 600; margin-bottom: 0.75rem; }
    .profile-page .profile-hero .role-chip { padding: 0.35rem 0.85rem; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; background: #0f172a; color: #fff; border-radius: 9999px; }
    .profile-page .profile-header p { color: #64748b; font-size: 0.9375rem; margin-bottom: 0; }
    .profile-page .profile-card { border: 0; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06); overflow: hidden; margin-bottom: 1.5rem; }
    .profile-page .profile-card .card-header-custom { background: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.75rem; }
    .profile-page .profile-card .card-header-custom .icon-wrap { width: 44px; height: 44px; border-radius: 0.5rem; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .profile-page .profile-card .card-header-custom h5 { font-weight: 600; color: #0f172a; margin: 0; font-size: 1rem; }
    .profile-page .profile-card .card-header-custom p { color: #64748b; font-size: 0.8125rem; margin: 0.25rem 0 0 0; }
    .profile-page .profile-card .card-body { padding: 1.5rem; }
    .profile-page .profile-card .form-label { font-weight: 500; color: #334155; font-size: 0.875rem; }
    .profile-page .profile-card .form-control { border-radius: 0.5rem; border: 1px solid #e2e8f0; padding: 0.6rem 0.9rem; }
    .profile-page .profile-card .form-control:focus { border-color: #0f172a; box-shadow: 0 0 0 3px rgba(15,23,42,0.1); }
    .profile-page .profile-card .btn-save { background: #0f172a; color: #fff; border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 600; }
    .profile-page .profile-card .btn-save:hover { background: #1e293b; color: #fff; }
    .profile-page .profile-card-delete .card-header-custom .icon-wrap { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); }
    .profile-page .profile-card-delete .btn-danger { border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 600; }
</style>
@endpush

@section('content')
<div class="profile-page">
    

    @php
        $name = $user->name ?? 'User';
        $parts = array_filter(explode(' ', $name));
        $initials = count($parts) >= 2 ? Str::upper(mb_substr($parts[0],0,1).mb_substr($parts[count($parts)-1],0,1)) : Str::upper(mb_substr($name,0,2));
        $roleLabel = ucfirst(str_replace('_', ' ', $user->role ?? 'student'));
    @endphp
    <div class="profile-hero">
        <div class="avatar">{{ $initials }}</div>
        <div class="name">{{ $name }}</div>
        <span class="role-chip">{{ $roleLabel }}</span>
    </div>

    <div class="profile-card card border-0 shadow-sm">
        <div class="card-header-custom">
            <div class="icon-wrap">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
                <h5>{{ __('Profile Information') }}</h5>
                <p>{{ __("Update your account's profile information and email address.") }}</p>
            </div>
        </div>
        <div class="card-body">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="profile-card card border-0 shadow-sm">
        <div class="card-header-custom">
            <div class="icon-wrap">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <h5>{{ __('Update Password') }}</h5>
                <p>{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
            </div>
        </div>
        <div class="card-body">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="profile-card profile-card-delete card border-0 shadow-sm">
        <div class="card-header-custom">
            <div class="icon-wrap">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <div>
                <h5 class="text-danger">{{ __('Delete Account') }}</h5>
                <p>{{ __('Permanently delete your account and all associated data.') }}</p>
            </div>
        </div>
        <div class="card-body">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
