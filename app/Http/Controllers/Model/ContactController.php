<?php

namespace App\Http\Controllers\Model;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\ContactCreateRequest;
use App\Http\Requests\Contact\ContactUpdateRequest;
use App\Http\Resources\Model\Collection\ContactResourceCollection;
use App\Http\Resources\Model\ContactResource;

use App\Models\Contact;
use App\Models\User;

use Illuminate\Contracts\Database\Eloquent\Builder;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{

    private function getContact(User $user, int $idContact): Contact
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
        $contact = $this->getContact($user, $id);

        return new ContactResource($contact);
    }

    public function update(int $id, ContactUpdateRequest $contactUpdateRequest): ContactResource
    {
        $user = Auth::user();

        $contact = $this->getContact($user, $id);

        $data = $contactUpdateRequest->validated();
        $contact->fill($data);
        $contact->save();

        return new ContactResource($contact);
    }

    public function delete(int $id): JsonResponse
    {
        $user = Auth::user();

        $contact = $this->getContact($user, $id);

        $contact->delete();
        return response()->json([
            "data" => true,
        ])->setStatusCode(200);
    }

    public function search(Request $request): ContactResourceCollection
    {
        $user = Auth::user();
        $page = $request->input('page', 1);
        $pageSize = $request->input('size', 10);

        $contacts = Contact::query()->where('user_id', '=', $user->id);

        $contacts = $contacts->where(column: function (Builder $builder) use ($request) {
            $name = $request->input('name');
            if ($name) {
                $builder->where(function (Builder $builder) use ($name) {
                    $builder->orWhere('first_name', 'like', '%' . $name . '%');
                    $builder->orWhere('last_name', 'like', '%' . $name . '%');
                });
            }

            $email = $request->input('email');
            if ($email) {
                $builder->where('email', 'like', '%' . $email . '%');
            }

            $phone = $request->input('phone');
            if ($phone) {
                $builder->where('phone', 'like', '%' . $phone . '%');
            }
        });


        $contacts = $contacts->paginate(perPage: $pageSize, page: $page);

        return new ContactResourceCollection($contacts);
    }

}
