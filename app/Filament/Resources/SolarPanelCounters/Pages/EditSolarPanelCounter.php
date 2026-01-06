<?php

namespace App\Filament\Resources\SolarPanelCounters\Pages;

use App\Filament\Resources\SolarPanelCounters\SolarPanelCounterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSolarPanelCounter extends EditRecord
{
    protected static string $resource = SolarPanelCounterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
