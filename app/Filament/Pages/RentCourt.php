<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class RentCourt extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Pemesanan';

    protected static ?string $navigationLabel = 'Sewa Lapangan';

    protected static string $view = 'filament.pages.rent-court';

    protected static ?int $navigationSort = 6;
}
