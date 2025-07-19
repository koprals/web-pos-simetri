<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalCourtResource\Pages;
use App\Models\PaymentMethod;
use App\Models\RentalCourt;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RentalCourtResource extends Resource
{
    protected static ?string $model = RentalCourt::class;

    protected static ?string $navigationIcon = 'heroicon-m-calendar-days';

    protected static ?string $navigationLabel = 'Rental Lapangan';

    protected static ?int $navigationSort = 3;

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
                            ]),
                    ]),
                Group::make()
                    ->schema([
                        Section::make('Detail Penyewaan')
                            ->schema([
                                Forms\Components\Select::make('court_id')
                                    ->label('Lapangan')
                                    ->relationship('court', 'name')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('start_time')
                                    ->label('Waktu Mulai')
                                    ->required(),
                                Forms\Components\DateTimePicker::make('end_time')
                                    ->label('Waktu Selesai')
                                    ->required()
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                        self::updateTotalPrice($get, $set);
                                    }),
                                Forms\Components\TextInput::make('total_price')
                                    ->required()
                                    ->numeric()
                                    ->readOnly(),
                                Forms\Components\Textarea::make('note')
                                    ->columnSpanFull(),
                            ]),
                    ]),
                Group::make()
                    ->schema([
                        Section::make('Pembayaran')
                            ->schema([
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
                Tables\Columns\TextColumn::make('court.name')->label('Lapangan'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('total_price')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('paymentMethod.name')->label('Pembayaran')->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('change_amount')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('start_time')->dateTime()->label('Mulai'),
                Tables\Columns\TextColumn::make('end_time')->dateTime()->label('Selesai'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Dibuat')->sortable(),
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
                        return redirect()->route('rental-courts.export', [
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
            'index' => Pages\ListRentalCourts::route('/'),
            'create' => Pages\CreateRentalCourt::route('/create'),
            'edit' => Pages\EditRentalCourt::route('/{record}/edit'),
        ];
    }

    protected static function updateTotalPrice(Forms\Get $get, Forms\Set $set): void
    {
        $start = $get('start_time');
        $end = $get('end_time');

        if ($start && $end) {
            $duration = \Carbon\Carbon::parse($end)->diffInHours(\Carbon\Carbon::parse($start));
            $pricePerHour = 50000; // Ubah sesuai harga sewa per jam
            $set('total_price', $duration * $pricePerHour);
        }
    }

    protected static function updateExchangePaid(Forms\Get $get, Forms\Set $set): void
    {
        $paidAmount = (int) $get('paid_amount') ?? 0;
        $totalPrice = (int) $get('total_price') ?? 0;
        $exchangePaid = $paidAmount - $totalPrice;
        $set('change_amount', $exchangePaid);
    }
}
