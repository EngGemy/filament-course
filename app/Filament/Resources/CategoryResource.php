<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Fieldset;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $model = Category::class;
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup ="Store";
    protected static ?string $navigationIcon = 'heroicon-s-folder';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([



                TextInput::make('name'),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Fieldset::make('Label')
                    ->schema([
                        TextEntry::make('name')
                            ->badge(),
                        TextEntry::make('created_at')
                            ->dateTime()
                    ])

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'), //add
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return CategoryResource::getUrl('view', ['record' => $record]);
    }
}
