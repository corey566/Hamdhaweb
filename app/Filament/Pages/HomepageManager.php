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

        $heroSlides = $hero?->extra_data['slides'] ?? [];
        if (empty($heroSlides) && $hero?->image_path) {
            $heroSlides = [$hero->image_path];
        }

        $this->form->fill([
            'hero_title' => $hero?->title ?? '',
            'hero_subtitle' => $hero?->subtitle ?? '',
            'hero_content' => $hero?->content ?? '',
            'hero_image' => $hero?->image_path ?? null,
            'hero_slides' => $heroSlides,
            'hero_cta_text' => $hero?->cta_text ?? '',
            'hero_cta_url' => $hero?->cta_url ?? '',
            'hero_is_visible' => $hero?->is_visible ?? true,

            'customization_title' => $customization?->title ?? '',
            'customization_subtitle' => $customization?->subtitle ?? '',
            'customization_steps' => $customizationSteps,
            'customization_is_visible' => $customization?->is_visible ?? true,

            'mission_title' => $mission?->title ?? '',
            'mission_content' => $mission?->content ?? '',
            'mission_image' => $mission?->image_path ?? null,
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
                        ->label('Primary Image (fallback)')
                        ->image()
                        ->disk('public')
                        ->directory('homepage')
                        ->nullable()
                        ->saveUploadedFileUsing(fn ($file) => app(ImageService::class)->processHomepageImage($file)),
                    Forms\Components\FileUpload::make('hero_slides')
                        ->label('Hero Slider Images')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->disk('public')
                        ->directory('homepage')
                        ->nullable()
                        ->helperText('Upload multiple images for the homepage hero slider (desktop right + mobile top).'),
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
                        ->disk('public')
                        ->directory('homepage')
                        ->nullable()
                        ->saveUploadedFileUsing(fn ($file) => app(ImageService::class)->processHomepageImage($file)),
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

        $hero = HomepageSection::getSection('hero');
        $this->handleImageDeletion($imageService, $data['hero_image'] ?? null, $hero?->image_path);

        $slides = array_values(array_filter($data['hero_slides'] ?? []));
        $primaryImage = $slides[0] ?? ($data['hero_image'] ?? null);

        HomepageSection::updateSection('hero', [
            'title' => $data['hero_title'] ?? '',
            'subtitle' => $data['hero_subtitle'] ?? '',
            'content' => $data['hero_content'] ?? '',
            'image_path' => $primaryImage,
            'extra_data' => ['slides' => $slides],
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

        $mission = HomepageSection::getSection('mission');
        $this->handleImageDeletion($imageService, $data['mission_image'] ?? null, $mission?->image_path);

        HomepageSection::updateSection('mission', [
            'title' => $data['mission_title'] ?? '',
            'content' => $data['mission_content'] ?? '',
            'image_path' => $data['mission_image'] ?? null,
            'cta_text' => $data['mission_cta_text'] ?? '',
            'cta_url' => $data['mission_cta_url'] ?? '',
            'is_visible' => $data['mission_is_visible'] ?? true,
        ]);

        cache()->forget('homepage_section.hero');
        cache()->forget('homepage_section.customization_steps');
        cache()->forget('homepage_section.mission');

        Notification::make()->title('Homepage saved!')->success()->send();
    }

    private function handleImageDeletion(
        ImageService $imageService,
        ?string $newImagePath,
        ?string $existingPath,
    ): void {
        if ($existingPath && $newImagePath !== $existingPath) {
            $imageService->deleteImage($existingPath);
        }
    }
}
