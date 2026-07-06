<?php

namespace App\Filament\Resources;

use App\Enums\MemberStatus;
use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Organization';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Profile')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('membership_id')
                            ->maxLength(255),
                        Forms\Components\SpatieMediaLibraryFileUpload::make('photo')
                            ->collection('photo')
                            ->image()
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Location & Background')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('country')
                            ->default('United States')
                            ->required(),
                        Forms\Components\TextInput::make('state_province')
                            ->label('State / Province'),
                        Forms\Components\TextInput::make('occupation'),
                        Forms\Components\TextInput::make('school'),
                        Forms\Components\TextInput::make('graduation_year')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(2100),
                    ]),
                Forms\Components\Section::make('Contact & Bio')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('contact_email')
                            ->email(),
                        Forms\Components\TextInput::make('contact_phone')
                            ->tel(),
                        Forms\Components\Textarea::make('biography')
                            ->rows(4)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->options(MemberStatus::class)
                            ->default(MemberStatus::Approved)
                            ->required(),
                        Forms\Components\Toggle::make('is_public')
                            ->label('Show in public directory')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('photo')
                    ->collection('photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('state_province')
                    ->label('State / Province')
                    ->searchable(),
                Tables\Columns\TextColumn::make('school')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('graduation_year')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_public')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(MemberStatus::class),
                Tables\Filters\SelectFilter::make('country')
                    ->options(fn () => Member::query()->distinct()->pluck('country', 'country')->filter()->toArray()),
                Tables\Filters\TernaryFilter::make('is_public'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Member $record) => $record->status !== MemberStatus::Approved)
                    ->requiresConfirmation()
                    ->action(fn (Member $record) => $record->update([
                        'status' => MemberStatus::Approved,
                        'is_public' => true,
                    ])),
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Member $record) => $record->status === MemberStatus::Pending)
                    ->requiresConfirmation()
                    ->action(fn (Member $record) => $record->update([
                        'status' => MemberStatus::Rejected,
                        'is_public' => false,
                    ])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
