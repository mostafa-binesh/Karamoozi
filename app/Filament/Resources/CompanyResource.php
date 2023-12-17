<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('caption')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('company_grade')
                    ->numeric(),
                Forms\Components\TextInput::make('company_boss_id')
                    ->numeric(),
                Forms\Components\TextInput::make('company_number')
                    ->maxLength(11),
                Forms\Components\TextInput::make('company_registry_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('company_phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('company_address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('company_category')
                    ->maxLength(255),
                Forms\Components\TextInput::make('company_postal_code')
                    ->maxLength(255),
                Forms\Components\Toggle::make('company_is_registered'),
                Forms\Components\Toggle::make('company_type')
                    ->required(),
                Forms\Components\Toggle::make('verified')
                    ->required(),
                Forms\Components\TextInput::make('student_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_grade')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_boss_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_registry_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company_postal_code')
                    ->searchable(),
                Tables\Columns\IconColumn::make('company_is_registered')
                    ->boolean(),
                Tables\Columns\IconColumn::make('company_type')
                    ->boolean(),
                Tables\Columns\IconColumn::make('verified')
                    ->boolean(),
                Tables\Columns\TextColumn::make('student_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }    
}
