<?php

namespace App\Http\Controllers\Api;

use App\Traits\LoggedInUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TechnicalSupportTicket;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Admin\TechnicalSupportResource;

class TechnicalSupportController extends Controller
{
    
    use LoggedInUser;
  

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

      $user = $this->loggedInUser()['user'];
      return response()->json([
        'tickets' => TechnicalSupportResource::collection($user->tickets)
      ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  TechnicalSupportTicket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(TechnicalSupportTicket $ticket)
    {
      $user = $this->loggedInUser();
      if ($ticket->ticketable->id !== $user['user']->id || !strpos(strtolower($ticket->ticketable_type), $user['type'])) {
        return abort(404);
      }

      return response()->json([
        'messages' => $ticket->chats()->orderBy('id', 'DESC')->take(20)->get()->reverse()->values(),
        'title'    => $ticket->title,
      ]);
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  TechnicalSupportTicket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TechnicalSupportTicket $ticket)
    {
        $user = $this->loggedInUser();
        if ($ticket->ticketable->id !== $user['user']->id || !strpos(strtolower($ticket->ticketable_type), $user['type'])) {
          return abort(404);
        }

        // Validate
        $validator = Validator::make($request->all(), [
          'body'  => 'sometimes|nullable',
          'image' => 'sometimes|nullable',
        ]);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        // Upload attachment
        $attachment = null;
        if (!empty($request->image)) {
          $imageName = time().'.'.$request->image->extension();
          $request->image->move(public_path("storage/support/{$ticket->id}/attachments"), $imageName);
          $attachment = asset("storage/support/{$ticket->id}/attachments/{$imageName}");
        }


        if ($request->body || $attachment) {
          // Ticket message
          $ticket->chats()->create([
            'message'    => $request->body,
            'attachment' => $attachment,
            'sender'     => 2,
          ]);
          $ticket->touch();
          $ticket->status = 1;
          $ticket->save();
        }

        return response()->json([
          'message' => __('messages.successful_create'),
        ], 200);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      // Validate
      $validator = Validator::make($request->all(), [
        'title'       => 'required',
        'description' => 'required',
        'image'       => 'sometimes|nullable',
      ]);

      if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
      }

      // Submit ticket
      $user = $this->loggedInUser()['user'];
      $ticket = $user->tickets()->create($request->all());

      // Upload attachment
      $attachment = null;
      if (!empty($request->image)) {
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path("storage/support/{$ticket->id}/attachments"), $imageName);
        $attachment = asset("storage/support/{$ticket->id}/attachments/{$imageName}");
      }

      // Ticket's first message
      $ticket->chats()->create([
        'message'    => $request->description,
        'attachment' => $attachment,
        'sender'     => 2,
      ]);

        return response()->json([
          'message' => __('messages.successful_create'),
        ], 200);

    }
}
