<?php

namespace App\Filament\Pages;

use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;

class Category extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string | \BackedEnum | null $navigationIcon = Heroicon::OutlinedTag;

    protected string $view = 'filament.pages.category';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $state, Set $set): void {
                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->required(),
                        Select::make('parent_id')
                            ->label('Parent Category')
                            ->options(fn() => \App\Models\Category::whereNull('parent_id')->pluck('name', 'id')),
                        Textarea::make('description'),
                    ]),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        \App\Models\Category::create($this->form->getState());

        $this->form->fill();

        Notification::make()
            ->title('Category created')
            ->success()
            ->send();

    }

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Category::query())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('slug'),
                TextColumn::make('description')
                    ->default('-')
                    ->limit(50),
            ])
            ->filters([
                // ...
            ])
            ->recordActions([
                // ...
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
