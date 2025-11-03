<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CandidateController extends Controller
{
    // Display list with filters
    public function index(Request $request)
    {
        $query = Candidate::query();

        if ($request->filled('tier')) {
            $query->where('tier', (int) $request->get('tier'));
        }

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'created_at');
        $dir = $request->get('dir', 'desc');
        if (!in_array($sort, ['created_at', 'name', 'email', 'tier'])) {
            $sort = 'created_at';
        }
        if (!in_array($dir, ['asc', 'desc'])) {
            $dir = 'desc';
        }

        // Only select columns used in the view to reduce payload
        $query->select(['id', 'name', 'email', 'phone', 'tier', 'created_at']);

        $candidates = $query->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();

        // Cache tier stats to avoid multiple COUNT(*) queries per request
        $stats = Cache::remember('candidates:tier-stats', 300, function (): array {
            $grouped = Candidate::query()
                ->selectRaw('tier, COUNT(*) as aggregate')
                ->groupBy('tier')
                ->pluck('aggregate', 'tier')
                ->all();

            // Ensure all tiers 0..5 are present
            $result = [];
            for ($i = 0; $i <= 5; $i++) {
                $result[$i] = (int) ($grouped[$i] ?? 0);
            }

            return $result;
        });

        return view('candidates.index', compact('candidates', 'stats', 'sort', 'dir'));
    }

    // Registration form
    public function create()
    {
        return view('candidates.create');
    }

    // Store and auto-assign tier
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:candidates,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            // assessment fields
            'knows_html_css_js' => ['required', 'boolean'],
            'knows_react_next' => ['required', 'in:none,basic,advanced'],
            'can_build_crud_with_db' => ['required', 'boolean'],
            'can_auth_password_google' => ['required', 'boolean'],
            'knows_express_hono_or_laravel' => ['required', 'in:none,basic,proficient'],
            'knows_golang' => ['required', 'boolean'],
        ]);

        $assessment = [
            'knows_html_css_js' => (bool)$data['knows_html_css_js'],
            'knows_react_next' => $data['knows_react_next'],
            'can_build_crud_with_db' => (bool)$data['can_build_crud_with_db'],
            'can_auth_password_google' => (bool)$data['can_auth_password_google'],
            'knows_express_hono_or_laravel' => $data['knows_express_hono_or_laravel'],
            'knows_golang' => (bool)$data['knows_golang'],
        ];

        $tier = $this->determineTier($assessment);

        $candidate = Candidate::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'assessment' => $assessment,
            'tier' => $tier,
        ]);

        // Invalidate cached stats after creating a new candidate
        Cache::forget('candidates:tier-stats');

        return redirect()->route('candidates.show', $candidate)->with('status', 'Candidate registered and categorized into Tier '.$tier.'.');
    }

    public function show(Candidate $candidate)
    {
        return view('candidates.show', compact('candidate'));
    }

    private function determineTier(array $a): int
    {
        // Follows the provided tier definitions; simple heuristic mapping
        // Tier 4 requires Golang knowledge plus full-stack skills
        if (($a['knows_golang'] ?? false) &&
            ($a['knows_express_hono_or_laravel'] === 'proficient' || $a['knows_express_hono_or_laravel'] === 'basic') &&
            ($a['can_auth_password_google'] ?? false)) {
            return 4; // Advanced Full-Stack (Go aware)
        }

        // Tier 3: Can build authenticated CRUD apps and authenticated CRUD APIs with Express/Hono or Laravel, no Go
        if (($a['can_auth_password_google'] ?? false) &&
            in_array($a['knows_express_hono_or_laravel'], ['basic','proficient'], true) &&
            !$a['knows_golang']) {
            return 3;
        }

        // Tier 2: Authenticated CRUD app with Next.js; deployable; may not know backend frameworks
        if (($a['can_auth_password_google'] ?? false) && $a['knows_react_next'] !== 'none') {
            return 2;
        }

        // Tier 1: Can build CRUD with DB but no auth
        if (($a['can_build_crud_with_db'] ?? false) && !($a['can_auth_password_google'] ?? false)) {
            return 1;
        }

        // Tier 0: Beginner
        if ($a['knows_html_css_js'] && in_array($a['knows_react_next'], ['none','basic'])) {
            return 0;
        }

        // Fallback to Tier 0
        return 0;
    }
}
