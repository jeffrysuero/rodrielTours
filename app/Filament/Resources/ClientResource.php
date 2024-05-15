<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Clientes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Forms\Components\TextInput::make('name')->label('Nombre')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('lastName')->label('Apellidos')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\DateTimePicker::make('arrivalDate')->label('Fecha de llegada')
                                    ->required(),
                            ]),
                    ]),
                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')->label('Telefono')
                                    ->tel()
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxValue(15)
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('num_air')->label('Numero del Vuelo')
                                   
                                    ->maxLength(255),
                            ])
                    ]),

                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Forms\Components\MarkdownEditor::make('airport')->label('Aeropuerto')
                                    ->required()
                                    ->maxLength(255),
                            ])
                    ]),
                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([

                                Forms\Components\MarkdownEditor::make('hotel')->label('Hotel')
                                    ->required()
                                    ->maxLength(255)
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lastName')->label('Apellido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('Telefono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('airport')->label('Aero Puerto')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('num_air')->label('Numero de Vuelo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ExportBulkAction::make()
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
