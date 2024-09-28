<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\AddressCreateRequest;
use App\Http\Resources\Model\AddressResource;
use App\Http\Resources\Model\ContactResource;
use App\Models\Address;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{

    public function create(int $idContact, AddressCreateRequest $addressCreateRequest): JsonResponse
    {
        $user = Auth::user();

        $contact = Contact::where('user_id', '=', $user->id)->where('id', '=', $idContact)->first();
        if (!$contact) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "Contact not found."
                    ],
                ],
            ])->setStatusCode(404));
        }

        $data = $addressCreateRequest->validated();
        $address = new Address($data);
        $address->contact_id = $contact->id;
        $address->save();

        return (new AddressResource($address))->response()->setStatusCode(201);
    }

}
