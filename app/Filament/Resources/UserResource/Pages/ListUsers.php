<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->label('Tambah Customer')
                ->icon('heroicon-o-user-plus')
                ->color('primary')
                ->modalHeading('Tambah Customer')
                ->modalSubmitActionLabel('Buat Customer')
                ->successNotificationTitle('Customer Berhasil Dibuat')
        ];
    }
}
