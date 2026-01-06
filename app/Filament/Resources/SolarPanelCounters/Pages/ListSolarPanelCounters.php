<?php

namespace App\Filament\Resources\SolarPanelCounters\Pages;

use App\Filament\Resources\SolarPanelCounters\SolarPanelCounterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSolarPanelCounters extends ListRecords
{
    protected static string $resource = SolarPanelCounterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
