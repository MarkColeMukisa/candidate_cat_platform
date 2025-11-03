<?php

namespace App\Filament\Widgets;

use App\Models\Candidate;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class CandidateStatsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Candidate Stats';
    protected function getStats(): array
    {
        $total = Cache::remember('candidates:total-count', 300, function (): int {
            return (int) Candidate::query()->count();
        });

        return [
            Stat::make('Total Candidates', $total)
                ->description('All registered candidates')
                ->descriptionIcon('heroicon-o-users', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
        ];
    }
}
