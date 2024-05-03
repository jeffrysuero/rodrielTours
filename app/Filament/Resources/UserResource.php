<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Infolist;
// use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Administracion de Usuario';
    protected static ?string $navigationLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Usuarios')
                    ->description('')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->maxLength(255),
                            Forms\Components\TextInput::make('phone')->label('Telefono')
                            ->tel()
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxValue(15)
                            ->maxLength(255),

                        FileUpload::make('image')
                            ->image()
                            ->directory('users'),


                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload(),

                        Select::make('permissions')
                            ->multiple()
                            ->relationship('permissions', 'name')
                            ->preload(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Rol')
                    ->searchable(),
                ImageColumn::make('image')
                    ->height(60)
                    ->circular(),
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
                // Tables\Actions\ViewAction::make()->color('info')->label('Editar'),
                Tables\Actions\EditAction::make()->color('warning')->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Eliminar'),
                // ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ]);
    }





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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),

        ];
    }
    public static function getEloquentQuery(): Builder
    {

        $user = Auth()->user();


        if ($user->roles[0]->name != 'Administrador') {

            $adminIds = User::whereHas('roles', function ($query) {
                $query->where('name', 'Administrador');
            })->pluck('id');


            return parent::getEloquentQuery()->whereNotIn('id', $adminIds);
        }


        return parent::getEloquentQuery();
    }
}
