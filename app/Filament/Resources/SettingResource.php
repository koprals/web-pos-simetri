<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'Lainnya';

    public static function canCreate(): bool
    {
        return false; // disable tombol Create
    }

    public static function canDelete($record): bool
    {
        return false; // disable tombol Delete
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->disabled()
                    ->label('Section Key'),

                Forms\Components\Group::make([
                    // HERO SECTION
                    Forms\Components\TextInput::make('value.title')
                        ->label('Hero Title')
                        ->visible(fn ($record) => $record && $record->key === 'hero_section'),

                    Forms\Components\Textarea::make('value.subtitle')
                        ->label('Hero Subtitle')
                        ->visible(fn ($record) => $record && $record->key === 'hero_section'),

                    Forms\Components\FileUpload::make('value.background_image')
                        ->label('Hero Background Image')
                        ->image()
                        ->directory('hero-backgrounds')
                        ->visible(fn ($record) => $record && $record->key === 'hero_section'),

                    Forms\Components\TextInput::make('value.button_text')
                        ->label('Button Text')
                        ->visible(fn ($record) => $record && $record->key === 'hero_section'),

                    Forms\Components\TextInput::make('value.button_link')
                        ->label('Button Link')
                        ->visible(fn ($record) => $record && $record->key === 'hero_section'),

                    // SOCIAL MEDIA SECTION
                    Forms\Components\TextInput::make('value.facebook')
                        ->label('Facebook URL')
                        ->visible(fn ($record) => $record && $record->key === 'social_media'),

                    Forms\Components\TextInput::make('value.instagram')
                        ->label('Instagram URL')
                        ->visible(fn ($record) => $record && $record->key === 'social_media'),

                    Forms\Components\TextInput::make('value.tiktok')
                        ->label('Tiktok URL')
                        ->visible(fn ($record) => $record && $record->key === 'social_media'),

                    // CONTACT SECTION
                    Forms\Components\TextInput::make('value.phone')
                        ->label('Phone Number')
                        ->visible(fn ($record) => $record && $record->key === 'contact_info'),

                    Forms\Components\TextInput::make('value.email')
                        ->label('Email Address')
                        ->visible(fn ($record) => $record && $record->key === 'contact_info'),

                    Forms\Components\Textarea::make('value.address')
                        ->label('Address')
                        ->visible(fn ($record) => $record && $record->key === 'contact_info'),

                    // LOGO SECTION
                    Forms\Components\TextInput::make('value.logo_text')
                        ->label('Logo Text')
                        ->visible(fn ($record) => $record && $record->key === 'site_logo'),

                    Forms\Components\FileUpload::make('value.logo_image')
                        ->label('Logo Image')
                        ->image()
                        ->directory('logo-images')
                        ->visible(fn ($record) => $record && $record->key === 'site_logo'),

                    // OPERATIONAL SECTION
                    Forms\Components\TextInput::make('value.open_time')
                        ->label('Jam Buka')
                        ->visible(fn ($record) => $record && $record->key === 'operational_hours'),

                    Forms\Components\TextInput::make('value.close_time')
                        ->label('Jam Tutup')
                        ->visible(fn ($record) => $record && $record->key === 'operational_hours'),

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->label('Section'),
                Tables\Columns\TextColumn::make('updated_at')->label('Updated'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
