<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup ="Orders";
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                ->relationship('user','name')
                ->required()
                ->label('User'),
                Forms\Components\Select::make('product_id')
                    ->relationship('product','name')
                    ->required()
                    ->label('Product'),
                Forms\Components\TextInput::make('price')
                ->numeric()
                    ->label('Price')
                ->step(0.01),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('product.name'),
                Tables\Columns\TextColumn::make('price')
                ->money('EGP')
                ->summarize(Tables\Columns\Summarizers\Sum::make())
                ,

            ])
            ->defaultSort('created_at','desc')
//            ->defaultGroup('product.name')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('mark Complete') //vreate custom actions for compelte
                    ->requiresConfirmation() // required Yes or No dialouge
                    ->hidden(fn(Order $record) => $record->is_completed)  //hide action mark complete if he is already markeed
                    ->action(fn (Order $ahmed) => $ahmed->update(['is_completed' => true,'price' => 0]))
                        ->icon('heroicon-o-check-badge'),
                    Tables\Actions\Action::make('delete')
                        ->requiresConfirmation()
                        ->action(fn (Order $record) => $record->delete())
                ])
            ])
            ->headerActions([
                Tables\Actions\Action::make('New order')
                ->url(fn() => OrderResource::getUrl('create')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Mark Comlpleted')
                    ->requiresConfirmation()
                        ->icon('heroicon-o-check-badge')
                    ->action(fn(Collection  $record) => $record->each->update(['is_completed' => true,'price' => 0]))
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return  Order::whereDate('created_at',today())->count() ? 'NEW' : "" ;
    }
}

