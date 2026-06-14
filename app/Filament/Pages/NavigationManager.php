<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Setting;
use App\Support\StorefrontConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class NavigationManager extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationLabel = 'Navigation';

    protected static ?string $navigationGroup = 'Website';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.navigation-manager';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'storefront_nav_mode' => Setting::get('storefront_nav_mode', 'hierarchical'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Navbar structure')
                ->description('Choose how categories appear in the main menu. Reorder categories below — drag rows in the Categories list (Catalog → Categories).')
                ->schema([
                    Forms\Components\Radio::make('storefront_nav_mode')
                        ->label('Navigation mode')
                        ->options([
                            'flat' => 'Option A — Simple (New Arrivals, Plain Abaya, Embroidery Abaya, … each as direct links)',
                            'hierarchical' => 'Option B — Main + sub nav (Abayas ▾ with Plain, Embroidery… / Hijab ▾ with Cotton, Georgette…)',
                        ])
                        ->required(),
                ]),
        ])->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        Setting::set('storefront_nav_mode', $data['storefront_nav_mode'] ?? 'hierarchical');
        StorefrontConfig::clearCache();

        Notification::make()->title('Navigation settings saved')->success()->send();
    }

    public function getCategoriesProperty()
    {
        return Category::topLevel()
            ->with(['children' => fn ($q) => $q->ordered()])
            ->ordered()
            ->get();
    }
}
