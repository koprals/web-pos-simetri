<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PosPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Pemesanan';

    protected static ?string $navigationLabel = 'FnB & Apparel';

    protected static string $view = 'filament.pages.pos-page';

    protected static ?int $navigationSort = 4;
}
