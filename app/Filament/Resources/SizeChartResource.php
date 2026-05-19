<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SizeChartResource\Pages;
use App\Models\SizeChart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SizeChartResource extends Resource
{
    protected static ?string $model = SizeChart::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?string $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->helperText('e.g., "Abaya - Sri Lanka", "Hijab Standard", "Abaya - UK"'),

            Forms\Components\FileUpload::make('image_path')
                ->label('Size Chart Image (1080×1350, 4:5 ratio)')
                ->image()
                ->directory('size-charts')
                ->imageResizeMode('cover')
                ->imageCropAspectRatio('4:5'),

            Forms\Components\TextInput::make('sort_order')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')->label('Image'),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Linked Products'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSizeCharts::route('/'),
            'create' => Pages\CreateSizeChart::route('/create'),
            'edit' => Pages\EditSizeChart::route('/{record}/edit'),
        ];
    }
}
