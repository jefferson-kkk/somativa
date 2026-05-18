<?php

namespace App\Filament\Resources\SolicitacaoSaidas;

use App\Models\SolicitacaoSaida;
use App\Models\Aluno;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SolicitacaoSaidaResource extends Resource
{
    protected static ?string $model = SolicitacaoSaida::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    // Rótulo para o menu
    protected static ?string $navigationLabel = 'Solicitações de Saída';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Dados da Solicitação')
                ->description('Selecione o aluno para carregar os dados institucionais.')
                ->schema([
                    Forms\Components\Select::make('aluno_id')
                        ->label('Aluno')
                        ->relationship('aluno', 'nome')
                        ->searchable()
                        ->preload()
                        ->live() // Fundamental para reagir à mudança
                        ->required()
                        ->afterStateUpdated(function ($state, Set $set) {
                            if ($state) {
                                $aluno = Aluno::find($state);
                                if ($aluno) {
                                    // Puxa o e-mail do professor (se você tiver esse campo no Aluno)
                                    // Ou define um padrão caso queira testar agora:
                                    $set('email_professor', $aluno->email_professor ?? 'coordenacao@escola.com');
                                    $set('observacao', "Turma: {$aluno->turma}");
                                }
                            }
                        }),

                    Forms\Components\TextInput::make('email_professor')
                        ->label('E-mail Institucional do Professor')
                        ->email()
                        ->required()
                        ->helperText('O professor receberá um aviso imediato sobre esta solicitação.'),

                    Forms\Components\TextInput::make('motivo')
                        ->label('Motivo da Saída')
                        ->required()
                        ->placeholder('Ex: Consulta médica, Autorização dos pais'),

                    Forms\Components\DateTimePicker::make('horario_saida')
                        ->label('Horário Previsto')
                        ->required()
                        ->default(now()),

                    Forms\Components\Select::make('status')
                        ->options([
                            'Pendente' => 'Pendente',
                            'Liberado' => 'Liberado',
                            'Recusado' => 'Recusado',
                        ])
                        ->default('Pendente')
                        ->required()
                        ->native(false),

                    Forms\Components\Textarea::make('observacao')
                        ->label('Observações de Turma/Sala')
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('aluno.nome')
                    ->label('Aluno')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('aluno.turma')
                    ->label('Turma'),
                Tables\Columns\TextColumn::make('horario_saida')
                    ->label('Horário')
                    ->dateTime('H:i')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Situação')
                    ->colors([
                        'warning' => 'Pendente',
                        'success' => 'Liberado',
                        'danger' => 'Recusado',
                    ]),
            ])
            ->actions([
                EditAction::make(),

                // AÇÃO DA PORTARIA: O aluno chega, o porteiro valida
                Action::make('confirmar_saida')
                    ->label('Validar Portaria')
                    ->icon('heroicon-m-shield-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar Saída do Aluno')
                    ->modalDescription('O aluno se identificou corretamente? Ao confirmar, o responsável será notificado.')
                    ->visible(fn ($record) => $record->status === 'Pendente')
                    ->action(function (SolicitacaoSaida $record) {
                        $record->update(['status' => 'Liberado']);

                        // Dispara a Notificação SAFE para o Responsável
                        $record->aluno->notify(new \App\Notifications\RegistroFluxoNotification(
                            $record->aluno->nome, 
                            'SAÍDA REALIZADA (CONFIRMADO NA PORTARIA)'
                        ));

                        Notification::make()
                            ->title('Saída Validada!')
                            ->body("O responsável de {$record->aluno->nome} foi avisado.")
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSolicitacaoSaidas::route('/'),
            'create' => Pages\CreateSolicitacaoSaida::route('/create'),
            'edit' => Pages\EditSolicitacaoSaida::route('/{record}/edit'),
        ];
    }
}