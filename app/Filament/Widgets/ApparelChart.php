<?php

namespace App\Filament\Widgets;

use App\Models\OrderProduct;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ApparelChart extends ChartWidget
{
    protected static ?string $heading = 'Apparel';

    protected static ?int $sort = 4;

    public ?string $filter = 'today';

    protected static string $color = 'success';

    protected static bool $canView = true;

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $dateRange = match ($activeFilter) {
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

        $timeColumn = 'order_products.created_on';

        // Query dasar dengan join dan filter kategori
        $baseQuery = OrderProduct::query()
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('categories.slug', 'fnb');

        // Gunakan Trend untuk agregasi data
        $trend = Trend::query($baseQuery)
            ->dateColumn($timeColumn)
            ->between(
                start: $dateRange['start'],
                end: $dateRange['end'],
            );

        $trendData = match ($dateRange['period']) {
            'perHour' => $trend->perHour(),
            'perDay' => $trend->perDay(),
            'perMonth' => $trend->perMonth(),
        };

        // Hitung total sales langsung tanpa alias
        $trendData = $trendData->sum('order_products.quantity * order_products.unit_price');

        $labels = $trendData->map(function (TrendValue $value) use ($dateRange) {
            $date = Carbon::parse($value->date);

            return match ($dateRange['period']) {
                'perHour' => $date->format('H:i'),
                'perDay' => $date->format('d M'),
                'perMonth' => $date->format('M Y'),
            };
        });

        return [
            'datasets' => [
                [
                    'label' => 'Penjualan '.$this->getFilters()[$activeFilter],
                    'data' => $trendData->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $labels,
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
        return 'line'; // Ganti ke 'bar' jika mau grafik batang
    }
}
