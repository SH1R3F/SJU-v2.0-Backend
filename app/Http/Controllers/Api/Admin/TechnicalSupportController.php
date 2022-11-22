<?php

namespace App\Http\Controllers\Api\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TechnicalSupportTicket;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\TechnicalSupportResource;

class TechnicalSupportController extends Controller
{
      
    public function __construct()
    {
        // HONESTLY... idk how to manage this.
        // $this->middleware('permission:manage-membersupport');
        // $this->middleware('permission:manage-subscribersupport');
        // $this->middleware('permission:manage-volunteersupport');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tickets = TechnicalSupportTicket::with(['ticketable', 'chats'])->filter($request)->orderBy('status', 'DESC')->orderBy('updated_at', 'DESC')->take(40)->get();
        return response()->json([
          // 'total'   => TechnicalSupportTicket::filter($request)->get()->count(),
          'tickets' => TechnicalSupportResource::collection($tickets),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  TechnicalSupportTicket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function message(Request $request, TechnicalSupportTicket $ticket)
    {
        $validator = Validator::make($request->all(), [
          'senderId' => 'required|in:1,2'
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }
        
        // Store attachment
        if ($request->attachment) {
          $base64Image  = explode(";base64,", $request->attachment);
          $explodeImage = explode("image/", $base64Image[0]);
          $imageType    = $explodeImage[1];
          $image_base64 = base64_decode($base64Image[1]);
          $imageName    = uniqid() . '.'.$imageType;
          Storage::disk('public')->put("support/{$ticket->id}/attachments/{$imageName}", $image_base64);
          $request->merge(['attachment' => asset("storage/support/{$ticket->id}/attachments/{$imageName}")]);
        }

        $message = $ticket->chats()->create([
          'message'    => $request->message,
          'attachment' => $request->attachment,
          'sender'     => $request->senderId
        ]);

        $ticket->touch();
        $ticket->update(['status' => 1]);

        return response()->json([
          'message' => $message
        ]);
    }

    /**
     * Display the specified resource messages.
     *
     * @param  TechnicalSupportTicket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function chat(TechnicalSupportTicket $ticket)
    {
        return response()->json([
          'ticket' => new TechnicalSupportResource($ticket)
        ]);
    }

    
    /**
     * Toggle the the status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  TechnicalSupportTicket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function toggle(Request $request, TechnicalSupportTicket $ticket)
    {
        $newStatus = !$ticket->status;
        $ticket->update([
          'status' => $newStatus
        ]);

        return response()->json([
          'message' => __('messages.successful_update'),
          'status'  => $newStatus
        ], 200);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  TechnicalSupportTicket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(TechnicalSupportTicket $ticket)
    {
        $ticket->chats()->delete();
        $ticket->delete();
        return response()->json([
          'message' => __('messages.successful_delete'),
        ], 200);

    }
}
