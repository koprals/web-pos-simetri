<?php

namespace App\Livewire;

use App\Models\Court;
use App\Models\PaymentMethod;
use App\Models\RentalCourt;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class RentCourt extends Component implements HasForms
{
    use InteractsWithForms;

    public $search = '';

    public $tanggal;

    public $selectedCourt = null;

    public $selectedTimes = [];

    public $totalPrice = 0;

    public $paidAmount = 0;

    public $changeAmount = 0;

    public $paymentMethodId;

    public $name;

    public $phone;

    public $note;

    public $availableHours = [];

    public function render()
    {
        return view('livewire.rent-court', [
            'courts' => Court::where('is_active', '>', 0)
                ->search($this->search)
                ->paginate(12),
            'paymentMethods' => PaymentMethod::all(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Checkout')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Booking')
                            ->required()
                            ->default(now())
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->tanggal = $state;
                                $this->selectedTimes = [];
                                $this->availableHours = $this->getAvailableHours();
                            }),
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Customer')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('No. Telephone')
                            ->required()
                            ->maxLength(15),
                        Forms\Components\TextInput::make('totalPrice')
                            ->label('Total Harga')
                            ->readOnly()
                            ->numeric(),
                        Forms\Components\TextInput::make('paidAmount')
                            ->label('Jumlah Dibayar')
                            ->numeric()
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->changeAmount = $state - $this->totalPrice;
                            }),
                        Forms\Components\TextInput::make('changeAmount')
                            ->label('Kembalian')
                            ->readOnly()
                            ->numeric(),
                        Forms\Components\Select::make('paymentMethodId')
                            ->required()
                            ->label('Metode Pembayaran')
                            ->options(PaymentMethod::pluck('name', 'id')),
                        Forms\Components\Textarea::make('note')
                            ->label('Catatan'),
                    ]),
            ]);
    }

    public function mount()
    {
        $this->tanggal = now()->format('Y-m-d');
        $this->form->fill();
    }

    public function selectCourt($courtId)
    {
        $this->selectedCourt = $courtId;
        $this->selectedTimes = [];
        $this->availableHours = $this->getAvailableHours();
        $this->calculateTotal();
    }

    public function toggleTime($time)
    {
        $currentTimes = is_array($this->selectedTimes) ? $this->selectedTimes : [];

        if (($key = array_search($time, $currentTimes)) !== false) {
            unset($currentTimes[$key]);
        } else {
            $currentTimes[] = $time;
        }

        $this->selectedTimes = array_values(array_unique($currentTimes));
        sort($this->selectedTimes);
        $this->calculateTotal();
    }

    public function getAvailableHours()
    {
        if (! $this->selectedCourt || ! $this->tanggal) {
            return [];
        }

        $bookedTimes = RentalCourt::where('court_id', $this->selectedCourt)
            ->whereDate('start_time', $this->tanggal)
            ->get()
            ->map(function ($rental) {
                return [
                    'start' => Carbon::parse($rental->start_time)->format('H:i'),
                    'end' => Carbon::parse($rental->end_time)->format('H:i'),
                ];
            });

        $availableHours = [];
        for ($hour = 8; $hour <= 22; $hour++) {
            $time = sprintf('%02d:00', $hour);
            $timeEnd = sprintf('%02d:00', $hour + 1);
            $isAvailable = true;

            foreach ($bookedTimes as $booked) {
                if (($time >= $booked['start'] && $time < $booked['end']) ||
                    ($timeEnd > $booked['start'] && $timeEnd <= $booked['end'])) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $availableHours[] = $time;
            }
        }

        return $availableHours;
    }

    public function calculateTotal()
    {
        $total = 0;
        if ($this->selectedCourt) {
            $court = Court::find($this->selectedCourt);
            $total = $court->price * count($this->selectedTimes);
        }
        $this->totalPrice = $total;
        $this->changeAmount = $this->paidAmount - $total;

        return $total;
    }

    public function checkout()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required',
            'paymentMethodId' => 'required',
            'selectedCourt' => 'required',
            'selectedTimes' => 'required|array|min:1',
            'tanggal' => 'required|date',
        ]);

        foreach ($this->selectedTimes as $time) {
            $startTime = Carbon::parse($this->tanggal.' '.$time);
            $endTime = $startTime->copy()->addHour();

            RentalCourt::create([
                'court_id' => $this->selectedCourt,
                'name' => $this->name,
                'phone' => $this->phone,
                'total_price' => $this->totalPrice,
                'note' => $this->note,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'payment_method_id' => $this->paymentMethodId,
                'paid_amount' => $this->paidAmount,
                'change_amount' => $this->changeAmount,
            ]);
        }

        Notification::make()
            ->title('Booking berhasil dibuat')
            ->success()
            ->send();

        $this->reset(['selectedCourt', 'selectedTimes', 'totalPrice', 'paidAmount', 'changeAmount']);
        $this->form->fill(['totalPrice' => 0, 'paidAmount' => 0, 'changeAmount' => 0]);
    }
}
