<?php

namespace App\Filament\Resources\SolarPanelCounters\Schemas;

use Dom\Text;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\DatePicker;

class SolarPanelCounterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')->required(),
                TextInput::make('counter_reading')->required(),
            ]);
    }
}
