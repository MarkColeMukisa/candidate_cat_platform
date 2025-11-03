<?php

namespace App\Filament\Resources\Candidates\Pages;

use App\Filament\Resources\Candidates\CandidateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCandidate extends CreateRecord
{
    protected static string $resource = CandidateResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $assessment = [
            'knows_html_css_js' => (bool) ($data['knows_html_css_js'] ?? false),
            'knows_react_next' => $data['knows_react_next'] ?? 'none',
            'can_build_crud_with_db' => (bool) ($data['can_build_crud_with_db'] ?? false),
            'can_auth_password_google' => (bool) ($data['can_auth_password_google'] ?? false),
            'knows_express_hono_or_laravel' => $data['knows_express_hono_or_laravel'] ?? 'none',
            'knows_golang' => (bool) ($data['knows_golang'] ?? false),
        ];

        $data['assessment'] = $assessment;
        $data['tier'] = $this->determineTier($assessment);

        // Remove non-column form fields so they aren't persisted as columns
        unset(
            $data['knows_html_css_js'],
            $data['knows_react_next'],
            $data['can_build_crud_with_db'],
            $data['can_auth_password_google'],
            $data['knows_express_hono_or_laravel'],
            $data['knows_golang']
        );

        return $data;
    }

    private function determineTier(array $a): int
    {
        if (($a['knows_golang'] ?? false) &&
            (in_array($a['knows_express_hono_or_laravel'], ['basic', 'proficient'], true)) &&
            ($a['can_auth_password_google'] ?? false)) {
            return 4;
        }

        if (($a['can_auth_password_google'] ?? false) &&
            in_array($a['knows_express_hono_or_laravel'], ['basic', 'proficient'], true) &&
            !($a['knows_golang'] ?? false)) {
            return 3;
        }

        if (($a['can_auth_password_google'] ?? false) && ($a['knows_react_next'] ?? 'none') !== 'none') {
            return 2;
        }

        if (($a['can_build_crud_with_db'] ?? false) && !($a['can_auth_password_google'] ?? false)) {
            return 1;
        }

        if (($a['knows_html_css_js'] ?? false) && in_array(($a['knows_react_next'] ?? 'none'), ['none', 'basic'], true)) {
            return 0;
        }

        return 0;
    }
}
