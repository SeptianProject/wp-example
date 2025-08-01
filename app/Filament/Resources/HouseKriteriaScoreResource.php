<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\HouseKriteriaScore;
use App\Filament\Resources\HouseKriteriaScoreResource\Pages;

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
                    ->afterStateUpdated(function (callable $set) {
                        $set('kriteria_id', null);
                        $set('nilai', null);
                    })
                    ->placeholder('Pilih Rumah')
                    ->label('Nama Rumah'),
                Forms\Components\Select::make('kriteria_id')
                    ->relationship('kriteria', 'nama', function ($query, $get, $set, $livewire) {
                        if ($houseId = $get('house_id')) {
                            $existingKriterias = HouseKriteriaScore::where('house_id', $houseId)
                                ->pluck('kriteria_id')
                                ->toArray();

                            if ($livewire->record && $livewire->record->kriteria_id) {
                                $existingKriterias = array_filter($existingKriterias, function ($id) use ($livewire) {
                                    return $id != $livewire->record->kriteria_id;
                                });
                            }

                            $query->whereNotIn('id', $existingKriterias);
                        }
                    })
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
                Forms\Components\Textarea::make('keterangan')
                    ->placeholder('Masukkan Keterangan')
                    ->label('Keterangan')
                    ->rows(3)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('house.nama')
                    ->label('Nama Rumah')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kriteria.kode')
                    ->label('Kode Kriteria')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kriteria.nama')
                    ->label('Nama Kriteria')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nilai')
                    ->label('Nilai')
                    ->formatStateUsing(function ($record) {
                        if (!$record->kriteria) return $record->nilai;

                        if ($record->kriteria->field_type === 'tags' && is_array($record->nilai)) {
                            return implode(', ', $record->nilai);
                        }

                        return $record->nilai;
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->sortable()
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn($record) => $record->keterangan)
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
