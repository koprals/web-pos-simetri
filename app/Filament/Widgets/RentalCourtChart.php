<?php

namespace App\Filament\Widgets;

use App\Models\RentalCourt;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RentalCourtChart extends ChartWidget
{
    protected static ?string $heading = 'Sewa Lapangan';

    protected static ?int $sort = 5;

    public ?string $filter = 'today';

    protected static string $color = 'success';

    protected static bool $canView = true;

    protected int|string|array $columnSpan = '2';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $dateRange = $this->getDateRange($activeFilter);

        $query = Trend::model(RentalCourt::class)
            ->between($dateRange['start'], $dateRange['end']);

        $trendQuery = match ($dateRange['period']) {
            'perHour' => $query->perHour(),
            'perDay' => $query->perDay(),
            default => $query->perMonth(),
        };

        $trendData = $trendQuery->sum('total_price');

        $labels = $trendData->map(fn (TrendValue $value) => $this->formatDate($value->date, $dateRange['period']));

        $datasets = $trendData->map(fn (TrendValue $value) => $value->aggregate);

        return [
            'datasets' => [
                [
                    'label' => 'Sewa Lapangan '.$this->getFilters()[$activeFilter],
                    'data' => $datasets,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getDateRange(string $filter): array
    {
        return match ($filter) {
            'today' => [
                'start' => now()->startOfDay(),
                'end' => now()->endOfDay(),
                'period' => 'perHour',
            ],
            'week' => [
                'start' => now()->startOfWeek(),
                'end' => now()->endOfWeek(),
                'period' => 'perDay',
            ],
            'month' => [
                'start' => now()->startOfMonth(),
                'end' => now()->endOfMonth(),
                'period' => 'perDay',
            ],
            'year' => [
                'start' => now()->startOfYear(),
                'end' => now()->endOfYear(),
                'period' => 'perMonth',
            ],
        };
    }

    protected function formatDate(string $date, string $period): string
    {
        $parsedDate = Carbon::parse($date);

        return match ($period) {
            'perHour' => $parsedDate->format('H:i'),
            'perDay' => $parsedDate->format('d M'),
            'perMonth' => $parsedDate->format('M Y'),
        };
    }
}
