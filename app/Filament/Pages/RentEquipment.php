<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class RentEquipment extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';

    protected static ?string $navigationGroup = 'Pemesanan';

    protected static ?string $navigationLabel = 'Sewa Alat';

    protected static string $view = 'filament.pages.rent-equipment';

    protected static ?int $navigationSort = 5;
}
