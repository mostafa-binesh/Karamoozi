<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make("faculty_id")
                    ->relationship(name: "universityFaculty", titleAttribute: "faculty_name"),
                Forms\Components\TextInput::make('professor_id')
                    ->numeric(),
                Forms\Components\Select::make('term_id')
                    ->relationship(name: "term", titleAttribute: "name"),
                Forms\Components\Select::make('company_id')
                    ->relationship(name: 'company', titleAttribute: "company_name"),
                Forms\Components\TextInput::make('student_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('entrance_year')
                    ->numeric(),
                Forms\Components\TextInput::make('supervisor_id')
                    ->numeric(),
                Forms\Components\TextInput::make('grade')
                    ->numeric(),
                Forms\Components\Toggle::make('passed_units'),
                Forms\Components\Toggle::make('semester'),
                Forms\Components\TextInput::make('internship_year')
                    ->numeric(),
                Forms\Components\TextInput::make('internship_type')
                    ->numeric(),
                Forms\Components\Toggle::make('verified')
                    ->required(),
                Forms\Components\Toggle::make('pre_reg_done')
                    ->required(),
                Forms\Components\Toggle::make('faculty_verified')
                    ->required(),
                Forms\Components\Toggle::make('stage')
                    ->required(),
                Forms\Components\Toggle::make('pre_reg_verified')
                    ->required(),
                Forms\Components\TextInput::make('init_reg_rejection_reason')
                    ->maxLength(255),
                Forms\Components\TextInput::make('pre_reg_rejection_reason')
                    ->maxLength(255),
                Forms\Components\Toggle::make('expert_verification')
                    ->required(),
                Forms\Components\Toggle::make('supervisor_in_faculty_verification')
                    ->required(),
                Forms\Components\Toggle::make('internship_master_verification')
                    ->required(),
                Forms\Components\Toggle::make('educational_assistant_verification')
                    ->required(),
                Forms\Components\DatePicker::make('internship_started_at'),
                Forms\Components\DatePicker::make('internship_finished_at'),
                Forms\Components\Toggle::make('internship_status')
                    ->required(),
                Forms\Components\Textarea::make('evaluations')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('evaluations_verified')
                    ->required(),
                Forms\Components\Toggle::make('form4_verified')
                    ->required(),
                Forms\Components\Toggle::make('supervisor_verification')
                    ->required(),
                Forms\Components\Toggle::make('internship_finished')
                    ->required(),
                Forms\Components\DateTimePicker::make('email_verified_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.fullName')
                    ->sortable(),
                Tables\Columns\TextColumn::make('universityFaculty.faculty_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('professor.fullName')
                    ->sortable(),
                Tables\Columns\TextColumn::make('term.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.company_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('student_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('entrance_year')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('industrySupervisor.user.fullName')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('grade')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('passed_units')
                    ->boolean(),
                Tables\Columns\IconColumn::make('semester')
                    ->boolean(),
                Tables\Columns\TextColumn::make('internship_year')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('internship_type')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('verified')
                    ->boolean(),
                Tables\Columns\IconColumn::make('pre_reg_done')
                    ->boolean(),
                Tables\Columns\IconColumn::make('faculty_verified')
                    ->boolean(),
                Tables\Columns\IconColumn::make('stage')
                    ->boolean(),
                Tables\Columns\IconColumn::make('pre_reg_verified')
                    ->boolean(),
                Tables\Columns\TextColumn::make('init_reg_rejection_reason')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pre_reg_rejection_reason')
                    ->searchable(),
                Tables\Columns\IconColumn::make('expert_verification')
                    ->boolean(),
                Tables\Columns\IconColumn::make('supervisor_in_faculty_verification')
                    ->boolean(),
                Tables\Columns\IconColumn::make('internship_master_verification')
                    ->boolean(),
                Tables\Columns\IconColumn::make('educational_assistant_verification')
                    ->boolean(),
                Tables\Columns\TextColumn::make('internship_started_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('internship_finished_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('internship_status')
                    ->boolean(),
                Tables\Columns\IconColumn::make('evaluations_verified')
                    ->boolean(),
                Tables\Columns\IconColumn::make('form4_verified')
                    ->boolean(),
                Tables\Columns\IconColumn::make('supervisor_verification')
                    ->boolean(),
                Tables\Columns\IconColumn::make('internship_finished')
                    ->boolean(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
