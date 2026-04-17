<?php

namespace App\Filament\Resources\PriceBucketResource\Pages;

use App\Filament\Resources\PriceBucketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPriceBucket extends EditRecord
{
    protected static string $resource = PriceBucketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
