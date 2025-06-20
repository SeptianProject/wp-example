<?php

namespace App\Filament\Resources\KriteriaResource\Pages;

use App\Filament\Resources\KriteriaResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListKriterias extends ListRecords
{
    protected static string $resource = KriteriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Kriteria')
                ->icon('heroicon-o-plus')
                ->action(function () {
                    Notification::make()
                        ->title('Kriteria berhasil ditambahkan.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
