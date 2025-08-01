<?php

namespace App\Filament\Resources\HouseResource\Pages;

use App\Filament\Resources\HouseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateHouse extends CreateRecord
{
    protected static string $resource = HouseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreateAnotherFormAction(): Actions\Action
    {
        return parent::getCreateAnotherFormAction()->hidden();
    }
}
