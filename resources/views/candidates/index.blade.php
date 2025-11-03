@extends('layouts.app')

@section('content')
<nav class="breadcrumb">Home / Candidates</nav>
<div class="grid cols-3 mb-4">
    <div class="card">
        <div class="muted mb-2">Total</div>
        <div><strong>{{ $candidates->total() }}</strong> candidates</div>
    </div>
    <div class="card">
        <div class="muted mb-2">By Tier</div>
        <div class="stats">
            @for($i=0;$i<=5;$i++)
                <div class="stat">T{{ $i }}: <strong>{{ $stats[$i] ?? 0 }}</strong></div>
            @endfor
        </div>
    </div>
    <div class="card">
        <div class="muted mb-2">Actions</div>
        <a class="btn" href="{{ route('candidates.create') }}">Register Candidate</a>
    </div>
</div>

<div class="card mb-4">
    <form class="filters" method="get" action="{{ route('candidates.index') }}">
        <div>
            <label for="q">Search</label>
            <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="Search name, email, phone" />
        </div>
        <div>
            <label for="tier">Tier</label>
            <select name="tier" id="tier">
                <option value="">All</option>
                @for($i=0;$i<=5;$i++)
                    <option value="{{ $i }}" @selected((string)request('tier')===(string)$i)>Tier {{ $i }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label for="sort">Sort</label>
            <select name="sort" id="sort">
                <option value="created_at" @selected(request('sort','created_at')==='created_at')>Created</option>
                <option value="name" @selected(request('sort')==='name')>Name</option>
                <option value="email" @selected(request('sort')==='email')>Email</option>
                <option value="tier" @selected(request('sort')==='tier')>Tier</option>
            </select>
        </div>
        <div>
            <label for="dir">Direction</label>
            <select name="dir" id="dir">
                <option value="desc" @selected(request('dir','desc')==='desc')>Desc</option>
                <option value="asc" @selected(request('dir')==='asc')>Asc</option>
            </select>
        </div>
        <div>
            <label>&nbsp;</label>
            <button class="btn" type="submit">Apply</button>
        </div>
    </form>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Tier</th>
                <th>Registered</th>
            </tr>
        </thead>
        <tbody>
            @forelse($candidates as $c)
                <tr>
                    <td><a class="link" href="{{ route('candidates.show', $c) }}">{{ $c->name }}</a></td>
                    <td>{{ $c->email }}</td>
                    <td>{{ $c->phone }}</td>
                    <td><span class="badge">Tier {{ $c->tier }}</span></td>
                    <td class="muted">{{ $c->created_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="muted">No candidates found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $candidates->links() }}
    </div>
</div>
@endsection
