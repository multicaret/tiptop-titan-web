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
        'order.status' => 'required|numeric',
        'note' => 'required|min:3|max:255',
    ];

    public function updatedOrderStatus($newValue)
    {
        $this->order->status = $newValue;
        $this->order->save();
        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Status updated successfully',
        ]);
    }

    public function render()
    {
        return view('livewire.orders.show');
    }

    public function addNewNote()
    {
        $this->validate();
        $previousNote = OrderAgentNote::whereAgentId(auth()->id())
                                      ->whereOrderId($this->order->id)
                                      ->latest()
                                      ->first();
        $hasBeenSentBefore = ! is_null($previousNote) && ($previousNote->message == $this->note);
        if ($hasBeenSentBefore) {
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
