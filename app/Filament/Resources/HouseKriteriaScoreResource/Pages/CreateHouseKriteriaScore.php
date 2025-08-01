<?php

namespace App\Filament\Resources\HouseKriteriaScoreResource\Pages;

use App\Filament\Resources\HouseKriteriaScoreResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHouseKriteriaScore extends CreateRecord
{
    protected static string $resource = HouseKriteriaScoreResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreateAnotherFormAction(): Actions\Action
    {
        return parent::getCreateAnotherFormAction()->hidden();
    }
}
