<?php

namespace App\Livewire;

use App\Models\Equipment;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

class RentEquipment extends Component implements HasForms
{
    use InteractsWithForms;

    public $search = '';

    public $name_customer = '';

    public $gender = '';

    public $payment_method_id = 0;

    public $payment_methods;

    public $order_equipments = [];

    public $total_price;

    protected $listeners = [
        'loadOrderEquipments',
        'scanResult' => 'handleScanResult',
    ];

    public function render()
    {
        return view('livewire.rent-equipment', [
            'equipments' => Equipment::where('stock', '>', 0)
                ->search($this->search)
                ->paginate(12),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Checkout')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Customer')
                            ->required()
                            ->maxLength(255)
                            ->default(fn () => $this->name),
                        Forms\Components\TextInput::make('phone')
                            ->label('No. Telephone')
                            ->required()
                            ->maxLength(255)
                            ->default(fn () => $this->phone),
                        Forms\Components\TextInput::make('total_price')
                            ->label('Total Harga')
                            ->readOnly()
                            ->numeric()
                            ->default(fn () => $this->total_price),
                        Forms\Components\Select::make('payment_method_id')
                            ->required()
                            ->label('Payment Method')
                            ->options($this->payment_methods->pluck('name', 'id')),
                        Forms\Components\TextInput::make('note')
                            ->label('Note')
                            ->required()
                            ->maxLength(255)
                            ->default(fn () => $this->note),
                    ]),
            ]);
    }

    public function mount()
    {
        if (session()->has('orderEquipments')) {
            $this->order_equipments = session('orderEquipments');
        }
        $this->payment_methods = PaymentMethod::all();
        $this->form->fill(['payment_methods', $this->payment_methods]);
    }

    public function addToOrder($equipmentId)
    {
        $equipment = Equipment::find($equipmentId);
        if ($equipment) {
            if ($equipment->stock <= 0) {
                Notification::make()
                    ->title('Stok habis')
                    ->danger()
                    ->send();

                return;
            }

            $existingItemKey = null;
            foreach ($this->order_equipments as $key => $item) {
                if ($item['equipment_id'] == $equipmentId) {
                    $existingItemKey = $key;
                    break;
                }
            }

            if ($existingItemKey !== null) {
                $this->order_equipments[$existingItemKey]['quantity']++;
            } else {
                $this->order_equipments[] = [
                    'equipment_id' => $equipment->id,
                    'name' => $equipment->name,
                    'price' => $equipment->price,
                    'image_url' => $equipment->image_url,
                    'quantity' => 1,
                ];
            }

            session()->put('orderEquipments', $this->order_equipments);
            Notification::make()
                ->title('Alat ditambahkan ke keranjang')
                ->success()
                ->send();

        }
    }

    public function loadorderEquipments($orderEquipments)
    {
        $this->order_equipments = $orderEquipments;
        session()->put('orderEquipments', $orderEquipments);
    }

    public function increaseQuantity($equipment_id)
    {
        $equipment = Equipment::find($equipment_id);

        if (! $equipment) {
            Notification::make()
                ->title('Alat tidak ditemukan')
                ->danger()
                ->send();

            return;
        }

        foreach ($this->order_equipments as $key => $item) {
            if ($item['equipment_id'] == $equipment_id) {
                if ($equipment->stock >= $item['quantity'] + 1) {
                    $this->order_equipments[$key]['quantity']++;
                } else {
                    Notification::make()
                        ->title('Stok barang tidak mencukupi')
                        ->danger()
                        ->send();
                }
                break;
            }
        }

        session()->put('orderEquipments', $this->order_equipments);
    }

    public function decreaseQuantity($equipment_id)
    {
        foreach ($this->order_equipments as $key => $item) {
            if ($item['equipment_id'] == $equipment_id) {
                if ($this->order_equipments[$key]['quantity'] > 1) {
                    $this->order_equipments[$key]['quantity']--;
                } else {
                    unset($this->order_equipments[$key]);
                    $this->order_equipments = array_values($this->order_equipments);
                }
                break;
            }
        }
        session()->put('orderEquipments', $this->order_equipments);
    }

    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->order_equipments as $item) {
            $total += $item['quantity'] * $item['price'];
        }
        $this->total_price = $total;

        return $total;
    }

    public function checkout()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'payment_method_id' => 'required',
        ]);

        $payment_method_id_temp = $this->payment_method_id;

        $order = RentalEquipment::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'total_price' => $this->calculateTotal(),
            'payment_method_id' => $payment_method_id_temp,
            'note' => $this->note,
        ]);

        foreach ($this->order_equipments as $item) {
            OrderEquipment::create([
                'order_id' => $order->id,
                'equipment_id' => $item['equipment_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ]);
        }

        $this->order_equipments = [];
        session()->forget(['orderEquipments']);

        return redirect()->to('admin/orders');
    }

    public function handleScanResult($decodeText)
    {
        $equipment = Equipment::where('barcode', $decodeText)->first();

        if ($equipment) {
            $this->addToOrder($equipment->id);
        } else {
            Notifiction::make()
                ->title('Equipment not found '.$decodeText)
                ->danger()
                ->send();
        }
    }
}
