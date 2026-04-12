@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <div class="container">
        <h2 class="section-title">My Cart</h2>

        @if($cart->storeFront)
            <div class="card">
                <p><strong>Cart Shop:</strong> {{ $cart->storeFront->name }} - {{ $cart->storeFront->branch_name }}</p>
            </div>
        @endif

        @php $total = 0; @endphp

        <div class="list-block">
            @forelse($cartItems as $cartItem)
                @php
                    $subtotal = $cartItem->item->price * $cartItem->quantity;
                    $total += $subtotal;
                @endphp

                <div class="card">
                    <h3>{{ $cartItem->item->item_name }}</h3>
                    <p><strong>Price:</strong> {{ $cartItem->item->price }}</p>
                    <p><strong>Quantity:</strong> {{ $cartItem->quantity }}</p>
                    <p><strong>Subtotal:</strong> {{ $subtotal }}</p>

                    <div class="actions">
                        <form method="POST" action="{{ route('customer.cart.remove', $cartItem) }}" class="inline-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card">
                    <p>Your cart is empty.</p>
                </div>
            @endforelse
        </div>

        @if($cartItems->count() > 0)
            <div class="card">
                <h3>Total: {{ $total }}</h3>

                <form method="POST" action="{{ route('customer.cart.checkout') }}" style="margin-top: 20px;">
                    @csrf
                    
                    <div style="margin-bottom: 15px;">
                        <p><strong>Order Type:</strong></p>
                        <label style="margin-right: 15px; cursor: pointer;">
                            <input type="radio" name="order_type" value="takeaway" id="type_takeaway" checked onchange="toggleDelivery()"> 
                            Takeaway (Self Pickup)
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="order_type" value="delivery" id="type_delivery" onchange="toggleDelivery()"> 
                            Delivery
                        </label>
                    </div>

                    <div id="delivery_details" style="display: none; margin-top: 15px; padding: 15px; border: 1px solid #ddd; background-color: #f9f9f9; border-radius: 5px;">
                        <h4 style="margin-top: 0; margin-bottom: 15px;">Delivery Information</h4>

                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Phone Number:</label>
                            <input type="text" name="delivery_phone" value="{{ auth()->user()->phone }}" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;" placeholder="e.g. 017XXXXXXXX">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">Address (Building/Room):</label>
                            <input type="text" name="delivery_address" id="delivery_address_input" value="{{ auth()->user()->default_delivery_address }}" style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;" placeholder="e.g. BRACU UB2, 5th Floor">
                        </div>

                        <input type="hidden" name="delivery_lat" id="delivery_lat" value="{{ auth()->user()->default_delivery_lat ?? '23.7744' }}">
                        <input type="hidden" name="delivery_lng" id="delivery_lng" value="{{ auth()->user()->default_delivery_lng ?? '90.4048' }}">

                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 8px;">
                                <label style="font-weight: bold; margin: 0;">Pin Location on Map:</label>
                                <button type="button" onclick="getCurrentLocation(this)" style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; font-weight: bold;">
                                    📍 Use My Current Location
                                </button>
                            </div>
                            
                            <div id="map" style="height: 300px; width: 100%; border: 1px solid #ccc; border-radius: 4px; z-index: 1;"></div>
                            <small style="color: #666; margin-top: 5px; display: block;">You can use the magnifying glass to search, or drag the blue pin to your exact location.</small>
                        </div>
                    </div>

                    <div class="actions" style="margin-top: 20px;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-size: 1.1rem;">Place Order</button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <script>
        let map, marker;

        function toggleDelivery() {
            const isDelivery = document.getElementById('type_delivery').checked;
            const detailsDiv = document.getElementById('delivery_details');
            
            if (isDelivery) {
                detailsDiv.style.display = 'block';
                
                if (!map) {
                    const lat = parseFloat(document.getElementById('delivery_lat').value);
                    const lng = parseFloat(document.getElementById('delivery_lng').value);
                    
                    map = L.map('map').setView([lat, lng], 15);
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    marker = L.marker([lat, lng], {draggable: true}).addTo(map);

                    const geocoder = L.Control.geocoder({
                        defaultMarkGeocode: false,
                        placeholder: "Search for a location..."
                    })
                    .on('markgeocode', function(e) {
                        const center = e.geocode.center;
                        map.fitBounds(e.geocode.bbox);
                        marker.setLatLng(center);
                        
                        document.getElementById('delivery_lat').value = center.lat;
                        document.getElementById('delivery_lng').value = center.lng;

                        document.getElementById('delivery_address_input').value = e.geocode.name;
                    })
                    .addTo(map);

                    marker.on('dragend', function (e) {
                        document.getElementById('delivery_lat').value = marker.getLatLng().lat;
                        document.getElementById('delivery_lng').value = marker.getLatLng().lng;
                    });

                    map.on('click', function(e) {
                        marker.setLatLng(e.latlng);
                        document.getElementById('delivery_lat').value = e.latlng.lat;
                        document.getElementById('delivery_lng').value = e.latlng.lng;
                    });
                    
                    setTimeout(function(){ map.invalidateSize(); }, 100);
                }
            } else {
                detailsDiv.style.display = 'none';
            }
        }

        function getCurrentLocation(btn) {
            if (navigator.geolocation) {
                const originalText = btn.innerHTML;
                btn.innerHTML = "⏳ Locating...";
                btn.style.opacity = "0.7";

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        map.setView([lat, lng], 18);
                        marker.setLatLng([lat, lng]);
                        
                        document.getElementById('delivery_lat').value = lat;
                        document.getElementById('delivery_lng').value = lng;
                        
                        btn.innerHTML = "✅ Location Found!";
                        btn.style.background = "#198754";
                        
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.style.background = "#28a745";
                            btn.style.opacity = "1";
                        }, 3000);
                    }, 
                    function(error) {
                        btn.innerHTML = originalText;
                        btn.style.opacity = "1";
                        if (error.code == error.PERMISSION_DENIED) {
                            alert("Location access was denied. Please allow location permissions in your browser settings.");
                        } else {
                            alert("Could not detect your current location. Please check your device settings.");
                        }
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                alert("Geolocation is not supported by your browser.");
            }
        }
    </script>
@endsection