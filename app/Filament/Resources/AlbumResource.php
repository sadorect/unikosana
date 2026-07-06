<?php

namespace App\Filament\Resources;

use App\Enums\AlbumType;
use App\Filament\Resources\AlbumResource\Pages;
use App\Models\Album;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AlbumResource extends Resource
{
    protected static ?string $model = Album::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->options(AlbumType::class)
                            ->default(AlbumType::Photo)
                            ->required()
                            ->live(),
                        Forms\Components\Select::make('event_id')
                            ->relationship('event', 'title')
                            ->searchable()
                            ->preload()
                            ->label('Related event (optional)'),
                        Forms\Components\TextInput::make('year')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(2100),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                    ]),
                Forms\Components\SpatieMediaLibraryFileUpload::make('photos')
                    ->collection('photos')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->visible(fn (Forms\Get $get) => $get('type') === AlbumType::Photo->value)
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('videos')
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->label('Video URL (YouTube / Vimeo)')
                            ->url()
                            ->required(),
                        Forms\Components\TextInput::make('title'),
                    ])
                    ->columns(2)
                    ->visible(fn (Forms\Get $get) => $get('type') === AlbumType::Video->value)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('year', 'desc')
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('photos')
                    ->collection('photos')
                    ->limit(1),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('event.title')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('year')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(AlbumType::class),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlbums::route('/'),
            'create' => Pages\CreateAlbum::route('/create'),
            'edit' => Pages\EditAlbum::route('/{record}/edit'),
        ];
    }
}
