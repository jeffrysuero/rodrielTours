<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Infolist;
// use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components;
use Illuminate\Support\Facades\DB;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Vehiculos';

    public static function form(Form $form): Form
    {

        $chofer = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('roles.name', 'Conductores')
            ->pluck('users.name','users.id');
        //  dd($chofer);
        return $form

            ->schema([
                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Forms\Components\TextInput::make('marca')->label('Marca ')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('modelo')->label('Modelo')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('color')->label('Color')
                                    ->required(),

                                // Forms\Components\TextInput::make('type')->label('Tipo')
                                //     ->required()
                                //     ->maxLength(255),

                                Forms\Components\TextInput::make('percentage')->label('Porcentage a pagar por viaje')
                                    ->required()
                                    ->numeric(),
                            ])
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Forms\Components\TextInput::make('passenger_capacity')->label('Capasidad de Pasagero')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('luggage_capacity')->label('Capasidad de Equipaje')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('placa')->label('Placa')
                                    ->required(),

                                // Select::make('userId')
                                //     ->label('Chofer')
                                //     ->searchable()
                                //     ->noSearchResultsMessage('Chofer no encontrado')
                                //     ->options($chofer),

                                // ->label('Cliente')
                                // ->searchable()
                                // ->noSearchResultsMessage('Cliente no encontrado')
                                // ->options($client),
                            ]),

                    ]),

                Group::make()
                    ->schema([
                        Section::make('')
                            ->schema([
                                Forms\Components\FileUpload::make('image')->label('Imagen del Vehiculo')
                                    ->image()
                                    ->required()
                                    ->directory('vehicles'),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                // Tables\Columns\TextColumn::make('users.name')->label('Chofer')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('marca')
                    ->searchable(),
                Tables\Columns\TextColumn::make('modelo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('color')->label('Color')
                    ->numeric()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('type')->label('Tipo')
                //     ->searchable(),
                Tables\Columns\TextColumn::make('passenger_capacity')->label('Cap.Pasagero')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('luggage_capacity')->label('Cap.Equipaje')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('image')
                    ->height(90)
                    ->circular(),
                Tables\Columns\TextColumn::make('placa')->label('Placa')
                    ->numeric()
                    ->sortable(),
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
                // Tables\Actions\ActionGroup::make([
                // Tables\Actions\ViewAction::make()->color('success')->label('pagar')->icon('heroicon-o-banknotes'),
                Tables\Actions\EditAction::make()->color('warning')->label('Editar'),
                Tables\Actions\DeleteAction::make(),
                // ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ]);
    }


    // public static function infolist(Infolist $infolist): Infolist
    // {
    //     return $infolist
    //         ->schema([
    //             Components\Section::make()
    //                 ->schema([
    //                     Components\Split::make([
    //                         Components\Grid::make(2)
    //                             ->schema([
    //                                 Components\Group::make([
    //                                     Components\TextEntry::make('users.name')->label('Nombre'),
    //                                     Components\TextEntry::make('users.email')->label('Email'),
    //                                     // Components\TextEntry::make('published_at')
    //                                     //     ->badge()
    //                                     //     ->date()
    //                                     //     ->color('success'),
    //                                 ]),
    //                                 Components\Group::make([
    //                                     Components\TextEntry::make(''),
    //                                     Components\TextEntry::make(''),
    //                                     // Components\SpatieTagsEntry::make('tags'),
    //                                 ]),

    //                                 Components\Group::make([
    //                                     // Components\TextEntry::make('marca'),
    //                                     // Components\TextEntry::make('modelo'),
    //                                     // Components\SpatieTagsEntry::make('tags'),

    //                                 ]),
    //                             ]),
    //                         Components\ImageEntry::make('users.image')
    //                             ->hiddenLabel()
    //                             ->grow(false),
    //                     ])->from('lg'),

    //                 ]),
    //             Components\Section::make('Content')
    //                 ->schema([
    //                     Components\TextEntry::make('content')
    //                         ->prose()
    //                         ->markdown()
    //                         ->hiddenLabel(),

    //                 ])
    //                 ->collapsible(),
    //         ]);
    // }

    // public static function getRecordSubNavigation(Page $page): array
    // {
    //     return $page->generateNavigationItems([
    //         Pages\ViewUser::class,
    //         Pages\CompletedPay::class,

    //     ]);
    // }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
            // 'view' => Pages\ViewUser::route('/{record}'),
            // 'pay' => Pages\CompletedPay::route('/{record}/pay'),
        ];
    }
}
