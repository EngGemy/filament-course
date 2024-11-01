<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{  protected static ?string $recordTitleAttribute = 'name';

    protected  static array $statuses =[
        'in stock' => 'in stock',
        'sold out' => 'sold out',
        'coming soon' => 'coming soon',
    ];
    protected static ?string $model = Product::class;
    protected static ?string $navigationGroup ="Store";
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('Order')
                        ->schema([
                            TextInput::make('name')
                                ->columnSpan(3),
                        ]),
                    Wizard\Step::make('Delivery')
                        ->schema([
                            TextInput::make('price'),
                            Forms\Components\Toggle::make('is_active'),
                        ]),
                    Wizard\Step::make('Billing')
                        ->schema([
                            Select::make('category_id')
                                ->relationship('category','name'),
                            Select::make('status')
                                ->options(ProductResource::$statuses)
                        ]),
                ])



            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextInputColumn::make('name'),
                Tables\Columns\TextColumn::make('price')
                    ->sortable()
                ->money('EGP'),
                TextColumn::make('category.name')
                    ->label('Category Name')
                    ->alignment(Alignment::End)
                    ->url(fn (Product $record): string =>CategoryResource::getUrl('edit',['record' => $record->category_id])),
//                    ->url(function (AppCount $product) { //set URL or lnk for each row in coulmn
//                        return $product->category_id
//                            ? CategoryResource::getUrl('edit', ['record' => $product->category_id])
//                            : null;
//                    }),

        Tables\Columns\SelectColumn::make('status')
                ->options(self::$statuses),
                Tables\Columns\ToggleColumn::make('is_active'),
                TextColumn::make('created_at')
                ->since()

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(ProductResource::$statuses),
                Tables\Filters\SelectFilter::make('category')
                ->relationship('category','name'),
                Tables\Filters\Filter::make('created_from')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            );
                    }),
                Tables\Filters\Filter::make('created_until')
                    ->form([
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ],layout: Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name',   'category.name'];
    }
}
