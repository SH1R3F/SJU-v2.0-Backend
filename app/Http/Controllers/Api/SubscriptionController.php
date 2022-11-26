<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{

    /**
     * The page to be redirected to from the payment gateway.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function response(Request $request)
    {
        // If we don't get back the ID
        if (!$request->id) {
            return 'This page requires and order ref to load. If you think it happened by a mistake contact us with reproduction steps.';
        }

        $order_ref = $request->id;
        $invoice = Invoice::where('order_ref', $order_ref)->first();

        // If we don't get back the ID
        if (!$invoice) {
            return 'No invoice with this order ref! If you think it happened by a mistake contact us with reproduction steps.';
        }

        // This method shouldn't execute if member is not approved and active.
        // And also when member subscription is active and not ended yet!
        if ($invoice->member->active !== 1 || $invoice->member->approved !== 1 || ($invoice->subscription->status === 1 && Carbon::now()->lt($invoice->subscription->end_date) )) {
          return response()->json([
            'message' => 'What are you doing here? You should be approved, active, and your subscription isnt ended yet!'
          ], 403);
        }

        // Verify payment status !
        $result = $this->verify($invoice);

        $invoice->update([
          'invoice_number'    => $invoice->invoice_number ? $invoice->invoice_number : $this->invoice_number(),
          'subscription_data' => $invoice->subscription->toArray(),
          'order_data'        => $result,
        ]);

        // Successful payment
        if (preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/', $result->result->code) == 1) {

          // Update subscription
          $invoice->subscription->update([
            'start_date' => Carbon::now(),
            'end_date'   => Carbon::now()->endOfYear(),
            'status'     => 1
          ]);

          // Update Invoice
          $invoice->update([
            'amount'            => $result->amount,
            'member_data'       => $result->customer,
            'subscription_data' => $invoice->subscription->toArray(),
            'order_data'        => $result,
            'status'            => 1
          ]);

          // Give the member membership number if he hasn't one already
          $invoice->member->update([
            'membership_number' => $invoice->member->membership_number? $invoice->member->membership_number : $this->membership_number($invoice->subscription->type)
          ]);

          // Redirect to frontend membership to celebrate !
          return redirect()->intended(
            config('app.frontend_url') . '/members/dashboard/membership?payment=success'
          );
          
        }
        
        // Payment is pending!
        else if (preg_match('/^(000\.400\.0[^3]|000\.400\.100)/', $result->result->code) == 1) {

          // Redirect to frontend membership with Pending message !
          return redirect()->intended(
            config('app.frontend_url') . '/members/dashboard/membership?payment=pending'
          );

        }
        
        // Payment refused !
        else {
          
          // Redirect to frontend membership with refused message !
          return redirect()->intended(
            config('app.frontend_url') . '/members/dashboard/membership?payment=refused'
          );

        }

      }
    
    /**
     * Performing the previous payment process.
     *   Preparing the payment gateway 
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $type
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request, $type)
    {
      
      $member   = Auth::guard('api-members')->user();

      // This method shouldn't execute if member is not approved and active.
      // And also when member subscription is active and not ended yet!
      if ($member->active !== 1 || $member->approved !== 1 || ($member->subscription->status === 1 && Carbon::now()->lt($member->subscription->end_date) )) {
        return response()->json([
          'message' => 'What are you doing here? You should be approved, active, and your subscription isnt ended yet!'
        ], 403);
      }

      $cart_ref = 'SJU-' . time();
      $paytype  = ($type == 2)? 'MADA' : 'VISA_MASTER';

      switch ($member->subscription->type) {
        case 1:
          $price = 250;
          $type_ar = 'متفرغ';
          break;
        case 2:
          $price = 200;
          $type_ar = 'غير متفرغ';
          break;
        case 3:
          $price = 150;
          $type_ar = 'منتسب';
          break;
      }

      $price = ($member->branch === 8 && $member->delivery_method === 2) ? $price + 30 : $price;
      $price = number_format($price, 2);
      
      // Requesting the payment gateway
      $response   = $this->gateway($cart_ref, $member, $price, $paytype, $type_ar);
      $gatewayReq = json_decode($response);
      
      
      if (!empty(trim($gatewayReq->result->code)) && $gatewayReq->result->code == '000.200.100' && $gatewayReq->id) { // Success response

        // Create a new Invoice
        $member->invoices()->create([
          'cart_ref'        => $cart_ref,
          'subscription_id' => $member->subscription->id,
          'order_ref'       => $gatewayReq->id,
          'payment_method'  => $type,
          'status'          => 0,
        ]);

        return response()->json([
          'id' => $gatewayReq->id
        ], 200); // Success response
      }

      // Otherwise.. Return an error status on our end!
      return response()->json([
        'message' => 'messages.error_on_our_side'
      ], 422);

    }

    /**
     * Preparing the payment gateway 
     *
     * @param  \String  $cart_ref
     * @param  Member  $member
     * @return \Illuminate\Http\Response
     */
    private function gateway($cart_ref, $member, $price, $paytype, $type_ar)
    {
        // Live codes
        // $mada = env('PAYMENT_TOKEN_MADA');
        // $visa = env('PAYMENT_TOKEN_VISA');
        // $url  = env('PAYMENT_URL');
        // $auth = env('PAYMENT_AUTH_TOKEN');

        // Test codes
        $mada = env('PAYMENT_TOKEN_MADA_TEST');
        $visa = env('PAYMENT_TOKEN_VISA_TEST');
        $url  = env('PAYMENT_URL_TEST');
        $auth = env('PAYMENT_AUTH_TOKEN_TEST');

        $select_paytype = ($paytype == 'MADA') ? $mada : $visa;
        $data = "entityId=" . $select_paytype.
            "&testMode=EXTERNAL".
            "&amount=" . $price.
            "&currency=SAR" .
            "&paymentType=DB" .
            "&merchantTransactionId=".$cart_ref.
            "&billing.street1=Jubail".
            "&billing.city=Jubail".
            "&billing.state=ES".
            "&billing.country=SA".
            "&billing.postcode=31961".
            "&customer.givenName=" . $member->fname_ar .
            "&customer.surname=". $member->lname_ar .
            "&customer.mobile=". $member->mobile .
            "&customer.email=". $member->email .
            "&customParameters[SHOPPER_productname]=" . $member->national_id . '-' .  $type_ar;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . $auth
        ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);

        return $response;
    }


    /**
     * Verifying the payment status of an existing invoice
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    private function verify(Invoice $invoice)
    {
      
      // Live codes
      // $mada = env('PAYMENT_TOKEN_MADA');
      // $visa = env('PAYMENT_TOKEN_VISA');
      // $url  = env('PAYMENT_URL');
      // $auth = env('PAYMENT_AUTH_TOKEN');
   
      // Test codes
      $mada = env('PAYMENT_TOKEN_MADA_TEST');
      $visa = env('PAYMENT_TOKEN_VISA_TEST');
      $url  = env('PAYMENT_URL_TEST');
      $auth = env('PAYMENT_AUTH_TOKEN_TEST');


      $select_paytype = ($invoice->payment_method == 2) ? $mada : $visa;
      $url = $urlb . $invoice->order_ref . "/payment";
      $url .= "?entityId=".$select_paytype;
  
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Authorization:Bearer ' . $auth
      ));
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);
      if(curl_errno($ch)) {
          return curl_error($ch);
      }
      curl_close($ch);
      
      return json_decode($response);

    }  

    /**
     * Generate an invoice number depending on the last invoice number we have
     *
     * @return \Illuminate\Http\Response
     * @return \String 
     */
    private function invoice_number()
    {
        // 0000100
        $invoice = Invoice::orderBy('invoice_number', 'DESC')->first();

        if (!$invoice || !$invoice->invoice_number) {
          $invoice_number = '0000001';

        } else {
          $number = intval($invoice->invoice_number);
          $invoice_number = str_pad($number + 1, 7, '0', STR_PAD_LEFT);
        }

        return $invoice_number;
    }


    /**
     * Generate a membership number for a specific membership type
     *
     * @param  \Int  $type
     * @return \String 
     */
    private function membership_number($type)
    {

      // For parttime members reserve 20 membership, other types reserve only 10
      $reserve = $type === 2 ? 20 : 10;
      $last = Member::where('membership_number', 'LIKE', "$type-%")->orderBy('membership_number', 'DESC')->first();

      if ($last && $last->membership_number) {
        // If we have a last value. increase on on it
        $num = explode('-', $last->membership_number)[1];
        $number = intval($num);
        $membership_number = $number + 1;

      } else { // Congratulations! You're the first !
        // Increase one on our reserve numbers
        $membership_number = $reserve + 1;
      }
      // x-0001
      return "$type-" . str_pad($membership_number, 4, '0', STR_PAD_LEFT);

    }


}
