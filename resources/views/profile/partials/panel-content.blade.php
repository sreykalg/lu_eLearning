@php
    $user = $user ?? auth()->user();
    $name = $user?->name ?? 'User';
    $parts = array_filter(explode(' ', $name));
    $initials = count($parts) >= 2 ? Str::upper(mb_substr($parts[0],0,1).mb_substr($parts[count($parts)-1],0,1)) : Str::upper(mb_substr($name,0,2));
    $roleLabel = ucfirst(str_replace('_', ' ', $user?->role ?? 'student'));
    $photoUrl = $user && !empty($user->profile_photo_path) ? asset('storage/' . $user->profile_photo_path) : null;
@endphp
@if($user)
<style>
    .profile-panel .profile-shell { background: #fff; border: 1px solid #e5e7eb; border-radius: 0.9rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    .profile-panel .profile-hero { padding: 1.25rem 1.25rem 0.75rem; border-bottom: 1px solid #eef2f7; }
    .profile-panel .profile-hero-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
    .profile-panel .profile-person { display: flex; align-items: center; gap: 0.9rem; }
    .profile-panel .avatar-wrap { position: relative; width: 84px; height: 84px; flex-shrink: 0; }
    .profile-panel .profile-hero .avatar { width: 84px; height: 84px; border-radius: 50%; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.65rem; font-weight: 700; overflow: hidden; border: 3px solid #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.12); }
    .profile-panel .profile-hero .avatar img { width: 100%; height: 100%; object-fit: cover; }
    .profile-panel .profile-hero .avatar-add-btn { position: absolute; right: 0; bottom: 0; width: 28px; height: 28px; border-radius: 9999px; background: #0f172a; border: 2px solid #fff; color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .profile-panel .profile-hero .avatar-add-btn:hover { background: #1e293b; }
    .profile-panel .profile-hero .name { font-size: 1.35rem; font-weight: 700; color: #0f172a; margin: 0 0 0.2rem; }
    .profile-panel .profile-hero .email { color: #64748b; font-size: 0.88rem; margin: 0 0 0.4rem; }
    .profile-panel .profile-hero .role-chip { padding: 0.3rem 0.7rem; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; background: #f1f5f9; color: #0f172a; border-radius: 9999px; border: 1px solid #e2e8f0; }
    .profile-panel .profile-tabs { display: flex; align-items: center; gap: 1rem; margin-top: 0.85rem; border-top: 1px solid #f1f5f9; padding-top: 0.6rem; }
    .profile-panel .profile-tabs a { text-decoration: none; font-size: 0.875rem; color: #64748b; font-weight: 500; padding-bottom: 0.35rem; border-bottom: 2px solid transparent; }
    .profile-panel .profile-tabs a.active { color: #0f172a; border-bottom-color: #0f172a; }
    .profile-panel .profile-tabs a:hover { color: #0f172a; }
    .profile-panel .profile-body { padding: 1rem 1.25rem 1.25rem; }
    .profile-panel .profile-card { border: 0; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.06); overflow: hidden; margin-bottom: 1.25rem; }
    .profile-panel .profile-card .card-header-custom { background: #fff; padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 0.75rem; }
    .profile-panel .profile-card .card-header-custom .icon-wrap { width: 44px; height: 44px; border-radius: 0.5rem; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #fff; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .profile-panel .profile-card .card-header-custom h5 { font-weight: 600; color: #0f172a; margin: 0; font-size: 1rem; }
    .profile-panel .profile-card .card-header-custom p { color: #64748b; font-size: 0.8125rem; margin: 0.25rem 0 0 0; }
    .profile-panel .profile-card .card-body { padding: 1.5rem; }
    .profile-panel .profile-card .form-label { font-weight: 500; color: #334155; font-size: 0.875rem; }
    .profile-panel .profile-card .form-control { border-radius: 0.5rem; border: 1px solid #e2e8f0; padding: 0.6rem 0.9rem; }
    .profile-panel .profile-card .form-control:focus { border-color: #0f172a; box-shadow: 0 0 0 3px rgba(15,23,42,0.1); }
    .profile-panel .profile-card .btn-save { background: #0f172a; color: #fff; border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 600; }
    .profile-panel .profile-card .btn-save:hover { background: #1e293b; color: #fff; }
    .profile-panel .profile-card-delete .card-header-custom .icon-wrap { background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); }
    .profile-panel .profile-card-delete .btn-danger { border-radius: 0.5rem; padding: 0.5rem 1.25rem; font-weight: 600; }
</style>
<div class="profile-panel">
    <div class="profile-shell">
        <div class="profile-hero">
            <div class="profile-hero-row">
                <div class="profile-person">
                    <div class="avatar-wrap">
                        <label for="profile_photo_input" class="avatar">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="{{ $name }}">
                            @else
                                <span>{{ $initials }}</span>
                            @endif
                        </label>
                        <label for="profile_photo_input" class="avatar-add-btn" title="{{ __('Add picture') }}">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </label>
                    </div>
                    <div>
                        <h3 class="name">{{ $name }}</h3>
                        <p class="email">{{ $user?->email }}</p>
                        <span class="role-chip">{{ $roleLabel }}</span>
                    </div>
                </div>
            </div>
            <div class="profile-tabs">
                <a href="#profile-info" class="active">{{ __('Profile') }}</a>
                <a href="#profile-password">{{ __('Password') }}</a>
                <a href="#profile-account">{{ __('Account') }}</a>
            </div>
        </div>
        <div class="profile-body">

    <div id="profile-info" class="profile-card card border-0 shadow-sm">
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
            @include('profile.partials.update-profile-information-form', ['user' => $user, 'inModal' => true])
        </div>
    </div>

    <div id="profile-password" class="profile-card card border-0 shadow-sm">
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

    <div id="profile-account" class="profile-card profile-card-delete card border-0 shadow-sm">
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
    </div>
</div>
@push('scripts')
<script>
document.getElementById('profile_photo_input')?.addEventListener('change', function () {
    var file = this.files && this.files[0];
    if (!file || !file.type.startsWith('image/')) return;
    var avatar = document.querySelector('.profile-panel .avatar');
    if (!avatar) return;
    var reader = new FileReader();
    reader.onload = function () {
        avatar.innerHTML = '<img src="' + reader.result + '" alt="Profile photo preview">';
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
@endif
