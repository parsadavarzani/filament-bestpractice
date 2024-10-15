<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Widgets\ProductStatsWidget;
use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Product';

    public static function getNavigationBadge(): ?string
    {
        return Product::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Product::getForm())
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
           // ->persistFiltersInSession()
           // ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('product-image')
                    ->label('Image')
                    ->collection('product-images')
                    ->defaultImageUrl(url('/images/new-product.jpg')),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                Tables\Columns\TextColumn::make('qty')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
//                Tables\Filters\Filter::make('is_visible')
//                    ->label('Visibility')
//                    ->query(function (Builder $query) {
//                        return $query->where('is_visible', 1);
//                    }),
                Tables\Filters\Filter::make('is_visible')
                    ->label('Visibility')
                    ->toggle()
                    ->query(function (Builder $query) {
                        return $query->where('is_visible', 1);
                    }),
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->indicator('Administrators')
                    ->attribute('is_visible')
                    ->placeholder('You can see with products are visible and witch are not')
                    ->trueLabel('do you want to see visible products ?')
                    ->falseLabel('do you want to see invisible products ?'),
//                    ->queries(
//                        true: fn (Builder $query) => $query->withTrashed(),
//                        false: fn (Builder $query) => $query->onlyTrashed(),
//                        blank: fn (Builder $query) => $query->withoutTrashed(),
//                    )

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until')->default(now()),

                    ])->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()->color('info'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            ProductStatsWidget::class,
        ];
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
}
