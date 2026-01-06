## Create Form and Table on the Same Page

This project demonstrates how to create a custom page to show form and table next to each other.

The repository contains the complete Laravel + Filament project to demonstrate the functionality, including migrations/seeds for the demo data.

The Filament project is in the `app/Filament` folder.

Feel free to pick the parts that you actually need in your projects.

---

## How to install

- Clone the repository with `git clone`
- Copy the `.env.example` file to `.env` and edit database credentials there
- Run `composer install`
- Run `php artisan key:generate`
- Run `php artisan storage:link`
- Run `php artisan migrate --seed` (it has some seeded data for your testing)
- Run `npm ci` and `npm run build`
- That's it: launch the URL `/admin` and log in with credentials `admin@admin.com` and `password`. Visit the `/category` page.

---

## Screenshot

![](https://laraveldaily.com/uploads/2024/10/create-form-and-table-on-the-same-page-01.png)

![](https://laraveldaily.com/uploads/2024/10/create-form-and-table-on-the-same-page-02.png)

---

## How It Works

The inspiration for this page is from a WordPress page:

![](https://laraveldaily.com/uploads/2024/10/create-form-and-table-on-the-same-page-wp-example.png.png)

The whole logic is in a custom Filament page. First, the form is initialized in the `mount()` method.

**app/Filament/Pages/Category.php**:
```php
public function mount(): void
{
    $this->form->fill();
}
```

Then, the form has a name field that is live but only on blur. After the state is updated, a slug is generated from its state and set to a slug field.

The select field have options set manually. When options are in closure after the form is saved, the options are reloaded.

**app/Filament/Pages/Category.php**:
```php
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;

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
```

After creating a category the form is initialized to reset its state. Records in the table are shown automatically without page refresh.

**app/Filament/Pages/Category.php**:
```php
use Filament\Notifications\Notification;

public function create(): void
{
    \App\Models\Category::create($this->form->getState());

    $this->form->fill();

    Notification::make()
        ->title('Category created')
        ->success()
        ->send();

}
```

In the table, we show the name, slug, and description. We limit the description to 50 characters, and if there is no description, we show a minus sign.

**app/Filament/Pages/Category.php**:
```php
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
```

In the View file we make a grid of three columns. One column is for the form, and two columns are for the table.

**resources/views/filament/pages/category.blade.php**:
```blade
<x-filament-panels::page>
    <div class="grid gap-4 md:grid-cols-3">
        <form wire:submit="create">
            <div class="space-y-2">
                {{ $this->form }}

                <x-filament::button type="submit">
                    Submit
                </x-filament::button>
            </div>
        </form>

        <div class="md:col-span-2">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
```
