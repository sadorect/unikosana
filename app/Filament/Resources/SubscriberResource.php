<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriberResource\Pages;
use App\Models\Subscriber;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubscriberResource extends Resource
{
    protected static ?string $model = Subscriber::class;

    protected static ?string $navigationIcon = 'heroicon-o-at-symbol';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Newsletter Subscribers';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('name'),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Subscribed')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn () => static::exportCsv()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function exportCsv(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $filename = 'subscribers-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Email', 'Name', 'Active', 'Subscribed At']);
            Subscriber::orderBy('created_at')->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $row) {
                    fputcsv($out, [
                        $row->email,
                        $row->name,
                        $row->is_active ? 'yes' : 'no',
                        $row->created_at?->toDateTimeString(),
                    ]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscribers::route('/'),
            'create' => Pages\CreateSubscriber::route('/create'),
            'edit' => Pages\EditSubscriber::route('/{record}/edit'),
        ];
    }
}
