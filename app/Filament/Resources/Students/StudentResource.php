<?php

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\Pages\CreateStudent;
use App\Filament\Resources\Students\Pages\EditStudent;
use App\Filament\Resources\Students\Pages\ListStudents;
use App\Filament\Resources\Students\Pages\ViewStudent;
use App\Models\Student;
use App\Models\User;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Throwable;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('turma')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('termo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('responsavel')
                    ->label('Responsavel/professor')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome')->searchable(),
                Tables\Columns\TextColumn::make('turma')->sortable(),
                Tables\Columns\TextColumn::make('termo'),
                Tables\Columns\TextColumn::make('responsavel')->label('Responsavel/professor'),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\Action::make('solicitar_saida')
                    ->label('Solicitar saida')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->color('warning')
                    ->visible(fn (): bool => Filament::getCurrentOrDefaultPanel()->getId() === 'admin')
                    ->form([
                        Forms\Components\TextInput::make('motivo_saida')
                            ->required()
                            ->label('Motivo da saida'),
                        Forms\Components\TimePicker::make('horario_saida')
                            ->required()
                            ->label('Horario da saida'),
                    ])
                    ->action(function (Student $record, array $data): void {
                        $exitRequest = $record->exitRequests()->create([
                            'motivo_saida' => $data['motivo_saida'],
                            'horario_saida' => $data['horario_saida'],
                            'status' => 'pendente',
                        ]);

                        $professors = User::query()
                            ->where('role', 'professor')
                            ->where('name', $record->responsavel)
                            ->get();

                        foreach ($professors as $professor) {
                            try {
                                Mail::raw(
                                    "O aluno {$record->nome} teve saida solicitada.\nHorario: {$exitRequest->horario_saida}\nMotivo: {$exitRequest->motivo_saida}",
                                    fn ($message) => $message
                                        ->to($professor->email)
                                        ->subject("Solicitacao de saida - {$record->nome}")
                                );
                            } catch (Throwable $exception) {
                                report($exception);
                            }
                        }

                        Notification::make()
                            ->title('Solicitacao enviada para a portaria.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'view' => ViewStudent::route('/{record}'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }
}
