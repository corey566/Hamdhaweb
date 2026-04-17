<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?int $navigationSort = 100;

    protected static string $view = 'filament.pages.site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'whatsapp_number' => Setting::get('whatsapp_number'),
            'whatsapp_message_template' => Setting::get('whatsapp_message_template'),
            'contact_phone' => Setting::get('contact_phone'),
            'model_number_prefix' => Setting::get('model_number_prefix', 'HM'),
            'new_arrivals_count' => Setting::get('new_arrivals_count', '8'),
            'social_instagram' => Setting::get('social_instagram'),
            'social_facebook' => Setting::get('social_facebook'),
            'social_tiktok' => Setting::get('social_tiktok'),
            'announcement_bar_items' => Setting::getJson('announcement_bar_items'),
            'footer_tagline' => Setting::get('footer_tagline'),
            'footer_info_links' => Setting::getJson('footer_info_links'),
            'footer_customer_care_links' => Setting::getJson('footer_customer_care_links'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Settings')->tabs([

                Forms\Components\Tabs\Tab::make('WhatsApp')->schema([
                    Forms\Components\TextInput::make('whatsapp_number')
                        ->label('WhatsApp Number (with country code, no +)')
                        ->required()
                        ->helperText('e.g., 94777626013'),
                    Forms\Components\Textarea::make('whatsapp_message_template')
                        ->label('Message Template')
                        ->rows(8)
                        ->helperText('Placeholders: {model}, {name}, {price}, {fabric}, {url}'),
                ]),

                Forms\Components\Tabs\Tab::make('Announcement Bar')->schema([
                    Forms\Components\Repeater::make('announcement_bar_items')
                        ->label('Top Bar Items')
                        ->simple(
                            Forms\Components\TextInput::make('text')->required(),
                        )
                        ->defaultItems(2),
                ]),

                Forms\Components\Tabs\Tab::make('Social & Contact')->schema([
                    Forms\Components\TextInput::make('contact_phone'),
                    Forms\Components\TextInput::make('social_instagram')->url(),
                    Forms\Components\TextInput::make('social_facebook')->url(),
                    Forms\Components\TextInput::make('social_tiktok')->url(),
                ]),

                Forms\Components\Tabs\Tab::make('Footer')->schema([
                    Forms\Components\Textarea::make('footer_tagline')->rows(3),
                    Forms\Components\Repeater::make('footer_info_links')
                        ->label('Information Links')
                        ->schema([
                            Forms\Components\TextInput::make('label')->required(),
                            Forms\Components\TextInput::make('url')->required(),
                        ])->columns(2),
                    Forms\Components\Repeater::make('footer_customer_care_links')
                        ->label('Customer Care Links')
                        ->schema([
                            Forms\Components\TextInput::make('label')->required(),
                            Forms\Components\TextInput::make('url')->required(),
                        ])->columns(2),
                ]),

                Forms\Components\Tabs\Tab::make('Product Settings')->schema([
                    Forms\Components\TextInput::make('model_number_prefix')
                        ->label('Model Number Prefix')
                        ->helperText('e.g., HM → generates HM-0001'),
                    Forms\Components\TextInput::make('new_arrivals_count')
                        ->numeric()
                        ->helperText('How many products to show in "New Arrivals"'),
                ]),
            ]),
        ])->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach (['whatsapp_number', 'whatsapp_message_template', 'contact_phone',
            'model_number_prefix', 'new_arrivals_count',
            'social_instagram', 'social_facebook', 'social_tiktok',
            'footer_tagline'] as $key) {
            Setting::set($key, $data[$key] ?? null);
        }

        Setting::set('announcement_bar_items', json_encode($data['announcement_bar_items'] ?? []));
        Setting::set('footer_info_links', json_encode($data['footer_info_links'] ?? []));
        Setting::set('footer_customer_care_links', json_encode($data['footer_customer_care_links'] ?? []));

        Notification::make()->title('Settings saved!')->success()->send();
    }
}
