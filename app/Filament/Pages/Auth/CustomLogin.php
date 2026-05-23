<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class CustomLogin extends Login
{
    public function form(Schema $schema): Schema
    {
        $schema = parent::form($schema);

        return $schema
            ->components([
                ...$schema->getComponents(),
                Select::make('role')
                    ->label('Acessar como')
                    ->options([
                        'admin' => 'Administrador',
                        'professor' => 'Professor',
                        'portaria' => 'Portaria',
                    ])
                    ->required(),
            ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
        ];
    }
}
                                                                                                                          
