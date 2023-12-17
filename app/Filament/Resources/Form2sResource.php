<?php

namespace App\Filament\Resources;

use App\Enums\VerificationStatusEnum;
use App\Filament\Resources\Form2sResource\Pages;
use App\Filament\Resources\Form2sResource\RelationManagers;
use App\Models\Form2s;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Form2sResource extends Resource
{
    protected static ?string $model = Form2s::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student_id')
                    ->numeric(),
                Forms\Components\TextInput::make('industry_supervisor_id')
                    ->numeric(),
                Forms\Components\TextInput::make('introduction_letter_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('introduction_letter_date')
                    ->required(),
                Forms\Components\TextInput::make('internship_department')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('supervisor_position')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('internship_started_at')
                    ->required(),
                Forms\Components\TextInput::make('internship_website')
                    ->maxLength(255),
                Forms\Components\Textarea::make('schedule_table')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('rejection_reason')
                    ->maxLength(255),
                Select::make('verified')
                    ->options(VerificationStatusEnum::class)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('industry_supervisor_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('introduction_letter_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('introduction_letter_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('internship_department')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supervisor_position')
                    ->searchable(),
                Tables\Columns\TextColumn::make('internship_started_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('internship_website')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rejection_reason')
                    ->searchable(),
                Tables\Columns\IconColumn::make('verified')
                    ->boolean(),
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
            'index' => Pages\ListForm2s::route('/'),
            'create' => Pages\CreateForm2s::route('/create'),
            'edit' => Pages\EditForm2s::route('/{record}/edit'),
        ];
    }
}
