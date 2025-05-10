<?php

namespace App\Filament\Resources\HouseKriteriaScoreResource\Pages;

use App\Filament\Resources\HouseKriteriaScoreResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListHouseKriteriaScores extends ListRecords
{
    protected static string $resource = HouseKriteriaScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
        ];
    }
}
