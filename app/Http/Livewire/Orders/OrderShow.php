<?php

namespace App\Http\Livewire\Orders;

use App\Models\Order;
use App\Models\OrderAgentNote;
use Livewire\Component;

class OrderShow extends Component
{
    public Order $order;
    public $note;

    protected $rules = [
        'note' => 'required|min:3|max:255',
    ];

    public function render()
    {
        return view('livewire.orders.show');
    }

    public function addNewNote()
    {
        $this->validate();
        if (OrderAgentNote::whereAgentId(auth()->id())
                          ->whereOrderId($this->order->id)
                          ->whereMessage($this->note)->exists()
        ) {
            $this->emit('showToast', [
                'icon' => 'error',
                'message' => 'This message has been sent before',
            ]);
        } else {
            $orderAgentNote = new OrderAgentNote();
            $orderAgentNote->message = $this->note;
            $orderAgentNote->order_id = $this->order->id;
            $orderAgentNote->agent_id = auth()->id();
            $orderAgentNote->save();

            $this->note = null;
            $this->emit('showToast', [
                'icon' => 'success',
                'message' => 'Note saved successfully',
            ]);
        }

    }
}
