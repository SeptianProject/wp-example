<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HouseKriteriaScoreResource\Pages;
use App\Filament\Resources\HouseKriteriaScoreResource\RelationManagers;
use App\Models\HouseKriteriaScore;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HouseKriteriaScoreResource extends Resource
{
    protected static ?string $model = HouseKriteriaScore::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'House';
    protected static ?int $navigationSort = 2;
    protected static ?string $label = 'Nilai Kriteria Rumah';
    protected static ?string $pluralLabel = 'Daftar Nilai Kriteria Rumah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('house_id')
                    ->relationship('house', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $set('kriteria_id', null);
                    })
                    ->placeholder('Pilih Rumah')
                    ->label('Nama Rumah'),
                Forms\Components\Select::make('kriteria_id')
                    ->relationship('kriteria', 'nama')
                    ->searchable()
                    ->preload()
                    ->placeholder('Pilih Kriteria')
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        $set('nilai', null);
                    })
                    ->required()
                    ->label('Nama Kriteria'),
                Forms\Components\TextInput::make('nilai')
                    ->required()
                    ->numeric()
                    ->placeholder('Masukkan Nilai Kriteria')
                    ->label('Nilai Kriteria'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('house.nama')
                    ->label('Nama Rumah')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('kriteria.nama')
                    ->label('Nama Kriteria')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nilai')
                    ->label('Nilai')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([])
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHouseKriteriaScores::route('/'),
            'create' => Pages\CreateHouseKriteriaScore::route('/create'),
            'edit' => Pages\EditHouseKriteriaScore::route('/{record}/edit'),
        ];
    }
}
