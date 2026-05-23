<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nome')
                    ->required(),
                TextInput::make('turma')
                    ->required(),
                TextInput::make('termo')
                    ->required(),
                TextInput::make('responsavel')
                    ->label('Responsavel/professor')
                    ->required(),
            ]);
    }
}
