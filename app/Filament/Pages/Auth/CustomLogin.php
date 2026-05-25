<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login; // Base atualizada da nova versão
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema; // Seu Filament já usa o novo padrão Schema
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse; // <- O CAMINHO CORRETO QUE MATA O ERRO

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

    // A assinatura exatamente igual à que a classe base 'Login' do Filament exige
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Auth::user();

        // Validação da role selecionada
        if ($user->role !== $data['role']) {
            Auth::logout();
            throw ValidationException::withMessages([
                'role' => 'Acesso negado: O seu usuário não possui o perfil selecionado.',
            ]);
        }

        session()->regenerate();

        // Faz o redirecionamento
        $this->redirect('/' . $user->role);

        // Retorna null para satisfazer a regra "?LoginResponse" sem quebrar o Livewire
        return null;
    }
}