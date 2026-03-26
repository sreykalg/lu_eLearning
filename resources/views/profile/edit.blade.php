@php
$layout = auth()->user()->isAdmin() ? 'layouts.admin' : (auth()->user()->isStudent() ? 'layouts.student-inner' : (auth()->user()->isInstructor() ? 'layouts.instructor-inner' : (auth()->user()->isHeadOfDept() ? 'layouts.hod-inner' : 'layouts.app-inner')));
@endphp
@extends($layout)

@section('content')
@include('profile.partials.panel-content', ['user' => $user])
@endsection
