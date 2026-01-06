<?php

namespace App\Filament\Pages;

use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Infolists\Components\TextEntry;


class SolarPanelCounter extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedSun;

    protected string $view = 'filament.pages.solar-panel-counter';


    public ?array $data = [];

    /*     protected function getFormModel(): string
    {
        return \App\Models\SolarPanelCounter::class;
    }

    protected function getFormStatePath(): ?string
    {
        return 'data';
    } */



    public function mount(): void
    {
        $last = \App\Models\SolarPanelCounter::orderBy('date', 'desc')->first();

        $this->form->fill([
            'date' => $last
                ? \Carbon\Carbon::parse($last->date)->addDay()
                : now()->toDateString(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make()
                    ->schema([
                        DatePicker::make('date')
                            /*                             ->default(function () {
                                $last = \App\Models\SolarPanelCounter::orderBy('date', 'desc')->first();

                                return $last ? \Carbon\Carbon::parse($last->date)->addDay() : now()->toDateString();
                            }) */
                            ->required()


                            ->displayFormat('d/m/Y'),
                        TextInput::make('counter_reading')
                            ->required(),

                    ]),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        \App\Models\SolarPanelCounter::create($this->form->getState());

        // Nieuwe volgende datum berekenen
        $last = \App\Models\SolarPanelCounter::orderBy('date', 'desc')->first();

        $this->form->fill([
            'date' => $last
                ? \Carbon\Carbon::parse($last->date)->addDay()
                : now()->toDateString(),
            'counter_reading' => null, // optioneel leegmaken
        ]);


        Notification::make()
            ->title('Counter Reading created')
            ->success()
            ->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\SolarPanelCounter::query())
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('date')
                    ->date('d/m/Y'),
                TextColumn::make('counter_reading'),
            ])
            ->filters([
                // ...
            ])
        ->recordActions([
            EditAction::make()
            ->modalWidth('sm')   // of 'xs', 'md', 'lg', 'xl', '2xl'

                ->schema([
                    DatePicker::make('date')->required(),
                    TextInput::make('counter_reading')->required(),
                ])
                ,

            DeleteAction::make(),
        ]);

    }
}
