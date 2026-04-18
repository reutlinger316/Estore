@extends('layouts.app')

@section('content')
    <div class="container">

        <h2 class="section-title">My StoreFronts</h2>

        <form method="GET" action="{{ route('merchant.storefronts.index') }}" style="margin-bottom:20px; display:flex; gap:10px;">
            <input
                type="text"
                name="search"
                placeholder="Search by store, branch or location"
                value="{{ $search ?? '' }}"
                style="padding:8px; width:260px;"
            >

            <button type="submit" class="btn">Search</button>

            @if(!empty($search))
                <a href="{{ route('merchant.storefronts.index') }}">
                    <button type="button">Clear</button>
                </a>
            @endif
        </form>

        <div class="actions">
            <a href="{{ route('merchant.storefronts.create') }}" class="btn btn-primary">Create StoreFront</a>
        </div>

        <div class="list-block">
            @forelse($storeFronts as $storeFront)
                <div class="card">
                    <h3>{{ $storeFront->name }}</h3>
                    <p><strong>Branch:</strong> {{ $storeFront->branch_name }}</p>
                    <p><strong>Location:</strong> {{ $storeFront->location }}</p>
                    <p><strong>Delivery City:</strong> {{ $storeFront->delivery_city }}</p>
                    <p><strong>Inside {{ $storeFront->delivery_city }} Fee:</strong> {{ $storeFront->inside_delivery_fee }}</p>
                    <p><strong>Outside {{ $storeFront->delivery_city }} Fee:</strong> {{ $storeFront->outside_delivery_fee }}</p>
                    <p><strong>Combo Setting:</strong> {{ $storeFront->allow_combos ? 'Enabled' : 'Disabled' }}</p>
                    <p><strong>Balance:</strong> {{ $storeFront->balance }}</p>
                    <p><strong>Status:</strong> {{ $storeFront->status ? 'Active' : 'Inactive' }}</p>
                    <p><strong>Assigned StoreFront Account:</strong> {{ $storeFront->storeAccount?->name ?? 'Not assigned' }}</p>
                    <p><strong>Confirmation:</strong> {{ ucfirst($storeFront->confirmation_status) }}</p>

                    <div class="actions">
                        <a href="{{ route('merchant.items.index', $storeFront) }}" class="btn btn-primary">
                            Manage Items
                        </a>

                        <form method="POST" action="{{ route('merchant.storefronts.toggle-combos', $storeFront) }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn">
                                {{ $storeFront->allow_combos ? 'Disable Combos' : 'Enable Combos' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('merchant.storefronts.transfer-balance', $storeFront) }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Transfer this branch balance to merchant balance?')">
                                Transfer Branch Balance
                            </button>
                        </form>

                        <form method="POST" action="{{ route('merchant.storefronts.destroy', $storeFront) }}" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this storefront?')">
                                Delete StoreFront
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card">
                    <p>No storefronts created yet.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection