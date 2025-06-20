<?php

namespace App\Filament\Resources\MeetingResource\Pages;

use App\Filament\Resources\MeetingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMeetings extends ListRecords
{
    protected static string $resource = MeetingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Pertemuan')
                ->icon('heroicon-o-calendar')
                ->color('primary')
                ->modalHeading('Tambah Pertemuan')
                ->modalSubmitActionLabel('Buat Pertemuan')
                ->successNotificationTitle('Pertemuan Berhasil Dibuat'),
        ];
    }
}
