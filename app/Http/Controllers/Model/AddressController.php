<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;

use App\Http\Requests\Address\AddressCreateRequest;
use App\Http\Requests\Address\AddressUpdateRequest;

use App\Http\Resources\Model\AddressResource;
use App\Http\Resources\Model\ContactResource;

use App\Models\Address;
use App\Models\Contact;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{

    # Local function that would be used in Controller function.
    private function getContact(\App\Models\User $user, int $idContact): Contact
    {
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
        return $contact;
    }

    private function getAddress(Contact $contact, int $idAddress): Address
    {
        $address = Address::where('contact_id', '=', $contact->id)->where('id', '=', $idAddress)->first();
        if (!$address) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
                "errors" => [
                    "message" => [
                        "Address not found."
                    ],
                ],
            ])->setStatusCode(404));
        }
        return $address;
    }

    # Controller function that would be used in routing.
    public function create(int $idContact, AddressCreateRequest $addressCreateRequest): JsonResponse
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idContact);

        $data = $addressCreateRequest->validated();
        $address = new Address($data);
        $address->contact_id = $contact->id;
        $address->save();

        return (new AddressResource($address))->response()->setStatusCode(201);
    }

    public function get(int $idContact, int $idAddress): AddressResource
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idContact);
        $address = $this->getAddress($contact, $idAddress);

        return new AddressResource($address);
    }

    public function update(int $idContact, int $idAddress, AddressUpdateRequest $addressUpdateRequest): AddressResource
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idContact);
        $address = $this->getAddress($contact, $idAddress);

        $data = $addressUpdateRequest->validated();
        $address->fill($data);
        $address->save();

        return new AddressResource($address);
    }

    public function delete(int $idContact, int $idAddress): JsonResponse
    {
        $user = Auth::user();
        $contact = $this->getContact($user, $idContact);
        $address = $this->getAddress($contact, $idAddress);
        $address->delete();

        return response()->json([
            "data" => true,
        ])->setStatusCode(200);
    }
}
