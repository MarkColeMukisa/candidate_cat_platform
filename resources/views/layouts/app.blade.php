<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Candidate Platform') }}</title>
    <style>
        :root { --c:#1f2937; --a:#2563eb; --bg:#f9fafb; --muted:#6b7280; --ok:#16a34a; --bad:#dc2626; }
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji'; margin:0; background:var(--bg); color:var(--c);}
        .container { max-width: 1000px; margin: 0 auto; padding: 1.25rem; }
        header { display:flex; align-items:center; justify-content:space-between; padding:1rem 0; }
        a { color: var(--a); text-decoration:none; }
        .btn { background: var(--a); color:#fff; padding:.5rem .9rem; border-radius:.4rem; border:none; cursor:pointer; display:inline-block; }
        .btn.secondary { background:#111827; }
        .card { background:#fff; border:1px solid #e5e7eb; border-radius:.6rem; padding:1rem; }
        .grid { display:grid; gap:1rem; }
        .grid.cols-2 { grid-template-columns: 1fr 1fr; }
        .grid.cols-3 { grid-template-columns: repeat(3, 1fr); }
        .mb-2 { margin-bottom:.5rem; } .mb-4{margin-bottom:1rem;} .mt-2{margin-top:.5rem;} .mt-4{margin-top:1rem;} .mt-6{margin-top:1.5rem;}
        label { font-weight:600; display:block; margin-bottom:.25rem; }
        input[type=text], input[type=email], select { width:100%; padding:.55rem .65rem; border:1px solid #d1d5db; border-radius:.4rem; }
        .row { display:flex; gap:.5rem; align-items:center; }
        .table { width:100%; border-collapse: collapse; }
        .table th, .table td { padding:.6rem .5rem; border-bottom:1px solid #e5e7eb; text-align:left; }
        .badge { display:inline-block; padding:.2rem .5rem; border-radius:999px; font-size:.85rem; background:#eef2ff; color:#3730a3; }
        .stats { display:flex; gap:.5rem; flex-wrap: wrap; }
        .stat { background:#fff; border:1px solid #e5e7eb; border-radius:.6rem; padding:.5rem .75rem; }
        .alert { padding:.6rem .75rem; border-radius:.4rem; }
        .alert.ok { background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; }
        nav.breadcrumb { font-size:.9rem; color: var(--muted); margin-bottom:.5rem; }
        .muted { color: var(--muted); }
        .filters { display:flex; gap:.5rem; flex-wrap:wrap; align-items:end; }
        .link { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div>
                <a href="{{ route('candidates.index') }}"><strong>{{ config('app.name', 'Candidate Platform') }}</strong></a>
            </div>
            <div class="row">
                <a class="btn" href="{{ route('candidates.create') }}">Register Candidate</a>
            </div>
        </header>

        @if(session('status'))
            <div class="alert ok mb-4">{{ session('status') }}</div>
        @endif

        {{ $slot ?? '' }}

        @yield('content')
    </div>
</body>
</html>
