<?php

namespace App\Filament\Resources\PriceBucketResource\Pages;

use App\Filament\Resources\PriceBucketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPriceBuckets extends ListRecords
{
    protected static string $resource = PriceBucketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
