<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Http\Resources\MessageResource;
use App\Http\Requests\SaveMessageRequest;
use Illuminate\Http\JsonResponse;


class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()  : JsonResponse
        {
        $messages = Message::with(['user'])->orderByDesc('created_at')->paginate(20);

        return $this->successResponse('Messages successfully fetched.', MessageResource::collection($messages));
        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(SaveMessageRequest $request)  : JsonResponse
    {
        $message = auth()->user()->messages()->create($request->validated());

        return $this->successResponse('Message successfully created.', MessageResource::make($message));
        // return $this->json->successResponse('Message successfully created.', new MessageResource(
        //     Message::create($request->validated()))
        // );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id) : JsonResponse
    {
    	$message = Message::find($id);

        if ($message->is_new && $message->user_id == auth()->user()->id) {
            $message->delete();
        }

        return $this>successResponse('Message successfully deleted.');
    }
}
