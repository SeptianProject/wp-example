<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Meeting;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MeetingResource\Pages;

class MeetingResource extends Resource
{
    protected static ?string $model = Meeting::class;
    protected static ?string $modelLabel = 'Pertemuan';
    protected static ?string $navigationGroup = 'Customer Management';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name', function (Builder $query) {
                                $query->where('role', 'customer');
                            })
                            ->disabled(fn($record) => $record !== null)
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'requested' => 'Requested',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled'
                            ])
                            ->default('requested')
                            ->required()
                            ->reactive(),
                    ])->columns(2),
                Forms\Components\DateTimePicker::make('date')
                    ->label('Tanggal Pertemuan')
                    ->required(fn(callable $get) => $get('status') === 'confirmed')
                    ->helperText('Atur tanggal pertemuan. Wajib diisi jika status "Confirmed"'),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->nullable()->maxLength(1000)
                    ->rows(3),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal Pertemuan')
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->status === 'cancelled') {
                            return 'Dibatalkan';
                        } elseif ($record->status === 'requested') {
                            return 'Menunggu konfirmasi';
                        } elseif ($state) {
                            return \Carbon\Carbon::parse($state)->format('d M Y H:i');
                        }
                        return '-';
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'requested',
                        'success' => 'confirmed',
                        'info' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(function ($state) {
                        $labels = [
                            'requested' => 'Menunggu Konfirmasi',
                            'confirmed' => 'Terjadwal',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan'
                        ];
                        return $labels[$state] ?? $state;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->status === 'cancelled') {
                            return 'Pertemuan dibatalkan';
                        }
                        return $state ?? '-';
                    })
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->date('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'requested' => 'Menunggu Konfirmasi',
                        'confirmed' => 'Terjadwal',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan'
                    ])
                    ->label('Status')
                    ->indicator('Status')
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->modalHeading('Edit Pertemuan')
                    ->modalSubmitActionLabel('Perbarui Pertemuan')
                    ->successNotificationTitle('Pertemuan Berhasil Diperbarui'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading('Belum ada pertemuan')
            ->emptyStateDescription('Belum ada pertemuan yang dijadwalkan.')
            ->emptyStateIcon('heroicon-o-calendar');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMeetings::route('/'),
        ];
    }
}
