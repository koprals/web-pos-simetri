<?php

namespace App\Filament\Resources\RentalCourtResource\Pages;

use App\Filament\Resources\RentalCourtResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRentalCourt extends EditRecord
{
    protected static string $resource = RentalCourtResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
