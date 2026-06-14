<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CmsPageResource\Pages;
use App\Models\CmsPage;
use App\Services\ImageService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CmsPageResource extends Resource
{
    protected static ?string $model = CmsPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Content Pages';

    protected static ?string $navigationGroup = 'Website';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Page')->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('URL: /page/your-slug'),
                Forms\Components\FileUpload::make('banner_image')
                    ->label('Banner image')
                    ->image()
                    ->directory('cms')
                    ->nullable()
                    ->saveUploadedFileUsing(fn ($file) => app(ImageService::class)->processHomepageImage($file)),
                Forms\Components\RichEditor::make('content')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('sections')
                    ->label('Extra sections')
                    ->schema([
                        Forms\Components\TextInput::make('heading'),
                        Forms\Components\RichEditor::make('body'),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('cms')
                            ->saveUploadedFileUsing(fn ($file) => app(ImageService::class)->processHomepageImage($file)),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_visible')->default(true),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->copyable(),
                Tables\Columns\IconColumn::make('is_visible')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCmsPages::route('/'),
            'create' => Pages\CreateCmsPage::route('/create'),
            'edit' => Pages\EditCmsPage::route('/{record}/edit'),
        ];
    }
}
