<?php

namespace App\Filament\Pages;

use App\Models\HomepageSection;
use App\Services\ImageService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class HomepageManager extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Homepage';

    protected static ?int $navigationSort = 101;

    protected static string $view = 'filament.pages.homepage-manager';

    public ?array $data = [];

    public function mount(): void
    {
        $hero = HomepageSection::getSection('hero');
        $customization = HomepageSection::getSection('customization_steps');
        $mission = HomepageSection::getSection('mission');

        $customizationSteps = $customization?->extra_data ?? [
            ['number' => 1, 'title' => '', 'description' => ''],
            ['number' => 2, 'title' => '', 'description' => ''],
            ['number' => 3, 'title' => '', 'description' => ''],
        ];

        $this->form->fill([
            'hero_title' => $hero?->title ?? '',
            'hero_subtitle' => $hero?->subtitle ?? '',
            'hero_content' => $hero?->content ?? '',
            'hero_cta_text' => $hero?->cta_text ?? '',
            'hero_cta_url' => $hero?->cta_url ?? '',
            'hero_is_visible' => $hero?->is_visible ?? true,

            'customization_title' => $customization?->title ?? '',
            'customization_subtitle' => $customization?->subtitle ?? '',
            'customization_steps' => $customizationSteps,
            'customization_is_visible' => $customization?->is_visible ?? true,

            'mission_title' => $mission?->title ?? '',
            'mission_content' => $mission?->content ?? '',
            'mission_cta_text' => $mission?->cta_text ?? '',
            'mission_cta_url' => $mission?->cta_url ?? '',
            'mission_is_visible' => $mission?->is_visible ?? true,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Homepage Sections')->tabs([
                Forms\Components\Tabs\Tab::make('Hero Section')->schema([
                    Forms\Components\Toggle::make('hero_is_visible')
                        ->label('Visible'),
                    Forms\Components\TextInput::make('hero_title')
                        ->label('Title'),
                    Forms\Components\TextInput::make('hero_subtitle')
                        ->label('Subtitle'),
                    Forms\Components\Textarea::make('hero_content')
                        ->label('Content')
                        ->rows(3),
                    Forms\Components\FileUpload::make('hero_image')
                        ->label('Background Image')
                        ->image()
                        ->directory('homepage'),
                    Forms\Components\TextInput::make('hero_cta_text')
                        ->label('Button Text'),
                    Forms\Components\TextInput::make('hero_cta_url')
                        ->label('Button URL'),
                ]),

                Forms\Components\Tabs\Tab::make('3-Step Customization')->schema([
                    Forms\Components\Toggle::make('customization_is_visible')
                        ->label('Visible'),
                    Forms\Components\TextInput::make('customization_title')
                        ->label('Section Title'),
                    Forms\Components\TextInput::make('customization_subtitle')
                        ->label('Section Subtitle'),
                    Forms\Components\Repeater::make('customization_steps')
                        ->label('Steps')
                        ->schema([
                            Forms\Components\TextInput::make('number')
                                ->label('Step Number')
                                ->numeric(),
                            Forms\Components\TextInput::make('title')
                                ->label('Title'),
                            Forms\Components\Textarea::make('description')
                                ->label('Description')
                                ->rows(2),
                        ])
                        ->defaultItems(3),
                ]),

                Forms\Components\Tabs\Tab::make('Mission Section')->schema([
                    Forms\Components\Toggle::make('mission_is_visible')
                        ->label('Visible'),
                    Forms\Components\TextInput::make('mission_title')
                        ->label('Title'),
                    Forms\Components\RichEditor::make('mission_content')
                        ->label('Content'),
                    Forms\Components\FileUpload::make('mission_image')
                        ->label('Image')
                        ->image()
                        ->directory('homepage'),
                    Forms\Components\TextInput::make('mission_cta_text')
                        ->label('Button Text'),
                    Forms\Components\TextInput::make('mission_cta_url')
                        ->label('Button URL'),
                ]),
            ]),
        ])->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $imageService = app(ImageService::class);

        HomepageSection::updateSection('hero', [
            'title' => $data['hero_title'] ?? '',
            'subtitle' => $data['hero_subtitle'] ?? '',
            'content' => $data['hero_content'] ?? '',
            'cta_text' => $data['hero_cta_text'] ?? '',
            'cta_url' => $data['hero_cta_url'] ?? '',
            'is_visible' => $data['hero_is_visible'] ?? true,
        ]);

        HomepageSection::updateSection('customization_steps', [
            'title' => $data['customization_title'] ?? '',
            'subtitle' => $data['customization_subtitle'] ?? '',
            'extra_data' => $data['customization_steps'] ?? [],
            'is_visible' => $data['customization_is_visible'] ?? true,
        ]);

        HomepageSection::updateSection('mission', [
            'title' => $data['mission_title'] ?? '',
            'content' => $data['mission_content'] ?? '',
            'cta_text' => $data['mission_cta_text'] ?? '',
            'cta_url' => $data['mission_cta_url'] ?? '',
            'is_visible' => $data['mission_is_visible'] ?? true,
        ]);

        Notification::make()->title('Homepage saved!')->success()->send();
    }
}
