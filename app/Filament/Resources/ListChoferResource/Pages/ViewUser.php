<?php

namespace App\Filament\Resources\ListChoferResource\Pages;

use App\Filament\Resources\ListChoferResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\VehicleResource;
use App\Models\Client;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
class ViewUser extends ViewRecord
{
    protected static string $resource = ListChoferResource::class;

    // public function getTitle(): string | Htmlable
    // {
    //     /** @var Client */
    //     $record = $this->getRecord();

    //     return optional($record->title)->toString() ?? 'Título no disponible';
    // }

    public static function table(Table $table): Table
    {
        return $table
            
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Chofer')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('marca')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('modelo')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('color')->label('Color')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('type')->label('Tipo')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('passenger_capacity')->label('Cap.Pasagero')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('luggage_capacity')->label('Cap.Equipaje')
                //     ->numeric()
                //     ->sortable(),
                // ImageColumn::make('image')
                //     ->height(90)
                //     ->circular(),
                // Tables\Columns\TextColumn::make('placa')->label('Placa')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->color('info'),
                    Tables\Actions\EditAction::make()->color('warning'),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // ExportBulkAction::make()
                ]),
            ]);
    }

    protected function getActions(): array
    {
        return [];
    }
}