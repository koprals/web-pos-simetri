<?php

namespace App\Filament\Resources\RentalCourtResource\Pages;

use App\Filament\Resources\RentalCourtResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRentalCourts extends ListRecords
{
    protected static string $resource = RentalCourtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
