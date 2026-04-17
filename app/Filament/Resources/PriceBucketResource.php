<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceBucketResource\Pages;
use App\Models\PriceBucket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PriceBucketResource extends Resource
{
    protected static ?string $model = PriceBucket::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('label')
                ->required()
                ->maxLength(100)
                ->helperText('e.g., "Under 5,000" or "10,000 - 15,000" or "Above 20,000"'),
            Forms\Components\TextInput::make('min_price')
                ->required()
                ->numeric()
                ->prefix('Rs.'),
            Forms\Components\TextInput::make('max_price')
                ->numeric()
                ->prefix('Rs.')
                ->helperText('Leave empty for "no upper limit" (e.g., "Above 20,000")'),
            Forms\Components\TextInput::make('sort_order')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')->sortable(),
                Tables\Columns\TextColumn::make('min_price')->money('LKR'),
                Tables\Columns\TextColumn::make('max_price')->money('LKR')->placeholder('No limit'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
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
            'index' => Pages\ListPriceBuckets::route('/'),
            'create' => Pages\CreatePriceBucket::route('/create'),
            'edit' => Pages\EditPriceBucket::route('/{record}/edit'),
        ];
    }
}
