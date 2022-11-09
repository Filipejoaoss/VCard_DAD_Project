<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'vcard' => new VCardResource($this->vcard_object),
            'date' => $this->date,
            'datetime' => $this->datetime,
            'type' => $this->type,
            'value' => $this->value,
            'old_balance' => $this->old_balance,
            'new_balance' => $this->new_balance,
            'payment_type' => $this->payment_type_object,
            'payment_reference' => $this->payment_reference,
            'pair_transaction' => $this->pair_transaction,
            'pair_vcard' => $this->pair_vcard,
            'category' => $this->category,
            'description' => $this->description,
            'custom_options' => $this->custom_options,
            'custom_data' => $this->custom_data,
        ];
    }
}
