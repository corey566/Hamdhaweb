<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Product Information')->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('model_number')
                    ->label('Model Number')
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Auto-generated. Will be assigned on save.'),

                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rs.'),

                Forms\Components\TextInput::make('discount_price')
                    ->numeric()
                    ->prefix('Rs.')
                    ->helperText('Leave empty for no discount. Must be less than regular price.'),

                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),

                Forms\Components\Select::make('fabric_id')
                    ->label('Fabric')
                    ->relationship('fabric', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')->required(),
                    ]),

                Forms\Components\TextInput::make('colors')
                    ->label('Colors / Note')
                    ->helperText('e.g., "Multiple Options — Confirm Via WhatsApp"'),
            ])->columns(2),

            Forms\Components\Section::make('Categories & Tags')->schema([
                Forms\Components\CheckboxList::make('categories')
                    ->relationship('categories', 'name')
                    ->columns(3)
                    ->helperText(
                        'Select all categories this product belongs to. '.
                        'The FIRST selected category will be the primary category. '.
                        'Others will appear as tag pills on the product card.'
                    )
                    ->required(),
            ]),

            Forms\Components\Section::make('Size Charts')->schema([
                Forms\Components\Select::make('sizeCharts')
                    ->label('Attach Size Guides')
                    ->relationship('sizeCharts', 'name')
                    ->multiple()
                    ->preload()
                    ->helperText('Select size chart(s) to show on this product. Created in Size Charts menu.'),
            ]),

            Forms\Components\Section::make('Product Images')->schema([
                Forms\Components\FileUpload::make('product_images')
                    ->label('Images (max 5, 4:5 ratio, auto-converted to WebP)')
                    ->image()
                    ->multiple()
                    ->maxFiles(5)
                    ->reorderable()
                    ->directory('products')
                    ->helperText('First image becomes the thumbnail. Drag to reorder.'),
            ]),

            Forms\Components\Section::make('Visibility')->schema([
                Forms\Components\Toggle::make('is_visible')
                    ->label('Visible on website')
                    ->default(true),
                Forms\Components\Toggle::make('is_featured')
                    ->label('Show in Featured/Homepage section')
                    ->default(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images.0.thumbnail_path')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.webp')),
                Tables\Columns\TextColumn::make('model_number')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('price')
                    ->money('LKR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_price')
                    ->money('LKR')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('fabric.name')
                    ->label('Fabric'),
                Tables\Columns\ToggleColumn::make('is_visible')
                    ->label('Visible'),
                Tables\Columns\ToggleColumn::make('is_featured')
                    ->label('Featured'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('fabric_id')
                    ->relationship('fabric', 'name'),
                Tables\Filters\TernaryFilter::make('is_visible'),
                Tables\Filters\TernaryFilter::make('is_featured'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
