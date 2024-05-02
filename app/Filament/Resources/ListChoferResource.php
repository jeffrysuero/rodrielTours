<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListChoferResource\Pages;
use App\Filament\Resources\ListChoferResource\RelationManagers;
use App\Models\ListChofer;
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
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Components;


class ListChoferResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';
    protected static ?string $navigationLabel = 'Choferes';
    public static function form(Form $form): Form
    {
        $nombreRolConductores = 'Conductores';
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

                        FileUpload::make('image')
                            ->image()
                            ->directory('users'),

                            Forms\Components\TextInput::make('percentage')->label('Porcentage a pagar por viaje')
                            ->required()
                            ->numeric(),
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name', function ($query) {
                                $query->where('name', 'Conductores');
                            })
                            ->required()
                            ->preload()


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
                ImageColumn::make('image')
                    ->height(60)
                    ->circular(),
                    Tables\Columns\TextColumn::make('percentage')->label('Porcentage Ganancias')
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
                Tables\Actions\ViewAction::make()->color('success')->label('pagar')->icon('heroicon-o-banknotes'),
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
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()
                    ->schema([
                        Components\Split::make([
                            Components\Grid::make(2)
                                ->schema([
                                    Components\Group::make([
                                        Components\TextEntry::make('name')->label('Nombre'),
                                        Components\TextEntry::make('email')->label('Email'),
                                        // Components\TextEntry::make('published_at')
                                        //     ->badge()
                                        //     ->date()
                                        //     ->color('success'),
                                    ]),
                                    Components\Group::make([
                                        Components\TextEntry::make(''),
                                        Components\TextEntry::make(''),
                                        // Components\SpatieTagsEntry::make('tags'),
                                    ]),

                                    Components\Group::make([
                                        // Components\TextEntry::make('marca'),
                                        // Components\TextEntry::make('modelo'),
                                        // Components\SpatieTagsEntry::make('tags'),

                                    ]),
                                ]),
                            Components\ImageEntry::make('image')
                                ->hiddenLabel()
                                ->grow(false),
                        ])->from('lg'),

                    ]),
                Components\Section::make('Content')
                    ->schema([
                        Components\TextEntry::make('content')
                            ->prose()
                            ->markdown()
                            ->hiddenLabel(),

                    ])
                    ->collapsible(),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewUser::class,
            Pages\CompletedPay::class,

        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListListChofers::route('/'),
            'create' => Pages\CreateListChofer::route('/create'),
            'edit' => Pages\EditListChofer::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
            'pay' => Pages\CompletedPay::route('/{record}/pay'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {

        $modelRole = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->join('users', 'model_has_roles.model_id', '=', 'users.id')
            ->where('roles.name', 'Conductores')
            ->pluck('users.id');

        $allUserIds = $modelRole->toArray();
        return parent::getEloquentQuery()->whereIn('id', $allUserIds);
    }
}
