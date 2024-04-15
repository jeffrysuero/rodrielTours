<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;
    public function filtersForm(Form $form): Form
    {
        $user = Auth()->user();
        if ($user->roles[0]->name === 'Administrador') {

            return $form
                ->schema([
                    Section::make()
                        ->schema([
                            // Select::make('businessCustomersOnly')
                            //     ->boolean(),
                            DatePicker::make('startDate')->label('Fecha Inicial')
                                ->maxDate(fn (Get $get) => $get('endDate') ?: now()),
                            DatePicker::make('endDate')->label('Fecha Final')
                                ->minDate(fn (Get $get) => $get('startDate') ?: now())
                                ->maxDate(now()),
                        ])
                        ->columns(2),
                ]);
        }
        return $form;
    }
}
