@extends('backend.layouts.app')

@push('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
@endpush

@section('content')
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/generall.png') }}'); background-size: cover; background-position: center;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('dashboard.location') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{ __('dashboard.add') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('dashboard.location') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('dashboard.add_location') }}</h3>
                        </div>
                        <form class="form-horizontal" method="post" action="{{ route('locations.store') }}">
                            @csrf
                            <div class="card-body">

                                <!-- Location Name -->
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">
                                        {{ __('dashboard.name') }} 
                                        <span style="color: red;">*</span>
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" value="{{ old('name') }}" name="name"
                                            class="form-control" required
                                            placeholder="{{ __('dashboard.enter_name') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Map -->
                                <div id="map" style="height: 500px;"></div>
                                <input type="hidden" name="polygon" id="polygonInput" value="{{ old('polygon') }}">
                                @error('polygon')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="card-footer">
                                <a href="{{ route('locations.index') }}" class="btn btn-default float-left">{{ __('dashboard.back') }}</a>
                                <button type="submit" class="btn btn-primary float-right">{{ __('dashboard.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>

<script>
    // Init map
    var map = L.map('map').setView([30.0444, 31.2357], 12); // Cairo as default

    // Add OSM layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Drawn layer group
    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    // Draw control
    var drawControl = new L.Control.Draw({
        edit: { featureGroup: drawnItems },
        draw: { marker: false, circle: false, rectangle: false, polyline: false }
    });
    map.addControl(drawControl);

    // Save polygon
    function savePolygon(layer) {
        var coords = layer.getLatLngs()[0].map(p => ({ lat: p.lat, lng: p.lng }));
        document.getElementById('polygonInput').value = JSON.stringify(coords);
    }

    // When polygon created
    map.on(L.Draw.Event.CREATED, function (event) {
        drawnItems.clearLayers();
        var layer = event.layer;
        drawnItems.addLayer(layer);
        savePolygon(layer);
    });

    // When polygon edited
    map.on(L.Draw.Event.EDITED, function (event) {
        event.layers.eachLayer(function (layer) {
            savePolygon(layer);
        });
    });

    // Restore old polygon if exists
    var oldPolygon = @json(old('polygon'));
    if (oldPolygon) {
        try {
            var coords = JSON.parse(oldPolygon);
            if (coords.length > 0) {
                var latlngs = coords.map(p => [p.lat, p.lng]);
                var polygon = L.polygon(latlngs, { color: 'blue' }).addTo(drawnItems);
                map.fitBounds(polygon.getBounds());
            }
        } catch (e) {
            console.error("Invalid old polygon:", e);
        }
    }
</script>
@endsection
