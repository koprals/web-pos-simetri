<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalEquipmentResource\Pages;
use App\Models\Equipment;
use App\Models\PaymentMethod;
use App\Models\RentalEquipment;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RentalEquipmentResource extends Resource
{
    protected static ?string $model = RentalEquipment::class;

    protected static ?string $navigationIcon = 'heroicon-m-wrench-screwdriver';

    protected static ?string $navigationLabel = 'Rental Peralatan';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Info Penyewa')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(20),
                                Forms\Components\Textarea::make('note')
                                    ->columnSpanFull(),
                            ]),
                    ]),
                Section::make('Peralatan Disewa')->schema([
                    self::getItemsRepeater(),
                ]),
                Group::make()
                    ->schema([
                        Section::make('Total & Pembayaran')
                            ->schema([
                                Forms\Components\TextInput::make('total_price')
                                    ->required()
                                    ->readOnly()
                                    ->numeric(),
                                Forms\Components\Select::make('payment_method_id')
                                    ->label('Metode Pembayaran')
                                    ->relationship('paymentMethod', 'name')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        $paymentMethod = PaymentMethod::find($state);
                                        $set('is_cash', $paymentMethod?->is_cash ?? false);

                                        if (! $paymentMethod->is_cash) {
                                            $set('change_amount', 0);
                                            $set('paid_amount', $get('total_price'));
                                        }
                                    })
                                    ->afterStateHydrated(function (Forms\Set $set, Forms\Get $get, $state) {
                                        $paymentMethod = PaymentMethod::find($state);

                                        if (! $paymentMethod?->is_cash) {
                                            $set('paid_amount', $get('total_price'));
                                            $set('change_amount', 0);
                                        }

                                        $set('is_cash', $paymentMethod?->is_cash ?? false);
                                    }),
                                Forms\Components\Hidden::make('is_cash')->dehydrated(),
                                Forms\Components\TextInput::make('paid_amount')
                                    ->numeric()
                                    ->label('Nominal Bayar')
                                    ->reactive()
                                    ->readOnly(fn (Forms\Get $get) => $get('is_cash') == false)
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                        self::updateExchangePaid($get, $set);
                                    }),
                                Forms\Components\TextInput::make('change_amount')
                                    ->numeric()
                                    ->label('Kembalian')
                                    ->readOnly(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('Nama Penyewa'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('total_price')->money('IDR')->sortable()->label('Total'),
                Tables\Columns\TextColumn::make('paymentMethod.name')->label('Pembayaran')->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('change_amount')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Tanggal Sewa')->sortable(),
            ])
            ->filters([
                Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('to')->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['to'], fn ($q) => $q->whereDate('created_at', '<=', $data['to']));
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (array $data) {
                        return redirect()->route('rental-equipments.export', [
                            'from' => $data['from'] ?? null,
                            'to' => $data['to'] ?? null,
                        ]);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Export Excel')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('to')->label('Sampai Tanggal'),
                    ])
                    ->color('success'),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentalEquipment::route('/'),
            'create' => Pages\CreateRentalEquipment::route('/create'),
            'edit' => Pages\EditRentalEquipment::route('/{record}/edit'),
        ];
    }

    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('equipmentRentals')
            ->relationship()
            ->live()
            ->columns([
                'md' => 10,
            ])
            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                self::updateTotalPrice($get, $set);
            })
            ->schema([
                Forms\Components\Select::make('equipment_id')
                    ->label('Peralatan')
                    ->required()
                    ->options(Equipment::query()->pluck('name', 'id'))
                    ->columnSpan([
                        'md' => 5,
                    ])
                    ->afterStateHydrated(function (Forms\Set $set, Forms\Get $get, $state) {
                        $equipment = Equipment::find($state);
                        $set('unit_price', $equipment->price ?? 0);
                    })
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        $equipment = Equipment::find($state);
                        $set('unit_price', $equipment->price ?? 0);
                        self::updateTotalPrice($get, $set);
                    })
                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->columnSpan([
                        'md' => 2,
                    ])
                    ->afterStateUpdated(fn ($state, Forms\Set $set, Forms\Get $get) => self::updateTotalPrice($get, $set)),
                Forms\Components\TextInput::make('unit_price')
                    ->label('Harga Satuan')
                    ->required()
                    ->numeric()
                    ->readOnly()
                    ->columnSpan([
                        'md' => 3,
                    ]),
            ]);
    }

    protected static function updateTotalPrice(Forms\Get $get, Forms\Set $set): void
    {
        $selectedEquipments = collect($get('equipmentRentals'))->filter(fn ($item) => ! empty($item['equipment_id']) && ! empty($item['quantity']));

        $prices = Equipment::find($selectedEquipments->pluck('equipment_id'))->pluck('price', 'id');
        $total = $selectedEquipments->reduce(function ($total, $equipment) use ($prices) {
            return $total + ($prices[$equipment['equipment_id']] * $equipment['quantity']);
        }, 0);

        $set('total_price', $total);
    }

    protected static function updateExchangePaid(Forms\Get $get, Forms\Set $set): void
    {
        $paidAmount = (int) $get('paid_amount') ?? 0;
        $totalPrice = (int) $get('total_price') ?? 0;
        $exchangePaid = $paidAmount - $totalPrice;
        $set('change_amount', $exchangePaid);
    }
}
