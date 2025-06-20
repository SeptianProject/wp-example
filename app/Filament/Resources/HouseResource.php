<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HouseResource\Pages;
use App\Models\House;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HouseResource extends Resource
{
    protected static ?string $model = House::class;
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'House';
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'Rumah';
    protected static ?string $pluralLabel = 'Daftar Data Rumah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Rumah')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3),
                FileUpload::make('image')
                    ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->visibility('public')
                    ->square()
                    ->size(100)
                    ->label('Foto Rumah'),
                TextColumn::make('nama')
                    ->label('Nama Rumah')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Deskripsi Rumah')
                    ->tooltip(fn($record) => $record->description)
                    ->limit(50),
                TextColumn::make('kriteria.kode')
                    ->label('Kode Kriteria')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return is_array($state) ? implode(', ', $state) : $state;
                    }),
                TextColumn::make('kriteria.nama')
                    ->label('Nama Kriteria')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return is_array($state) ? implode(', ', $state) : $state;
                    })
                    ->limit(40)
                    ->tooltip(fn($record) => $record->kriteria->pluck('nama')->implode(', ')),
                TextColumn::make('kriteriaScores.nilai')
                    ->label('Nilai Kriteria')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return is_array($state) ? implode(', ', $state) : $state;
                    })
                    ->limit(100)
                    ->tooltip(fn($record) => $record->kriteriaScores->pluck('nilai')->implode(', ')),
                TextColumn::make('kriteriaScores.keterangan')
                    ->label('Keterangan')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        return is_array($state) ? implode(', ', $state) : $state;
                    })
                    ->limit(100)
                    ->tooltip(fn($record) => $record->kriteriaScores->pluck('keterangan')->implode(', ')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListHouses::route('/'),
            'create' => Pages\CreateHouse::route('/create'),
            'edit' => Pages\EditHouse::route('/{record}/edit'),
        ];
    }
}
