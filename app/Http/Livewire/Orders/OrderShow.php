<?php

namespace App\Http\Livewire\Orders;

use App\Models\Order;
use App\Models\OrderAgentNote;
use Livewire\Component;

class OrderShow extends Component
{
    public Order $order;
    public $note;
    public $newGrandTotal;
    public $newGrandTotalNote;
    public $isGrantTotalFormShown = false;
    public $agentNotes;

    protected $rules = [
        'order.status' => 'required|numeric',
        'note' => 'required|min:3|max:255',
        'agentNotes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->agentNotes = $this->order->user->agent_notes;
    }

    public function updatedOrderStatus($newValue)
    {
        $this->order->status = $newValue;
        $this->order->save();
        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Status updated successfully',
        ]);
    }

    public function updatedAgentNotes($newValue)
    {
        $this->order->user->agent_notes = $newValue;
        $this->order->user->save();

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'User Attached note updated successfully',
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
            $this->createNote($this->note);

            $this->note = null;
            $this->emit('showToast', [
                'icon' => 'success',
                'message' => 'Note saved successfully',
            ]);
        }

    }

    public function addNewGrandTotal()
    {
        $this->validate([
            'newGrandTotal' => 'required_with:newGrandTotalNote|numeric',
            'newGrandTotalNote' => 'required_with:newGrandTotal|string',
        ]);
        \DB::beginTransaction();
        $this->order->grand_total_before_agent_manipulation = $this->order->grand_total;
        $this->order->grand_total = $this->newGrandTotal;
        $this->order->save();
        $this->createNote($this->newGrandTotalNote);
        \DB::commit();

        $this->isGrantTotalFormShown = false;
        $this->newGrandTotal = null;
        $this->newGrandTotalNote = null;

        $this->emit('showToast', [
            'icon' => 'success',
            'message' => 'Grand total updated successfully',
        ]);

    }


    public function toggleGrandTotalForm()
    {
        $this->isGrantTotalFormShown = ! $this->isGrantTotalFormShown;
    }

    private function createNote($message): void
    {
        $orderAgentNote = new OrderAgentNote();
        $orderAgentNote->message = $message;
        $orderAgentNote->order_id = $this->order->id;
        $orderAgentNote->agent_id = auth()->id();
        $orderAgentNote->save();
    }
}
