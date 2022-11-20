<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Admin\MemberResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
          'id'                => $this->id,
          'order_ref'         => $this->order_ref,
          'invoice_number'    => $this->invoice_number,
          'amount'            => $this->amount,
          'member'            => new MemberResource($this->member),
          'subscription_data' => $this->subscription_data,
          'member_id'         => $this->member_id,
          'created_at'        => $this->created_at->format('Y/m/d'),
          'payment_method'    => $this->payment_method ? config('sju.members.payment_methods')[$this->payment_method] : '',
          'status'            => config('sju.members.invoice_status')[$this->status],
        ];
    }
}
