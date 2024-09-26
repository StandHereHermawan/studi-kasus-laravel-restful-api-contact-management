<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactCreateRequest;
use App\Http\Requests\Contact\ContactUpdateRequest;
use App\Http\Resources\Model\ContactResource;
use App\Models\Contact;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{

    public function create(ContactCreateRequest $contactCreateRequest): JsonResponse
    {
        $data = $contactCreateRequest->validated();
        $user = Auth::user();

        $contact = new Contact($data);
        $contact->user_id = $user->id;
        $contact->save();

        return (new ContactResource($contact))->response()->setStatusCode(201);
    }

    public function get(int $id): ContactResource
    {
        $user = Auth::user();
        $contact = Contact::where('id', '=', $id)->where('user_id', '=', $user->id)->first();

        if (!$contact) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "Contact not found."
                    ],
                ],
            ])->setStatusCode(404));
        }

        return new ContactResource($contact);
    }

    public function update(int $id, ContactUpdateRequest $contactUpdateRequest): ContactResource
    {
        $user = Auth::user();

        $contact = Contact::where('id', '=', $id)->where('user_id', '=', $user->id)->first();

        if (!$contact) {
            throw new HttpResponseException(response()->json(
                [
                    "errors" => [
                        "message" => [
                            "Contact Not Found."
                        ],
                    ],
                ]
            )->setStatusCode(404));
        }

        $data = $contactUpdateRequest->validated();
        $contact->fill($data);
        $contact->save();

        return new ContactResource($contact);
    }

    public function delete(int $id): JsonResponse
    {
        $user = Auth::user();

        $contact = Contact::where('id', '=', $id)->where('user_id', '=', $user->id)->first();

        if (!$contact) {
            throw new HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "Contact not found."
                    ],
                ],
            ])->setStatusCode(404));
        }

        $contact->delete();
        return response()->json([
            "data" => true,
        ])->setStatusCode(200);
    }
}
