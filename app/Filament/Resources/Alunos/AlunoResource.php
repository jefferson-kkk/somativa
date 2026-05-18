<?php

namespace App\Filament\Resources\Alunos;

use App\Models\Aluno;
use App\Filament\Resources\Alunos\Pages;
use App\Filament\Resources\SolicitacaoSaidas\SolicitacaoSaidaResource;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class AlunoResource extends Resource
{
    protected static ?string $model = Aluno::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('nome')
                    ->label('Nome do Aluno')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email_responsavel')
                    ->label('E-mail do Responsável')
                    ->email()
                    ->required(),

                Forms\Components\TextInput::make('whatsapp_responsavel')
                    ->label('WhatsApp do Responsável')
                    ->required()
                    ->mask('(+99) 99 99999-9999')
                    ->placeholder('(+55) 19 99999-9999')
                    ->validationMessages([
                        'required' => 'O WhatsApp é obrigatório para a segurança do aluno.',
                    ]),

                Forms\Components\TextInput::make('turma')
                    ->label('Turma')
                    ->placeholder('Ex: 3º Ano A'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('turma')->sortable(),
            ])
            ->actions([
                EditAction::make(),
                
                // BOTÃO NOVO: Leva para o formulário de solicitação já com o ID
                Action::make('solicitar_saida')
                    ->label('Solicitar Saída')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->url(fn (Aluno $record): string => SolicitacaoSaidaResource::getUrl('create', [
                        'aluno_id' => $record->id,
                    ])),

                // BOTÃO DE NOTIFICAÇÃO DIRETA
                Action::make('notificar')
                    ->label('Registrar Entrada')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Aluno $record) {
                        $record->notify(new \App\Notifications\RegistroFluxoNotification($record->nome, 'Entrada na Escola'));

                        Notification::make()
                            ->title('Notificação enviada!')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlunos::route('/'),
            'create' => Pages\CreateAluno::route('/create'),
            'edit' => Pages\EditAluno::route('/{record}/edit'),
        ];
    }
}