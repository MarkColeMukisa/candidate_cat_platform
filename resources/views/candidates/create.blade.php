@extends('layouts.app')

@section('content')
<nav class="breadcrumb">Home / Register Candidate</nav>
<div class="card">
    <form method="post" action="{{ route('candidates.store') }}" class="grid cols-2">
        @csrf
        <div>
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')<div class="muted">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')<div class="muted">{{ $message }}</div>@enderror
        </div>
        <div>
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
            @error('phone')<div class="muted">{{ $message }}</div>@enderror
        </div>
        <div></div>

        <div class="mb-2" style="grid-column: span 2">
            <strong>Skill Assessment</strong>
        </div>

        <div>
            <label>Knows HTML, CSS, and basic JavaScript?</label>
            <select name="knows_html_css_js" required>
                <option value="1" @selected(old('knows_html_css_js')==='1')>Yes</option>
                <option value="0" @selected(old('knows_html_css_js')==='0')>No</option>
            </select>
            @error('knows_html_css_js')<div class="muted">{{ $message }}</div>@enderror
        </div>

        <div>
            <label>Knowledge of React/Next.js</label>
            <select name="knows_react_next" required>
                <option value="none" @selected(old('knows_react_next')==='none')>None</option>
                <option value="basic" @selected(old('knows_react_next','basic')==='basic')>Basic</option>
                <option value="advanced" @selected(old('knows_react_next')==='advanced')>Advanced</option>
            </select>
            @error('knows_react_next')<div class="muted">{{ $message }}</div>@enderror
        </div>

        <div>
            <label>Can build a CRUD app with a database?</label>
            <select name="can_build_crud_with_db" required>
                <option value="1" @selected(old('can_build_crud_with_db')==='1')>Yes</option>
                <option value="0" @selected(old('can_build_crud_with_db')==='0')>No</option>
            </select>
            @error('can_build_crud_with_db')<div class="muted">{{ $message }}</div>@enderror
        </div>

        <div>
            <label>Can implement authentication (password + Google)?</label>
            <select name="can_auth_password_google" required>
                <option value="1" @selected(old('can_auth_password_google')==='1')>Yes</option>
                <option value="0" @selected(old('can_auth_password_google')==='0')>No</option>
            </select>
            @error('can_auth_password_google')<div class="muted">{{ $message }}</div>@enderror
        </div>

        <div>
            <label>Backend frameworks (Express/Hono/Laravel)</label>
            <select name="knows_express_hono_or_laravel" required>
                <option value="none" @selected(old('knows_express_hono_or_laravel')==='none')>None</option>
                <option value="basic" @selected(old('knows_express_hono_or_laravel','basic')==='basic')>Basic</option>
                <option value="proficient" @selected(old('knows_express_hono_or_laravel')==='proficient')>Proficient</option>
            </select>
            @error('knows_express_hono_or_laravel')<div class="muted">{{ $message }}</div>@enderror
        </div>

        <div>
            <label>Knows Golang?</label>
            <select name="knows_golang" required>
                <option value="1" @selected(old('knows_golang')==='1')>Yes</option>
                <option value="0" @selected(old('knows_golang')==='0')>No</option>
            </select>
            @error('knows_golang')<div class="muted">{{ $message }}</div>@enderror
        </div>

        <div style="grid-column: span 2">
            <button type="submit" class="btn">Submit</button>
            <a href="{{ route('candidates.index') }}" class="btn secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
