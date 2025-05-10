<?php

namespace App\Filament\Resources\HouseKriteriaScoreResource\Pages;

use App\Filament\Resources\HouseKriteriaScoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHouseKriteriaScore extends EditRecord
{
    protected static string $resource = HouseKriteriaScoreResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
