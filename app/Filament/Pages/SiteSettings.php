<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\StorefrontConfig;
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
            'currency_gbp_rate' => Setting::get('currency_gbp_rate', '0.0021'),
            'plp_tagline' => Setting::get('plp_tagline'),
            'storefront_currency_enabled' => StorefrontConfig::bool('storefront_currency_enabled', true),
            'storefront_currency_default' => Setting::get('storefront_currency_default', 'LKR'),
            'storefront_wishlist_enabled' => StorefrontConfig::bool('storefront_wishlist_enabled', true),
            'storefront_marquee_links_enabled' => StorefrontConfig::bool('storefront_marquee_links_enabled', true),
            'storefront_featured_filter_enabled' => StorefrontConfig::bool('storefront_featured_filter_enabled', true),
            'storefront_collection_layout' => Setting::get('storefront_collection_layout', 'tabs'),
            'storefront_nav_mode' => Setting::get('storefront_nav_mode', 'hierarchical'),
            'storefront_currency_auto_detect' => StorefrontConfig::bool('storefront_currency_auto_detect', true),
            'site_title' => Setting::get('site_title', 'Hamdha Clothing'),
            'shipping_text' => Setting::get('shipping_text', 'Island-wide shipping'),
            'orianwave_url' => Setting::get('orianwave_url', 'https://orianwave.com'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Settings')->tabs([

                Forms\Components\Tabs\Tab::make('Storefront')->schema([
                    Forms\Components\Section::make('Header & branding')
                        ->schema([
                            Forms\Components\TextInput::make('site_title')
                                ->label('Website title (navbar)')
                                ->maxLength(80),
                            Forms\Components\TextInput::make('shipping_text')
                                ->label('Shipping announcement')
                                ->helperText('Shown in the top bar and mobile ticker.'),
                            Forms\Components\TextInput::make('orianwave_url')
                                ->label('Orianwave website URL')
                                ->url()
                                ->helperText('Footer “Design by Orianwave” link.'),
                            Forms\Components\Select::make('storefront_nav_mode')
                                ->label('Navigation layout')
                                ->options([
                                    'flat' => 'Option A — flat categories (New Arrivals, Plain Abaya, …)',
                                    'hierarchical' => 'Option B — main + dropdown (Abayas, Hijab, …)',
                                ])
                                ->required(),
                        ])->columns(2),

                    Forms\Components\Section::make('Currency (LKR ↔ GBP)')
                        ->description('Controls the currency toggle in the header and how prices are shown on the shop.')
                        ->schema([
                            Forms\Components\Toggle::make('storefront_currency_enabled')
                                ->label('Enable currency switcher')
                                ->helperText('When off, all prices stay in LKR and the LK/GBP button is hidden.')
                                ->live(),
                            Forms\Components\Toggle::make('storefront_currency_auto_detect')
                                ->label('Auto-detect currency by visitor location')
                                ->helperText('Uses browser locale and IP (UK → GBP, Sri Lanka → LKR). Visitors can still switch manually.')
                                ->visible(fn (Forms\Get $get) => $get('storefront_currency_enabled')),
                            Forms\Components\Select::make('storefront_currency_default')
                                ->label('Default currency for new visitors')
                                ->options([
                                    'LKR' => 'LKR — Sri Lanka (Rs.)',
                                    'GBP' => 'GBP — United Kingdom (£)',
                                ])
                                ->required()
                                ->visible(fn (Forms\Get $get) => $get('storefront_currency_enabled')),
                            Forms\Components\TextInput::make('currency_gbp_rate')
                                ->label('LKR → GBP conversion rate')
                                ->numeric()
                                ->step(0.0000001)
                                ->helperText('Display only: UK price = LKR price × this rate (e.g. 0.0021)')
                                ->visible(fn (Forms\Get $get) => $get('storefront_currency_enabled')),
                        ])->columns(1),

                    Forms\Components\Section::make('Wish list')
                        ->schema([
                            Forms\Components\Toggle::make('storefront_wishlist_enabled')
                                ->label('Enable wish list')
                                ->helperText('Saves items in the visitor\'s browser (localStorage). Heart icon and drawer appear when on.'),
                        ]),

                    Forms\Components\Section::make('Homepage marquee')
                        ->schema([
                            Forms\Components\Toggle::make('storefront_marquee_links_enabled')
                                ->label('Category pills link to collection pages')
                                ->helperText('When off, pills scroll as decorative text only (no links).'),
                        ]),

                    Forms\Components\Section::make('Shop / product listing')
                        ->schema([
                            Forms\Components\Toggle::make('storefront_featured_filter_enabled')
                                ->label('Show “Featured designs” filter')
                                ->helperText('When on, shoppers can filter to products marked Featured in admin.'),
                            Forms\Components\Select::make('storefront_collection_layout')
                                ->label('Collection navigation layout')
                                ->options([
                                    'tabs' => 'Horizontal tabs (single row)',
                                    'columns' => 'Two columns (e.g. Abayas | Hijabs side by side)',
                                ])
                                ->required()
                                ->helperText('How top-level collections appear on All Products and New Arrivals.'),
                            Forms\Components\Textarea::make('plp_tagline')
                                ->label('Product listing tagline')
                                ->rows(2)
                                ->helperText('Shown under the page title on category and shop pages.'),
                        ]),
                ]),

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
                    Forms\Components\Placeholder::make('footer_links_help')
                        ->label('')
                        ->content('Edit links below — they update on the live site automatically after you save.'),
                    Forms\Components\Textarea::make('footer_tagline')->rows(3),
                    Forms\Components\Repeater::make('footer_info_links')
                        ->label('Information Links')
                        ->schema([
                            Forms\Components\TextInput::make('label')->required(),
                            Forms\Components\TextInput::make('url')->required()->helperText('Use full URL or path, e.g. /about or https://...'),
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
            'footer_tagline', 'currency_gbp_rate', 'plp_tagline',
            'storefront_currency_default', 'storefront_collection_layout',
            'storefront_nav_mode', 'site_title', 'shipping_text', 'orianwave_url'] as $key) {
            Setting::set($key, $data[$key] ?? null);
        }

        foreach ([
            'storefront_currency_enabled',
            'storefront_currency_auto_detect',
            'storefront_wishlist_enabled',
            'storefront_marquee_links_enabled',
            'storefront_featured_filter_enabled',
        ] as $toggle) {
            Setting::set($toggle, ! empty($data[$toggle]) ? '1' : '0');
        }

        Setting::set('announcement_bar_items', json_encode($data['announcement_bar_items'] ?? []));
        Setting::set('footer_info_links', json_encode($data['footer_info_links'] ?? []));
        Setting::set('footer_customer_care_links', json_encode($data['footer_customer_care_links'] ?? []));

        StorefrontConfig::clearCache();

        Notification::make()
            ->title('Settings saved!')
            ->body('Storefront changes are live for all visitors.')
            ->success()
            ->send();
    }
}
