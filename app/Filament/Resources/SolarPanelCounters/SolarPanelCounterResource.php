<?php

namespace App\Filament\Resources\SolarPanelCounters;

use App\Filament\Resources\SolarPanelCounters\Pages\CreateSolarPanelCounter;
use App\Filament\Resources\SolarPanelCounters\Pages\EditSolarPanelCounter;
use App\Filament\Resources\SolarPanelCounters\Pages\ListSolarPanelCounters;
use App\Filament\Resources\SolarPanelCounters\Schemas\SolarPanelCounterForm;
use App\Filament\Resources\SolarPanelCounters\Tables\SolarPanelCountersTable;
use App\Models\SolarPanelCounter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SolarPanelCounterResource extends Resource
{
    protected static ?string $model = SolarPanelCounter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SolarPanelCounterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SolarPanelCountersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSolarPanelCounters::route('/'),
            'create' => CreateSolarPanelCounter::route('/create'),
            'edit' => EditSolarPanelCounter::route('/{record}/edit'),
        ];
    }
}
