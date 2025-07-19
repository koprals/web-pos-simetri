<?php

namespace App\Filament\Resources\RentalEquipmentResource\Pages;

use App\Filament\Resources\RentalEquipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRentalEquipment extends ListRecords
{
    protected static string $resource = RentalEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
