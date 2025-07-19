<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OmsetChart extends ChartWidget
{
    protected static ?string $heading = 'Omset';

    protected static ?int $sort = 1;

    public ?string $filter = 'today';

    protected static string $color = 'success';

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $dateRange = $this->getDateRange($activeFilter);

        $query = Trend::model(Order::class)
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
                    'label' => 'Omset '.$this->getFilters()[$activeFilter],
                    'data' => $datasets,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getContainerAttributes(): array
    {
        return [
            'class' => 'rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900',
        ];
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

    protected function getType(): string
    {
        return 'line';
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
