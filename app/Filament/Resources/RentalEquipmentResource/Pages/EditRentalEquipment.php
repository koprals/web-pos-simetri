<?php

namespace App\Filament\Resources\RentalEquipmentResource\Pages;

use App\Filament\Resources\RentalEquipmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRentalEquipment extends EditRecord
{
    protected static string $resource = RentalEquipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
