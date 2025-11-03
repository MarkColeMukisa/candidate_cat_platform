@extends('layouts.app')

@section('content')
<nav class="breadcrumb">Home / <a class="link" href="{{ route('candidates.index') }}">Candidates</a> / {{ $candidate->name }}</nav>

<div class="grid cols-2">
    <div class="card">
        <h2 class="mb-2">Candidate</h2>
        <div class="mb-2"><strong>Name:</strong> {{ $candidate->name }}</div>
        <div class="mb-2"><strong>Email:</strong> {{ $candidate->email }}</div>
        <div class="mb-2"><strong>Phone:</strong> {{ $candidate->phone ?: '—' }}</div>
        <div class="mb-2"><strong>Tier:</strong> <span class="badge">Tier {{ $candidate->tier }}</span></div>
        <div class="muted">Registered {{ $candidate->created_at->toDayDateTimeString() }}</div>
        <div class="mt-4">
            <a href="{{ route('candidates.index') }}" class="btn secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <h2 class="mb-2">Assessment</h2>
        @php($a = $candidate->assessment ?? [])
        <ul style="margin:0; padding-left:1rem">
            <li>HTML/CSS/JS: <strong>{{ !empty($a['knows_html_css_js']) ? 'Yes' : 'No' }}</strong></li>
            <li>React/Next: <strong>{{ $a['knows_react_next'] ?? '—' }}</strong></li>
            <li>CRUD with DB: <strong>{{ !empty($a['can_build_crud_with_db']) ? 'Yes' : 'No' }}</strong></li>
            <li>Auth (password + Google): <strong>{{ !empty($a['can_auth_password_google']) ? 'Yes' : 'No' }}</strong></li>
            <li>Express/Hono/Laravel: <strong>{{ $a['knows_express_hono_or_laravel'] ?? '—' }}</strong></li>
            <li>Golang: <strong>{{ !empty($a['knows_golang']) ? 'Yes' : 'No' }}</strong></li>
        </ul>
    </div>
</div>
@endsection
