<?php

namespace App\Http\Controllers\Addresses;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use App\Models\Client;
use App\Models\Plan;

class AddressController extends Controller
{
    public function show(Address $address)
    {
        $address->load(['plan', 'devices', 'client']);

        $used  = $address->devices->count();
        $limit = $address->plan?->device_limit ?? 0;

        return view('addresses.show', compact('address', 'used', 'limit'));
    }

    public function create(Client $client)
    {
        $plans = Plan::orderBy('name')->get();

        return view('addresses.create', compact('client', 'plans'));
    }

    public function store(StoreAddressRequest $request, Client $client)
    {
        $client->addresses()->create($request->validated());

        return redirect()->route('clients.show', $client)
            ->with('success', 'Property added successfully.');
    }

    public function edit(Address $address)
    {
        $plans = Plan::orderBy('name')->get();

        return view('addresses.edit', compact('address', 'plans'));
    }

    public function update(UpdateAddressRequest $request, Address $address)
    {
        $address->update($request->validated());

        return redirect()->route('clients.show', ['client' => $address->client_id])
            ->with('success', 'Property updated successfully.');
    }

    public function destroy(Address $address)
    {
        $clientId = $address->client_id;
        $address->delete();

        return redirect()->route('clients.show', ['client' => $clientId])
            ->with('success', 'Property deleted successfully.');
    }
}
