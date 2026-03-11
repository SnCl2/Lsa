@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container">
    <h1>Edit Inspection for Work: {{ $inspection->work->name }}</h1>
    <form method="POST" action="{{ route('inspections.update', $inspection->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h2>1. General Information</h2>
        <div class="row">
            <div class="col-md-3 form-group">
                <label for="bank_branch">Bank Branch:</label>
                <input type="text" class="form-control" id="bank_branch" name="bank_branch" value="{{ $inspection->bank_branch }}" required>
            </div>
            <div class="col-md-3 form-group">
                <label for="phone_no">Phone Number:</label>
                <input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ $inspection->phone_no }}" required>
            </div>
            <div class="col-md-3 form-group">
                <label for="representative">Representative:</label>
                <input type="text" class="form-control" id="representative" name="representative" value="{{ $inspection->representative }}" required>
            </div>
            <div class="col-md-3 form-group">
                <label for="applicant_name">Applicant Name:</label>
                <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="{{ $inspection->applicant_name }}" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" value="{{ $inspection->address }}" required>
            </div>
            <div class="col-md-6 form-group">
                <label for="apartment_name">Apartment Name:</label>
                <input type="text" class="form-control" id="apartment_name" name="apartment_name" value="{{ $inspection->apartment_name }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="holding_no">Holding Number:</label>
                <input type="text" class="form-control" id="holding_no" name="holding_no" value="{{ $inspection->holding_no }}">
            </div>
            <div class="col-md-6 form-group">
                <label for="road">Road:</label>
                <input type="text" class="form-control" id="road" name="road" value="{{ $inspection->road }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="post_office">Post Office:</label>
                <input type="text" class="form-control" id="post_office" name="post_office" value="{{ $inspection->post_office }}">
            </div>
            <div class="col-md-6 form-group">
                <label for="police_station">Police Station:</label>
                <input type="text" class="form-control" id="police_station" name="police_station" value="{{ $inspection->police_station }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="pin_code">Pin Code:</label>
                <input type="text" class="form-control" id="pin_code" name="pin_code" value="{{ $inspection->pin_code }}">
            </div>
            <div class="col-md-6 form-group">
                <label for="ward">Ward:</label>
                <input type="text" class="form-control" id="ward" name="ward" value="{{ $inspection->ward }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label for="district">District:</label>
                <input type="text" class="form-control" id="district" name="district" value="{{ $inspection->district }}">
            </div>
            <div class="col-md-6 form-group">
                <label for="authority">Authority:</label>
                <input type="text" class="form-control" id="authority" name="authority" value="{{ $inspection->authority }}">
            </div>
        </div>
    </div>
    <h2>2. Boundaries and Dimensions</h2>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="boundary_flat_actual_north">Boundary Flat Actual North:</label>
            <input type="text" class="form-control" id="boundary_flat_actual_north" name="boundary_flat_actual_north" value="{{ $inspection->boundary_flat_actual_north }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="boundary_flat_actual_south">Boundary Flat Actual South:</label>
            <input type="text" class="form-control" id="boundary_flat_actual_south" name="boundary_flat_actual_south" value="{{ $inspection->boundary_flat_actual_south }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="boundary_flat_actual_east">Boundary Flat Actual East:</label>
            <input type="text" class="form-control" id="boundary_flat_actual_east" name="boundary_flat_actual_east" value="{{ $inspection->boundary_flat_actual_east }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="boundary_flat_actual_west">Boundary Flat Actual West:</label>
            <input type="text" class="form-control" id="boundary_flat_actual_west" name="boundary_flat_actual_west" value="{{ $inspection->boundary_flat_actual_west }}" maxlength="255">
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="boundary_flat_deed_north">Boundary Flat Deed North:</label>
            <input type="text" class="form-control" id="boundary_flat_deed_north" name="boundary_flat_deed_north" value="{{ $inspection->boundary_flat_deed_north }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="boundary_flat_deed_south">Boundary Flat Deed South:</label>
            <input type="text" class="form-control" id="boundary_flat_deed_south" name="boundary_flat_deed_south" value="{{ $inspection->boundary_flat_deed_south }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="boundary_flat_deed_east">Boundary Flat Deed East:</label>
            <input type="text" class="form-control" id="boundary_flat_deed_east" name="boundary_flat_deed_east" value="{{ $inspection->boundary_flat_deed_east }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="boundary_flat_deed_west">Boundary Flat Deed West:</label>
            <input type="text" class="form-control" id="boundary_flat_deed_west" name="boundary_flat_deed_west" value="{{ $inspection->boundary_flat_deed_west }}" maxlength="255">
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="boundary_building_actual_north">Boundary Building Actual North:</label>
            <input type="text" class="form-control" id="boundary_building_actual_north" name="boundary_building_actual_north" value="{{ $inspection->boundary_building_actual_north }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="boundary_building_actual_south">Boundary Building Actual South:</label>
            <input type="text" class="form-control" id="boundary_building_actual_south" name="boundary_building_actual_south" value="{{ $inspection->boundary_building_actual_south }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="boundary_building_actual_east">Boundary Building Actual East:</label>
            <input type="text" class="form-control" id="boundary_building_actual_east" name="boundary_building_actual_east" value="{{ $inspection->boundary_building_actual_east }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="boundary_building_actual_west">Boundary Building Actual West:</label>
            <input type="text" class="form-control" id="boundary_building_actual_west" name="boundary_building_actual_west" value="{{ $inspection->boundary_building_actual_west }}" maxlength="255">
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="boundary_building_deed_north">Boundary Building Deed North:</label>
            <input type="text" class="form-control" id="boundary_building_deed_north" name="boundary_building_deed_north" value="{{ $inspection->boundary_building_deed_north }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="boundary_building_deed_south">Boundary Building Deed South:</label>
            <input type="text" class="form-control" id="boundary_building_deed_south" name="boundary_building_deed_south" value="{{ $inspection->boundary_building_deed_south }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="boundary_building_deed_east">Boundary Building Deed East:</label>
            <input type="text" class="form-control" id="boundary_building_deed_east" name="boundary_building_deed_east" value="{{ $inspection->boundary_building_deed_east }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="boundary_building_deed_west">Boundary Building Deed West:</label>
            <input type="text" class="form-control" id="boundary_building_deed_west" name="boundary_building_deed_west" value="{{ $inspection->boundary_building_deed_west }}" maxlength="255">
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="dimensions_flat_actual_north">Dimensions Flat Actual North:</label>
            <input type="text" class="form-control" id="dimensions_flat_actual_north" name="dimensions_flat_actual_north" value="{{ $inspection->dimensions_flat_actual_north }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="dimensions_flat_actual_south">Dimensions Flat Actual South:</label>
            <input type="text" class="form-control" id="dimensions_flat_actual_south" name="dimensions_flat_actual_south" value="{{ $inspection->dimensions_flat_actual_south }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="dimensions_flat_actual_east">Dimensions Flat Actual East:</label>
            <input type="text" class="form-control" id="dimensions_flat_actual_east" name="dimensions_flat_actual_east" value="{{ $inspection->dimensions_flat_actual_east }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="dimensions_flat_actual_west">Dimensions Flat Actual West:</label>
            <input type="text" class="form-control" id="dimensions_flat_actual_west" name="dimensions_flat_actual_west" value="{{ $inspection->dimensions_flat_actual_west }}" maxlength="255">
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 form-group">
            <label for="dimensions_flat_deed_north">Dimensions Flat Deed North:</label>
            <input type="text" class="form-control" id="dimensions_flat_deed_north" name="dimensions_flat_deed_north" value="{{ $inspection->dimensions_flat_deed_north }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="dimensions_flat_deed_south">Dimensions Flat Deed South:</label>
            <input type="text" class="form-control" id="dimensions_flat_deed_south" name="dimensions_flat_deed_south" value="{{ $inspection->dimensions_flat_deed_south }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="dimensions_flat_deed_east">Dimensions Flat Deed East:</label>
            <input type="text" class="form-control" id="dimensions_flat_deed_east" name="dimensions_flat_deed_east" value="{{ $inspection->dimensions_flat_deed_east }}" maxlength="255">
        </div>
        <div class="col-md-3 form-group">
            <label for="dimensions_flat_deed_west">Dimensions Flat Deed West:</label>
            <input type="text" class="form-control" id="dimensions_flat_deed_west" name="dimensions_flat_deed_west" value="{{ $inspection->dimensions_flat_deed_west }}" maxlength="255">
        </div>
    </div>
</div>
<!-- updated ^^^^ -->
        <h2>3. Property Details</h2>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="property_type">Property Type:</label>
            <select id="property_type" name="property_type" class="form-control" required>
            <option value="Flat" {{ $inspection->property_type == 'Flat' ? 'selected' : '' }}>Flat</option>
            <option value="Land & Building" {{ $inspection->property_type == 'Land & Building' ? 'selected' : '' }}>Land & Building</option>
            <option value="Land" {{ $inspection->property_type == 'Land' ? 'selected' : '' }}>Land</option>
            <option value="Factory" {{ $inspection->property_type == 'Factory' ? 'selected' : '' }}>Factory</option>
            <option value="Garage" {{ $inspection->property_type == 'Garage' ? 'selected' : '' }}>Garage</option>
            <option value="Shop" {{ $inspection->property_type == 'Shop' ? 'selected' : '' }}>Shop</option>
            </select>
            </div>
            <div class="col-md-3 form-group">
            <label for="nature_property">Nature of Property:</label>
            <select id="nature_property" name="nature_property" class="form-control" required>
            <option value="Residential" {{ $inspection->nature_property == 'Residential' ? 'selected' : '' }}>Residential</option>
            <option value="Commercial" {{ $inspection->nature_property == 'Commercial' ? 'selected' : '' }}>Commercial</option>
            <option value="Both" {{ $inspection->nature_property == 'Both' ? 'selected' : '' }}>Both</option>
            </select>
            </div>
            <div class="col-md-3 form-group">
            <label for="flat_no">Flat Number:</label>
            <input type="text" id="flat_no" name="flat_no" class="form-control" value="{{ $inspection->flat_no }}" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="floor">Floor:</label>
            <input type="text" id="floor" name="floor" class="form-control" value="{{ $inspection->floor }}" maxlength="255">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="located_at_side">Located at Side:</label>
            <input type="text" id="located_at_side" name="located_at_side" class="form-control" value="{{ $inspection->located_at_side }}" maxlength="255">
            </div>
            
            
            <!---->
            <div class="col-md-3 form-group">
                <label for="block">Block:</label>
                <input type="text" id="block" name="_block" class="form-control" maxlength="255">
            </div>
            
            <div class="col-md-3 form-group">
                <label for="phase">Phase:</label>
                <input type="text" id="phase" name="phase" class="form-control" maxlength="255">
            </div>
            
            <div class="col-md-3 form-group">
                <label for="tower">Tower:</label>
                <input type="text" id="tower" name="tower" class="form-control" maxlength="255">
            </div>
            
            <!-- Hidden input to store JSON data -->
            <input type="" id="jsonData" name="block" value="{{ $inspection->block }}">
            <!---->
            
            

            <div class="col-md-3 form-group">
            <label for="lift_available">Lift Available:</label>
            <select id="lift_available" name="lift_available" class="form-control" required>
            <option value="Yes" {{ $inspection->lift_available == 'Yes' ? 'selected' : '' }}>Yes</option>
            <option value="No" {{ $inspection->lift_available == 'No' ? 'selected' : '' }}>No</option>
            </select>
            </div>
            <div class="col-md-3 form-group">
            <label for="garage_available">Garage Available:</label>
            <select id="garage_available" name="_garage_available" class="form-control" required>
            <option value="Yes" {{ $inspection->garage_available == 'Yes' ? 'selected' : '' }}>Yes</option>
            <option value="No" {{ $inspection->garage_available == 'No' ? 'selected' : '' }}>No</option>
            </select>
            </div>
            <div class="col-md-3 form-group">
            <label for="garageType">Garage Type:</label>
            <select id="garageType" name="garageType" class="form-control">
                <option value="">select</option>
                <option value="covered_garage">Covered Garage</option>
                <option value="open_garage">Open Garage</option>
                <option value="independent_garage">Independent Garage</option>
                <option value="dependent_garage">Dependent Garage</option>
                <option value="mechanical_garage">Mechanical Garage</option>
                <option value="podium_garage">Podium Garage</option>
            </select>

            </div>
            <!-- Hidden input to store JSON data -->
            <input type="" id="garageJsonData" name="garage_available" value="{{ $inspection->garage_available }}">
        </div>
        <div class="row">
            
            <!---->
            <div class="col-md-3 form-group">
                <label for="super_builtup_area">Super Built-up Area:</label>
                <input type="text" id="super_builtup_area" name="super_builtup_area" class="form-control">
            </div>
            
            <div class="col-md-3 form-group">
                <label for="builtup_area">Built-up Area:</label>
                <input type="text" id="builtup_area" name="builtup_area" class="form-control">
            </div>
            
            <div class="col-md-3 form-group">
                <label for="carpet_area">Carpet Area:</label>
                <input type="text" id="carpet_area" name="carpet_area" class="form-control">
            </div>
            <div class="col-md-3 form-group">
            <label for="measurement_of_roof">Measurement of roof:</label>
            <input type="text" id="measurement_of_roof" name="measurement_of_roof" class="form-control" maxlength="255">
            </div>
            <!-- Hidden input to store JSON format of all three fields -->
            <input type="" id="land_area" name="land_area" value="{{ $inspection->land_area }}">
            <!---->
            

            <div class="col-md-3 form-group">
            <label for="flats_per_floor">Flats per Floor:</label>
            <input type="text" id="flats_per_floor" name="flats_per_floor" class="form-control" value="{{ $inspection->flats_per_floor }}" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="dwelling_unit">Dwelling Unit:</label>
            <input type="text" id="dwelling_unit" name="dwelling_unit" class="form-control" value="{{ $inspection->dwelling_unit }}" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="number_of_floors">Number of Floors:</label>
            <input type="text" id="number_of_floors" name="number_of_floors" class="form-control" value="{{ $inspection->number_of_floors }}" maxlength="255">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="super_built_up_area">Super Built Up Area:</label>
            <input type="text" id="super_built_up_area" name="super_built_up_area" class="form-control" value="{{ $inspection->super_built_up_area }}" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="occupied_by">Occupied By:</label>
            <select id="occupied_by" name="occupied_by" class="form-control" required>
            <option value="Owner" {{ $inspection->occupied_by == 'Owner' ? 'selected' : '' }}>Owner</option>
            <option value="Tenant" {{ $inspection->occupied_by == 'Tenant' ? 'selected' : '' }}>Tenant</option>
            <option value="Vacant" {{ $inspection->occupied_by == 'Vacant' ? 'selected' : '' }}>Vacant</option>
            </select>
            </div>
            <div class="col-md-3 form-group">
            <label for="year_of_occupancy">Year of Occupancy:</label>
            <input type="text" id="year_of_occupancy" name="year_of_occupancy" class="form-control" value="{{ $inspection->year_of_occupancy }}" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="year_of_construction">Year of Construction:</label>
            <input type="text" id="year_of_construction" name="year_of_construction" class="form-control" value="{{ $inspection->year_of_construction }}" maxlength="255">
            </div>
        </div>
        <h2>4. Electrical & Plumbing Details</h2>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="light_points">Light Points:</label>
            <input type="number" id="light_points" name="light_points" class="form-control" value="{{ $inspection->light_points }}">
            </div>
            <div class="col-md-3 form-group">
            <label for="fan_points">Fan Points:</label>
            <input type="number" id="fan_points" name="fan_points" class="form-control" value="{{ $inspection->fan_points }}">
            </div>
            <div class="col-md-3 form-group">
            <label for="water_closets">Water Closets:</label>
            <input type="number" id="water_closets" name="water_closets" class="form-control" value="{{ $inspection->water_closets }}">
            </div>
            <div class="col-md-3 form-group">
            <label for="washbasins">Washbasins:</label>
            <input type="number" id="washbasins" name="washbasins" class="form-control" value="{{ $inspection->washbasins }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="bathtubs">Bathtubs:</label>
            <input type="number" id="bathtubs" name="bathtubs" class="form-control" value="{{ $inspection->bathtubs }}">
            </div>
            <div class="col-md-3 form-group">
            <label for="plug_points">Plug Points:</label>
            <input type="number" id="plug_points" name="plug_points" class="form-control" value="{{ $inspection->plug_points }}">
            </div>
            <div class="col-md-3 form-group">
            <label for="door_type">Door Type:</label>
            <select id="door_type" name="door_type" class="form-control" maxlength="255">
                <option value="Wooden Door" {{ $inspection->door_type == 'Wooden Door' ? 'selected' : '' }}>Wooden Door</option>
                <option value="Steel Door" {{ $inspection->door_type == 'Steel Door' ? 'selected' : '' }}>Steel Door</option>
                <option value="Glass Door" {{ $inspection->door_type == 'Glass Door' ? 'selected' : '' }}>Glass Door</option>
                <option value="PVC (UPVC) Door" {{ $inspection->door_type == 'PVC (UPVC) Door' ? 'selected' : '' }}>PVC (UPVC) Door</option>
                <option value="French Door" {{ $inspection->door_type == 'French Door' ? 'selected' : '' }}>French Door</option>
                <option value="Sliding Door" {{ $inspection->door_type == 'Sliding Door' ? 'selected' : '' }}>Sliding Door</option>
                <option value="Folding Door" {{ $inspection->door_type == 'Folding Door (Bi-Fold Door)' ? 'selected' : '' }}>Folding Door (Bi-Fold Door)</option>
                <option value="Hinged Door" {{ $inspection->door_type == 'Hinged Door' ? 'selected' : '' }}>Hinged Door</option>
                <option value="Others" {{ $inspection->door_type == 'Others' ? 'selected' : '' }}>Others</option>
            </select>

            </div>
            <div class="col-md-3 form-group">
            <label for="flooring_type">Flooring Type:</label>
            <select id="flooring_type" name="flooring_type" class="form-control" required>
            <option value="Mosaic" {{ $inspection->flooring_type == 'Mosaic' ? 'selected' : '' }}>Mosaic</option>
            <option value="Tiles" {{ $inspection->flooring_type == 'Tiles' ? 'selected' : '' }}>Tiles</option>
            <option value="Marble" {{ $inspection->flooring_type == 'Marble' ? 'selected' : '' }}>Marble</option>
            <option value="Cement" {{ $inspection->flooring_type == 'Cement' ? 'selected' : '' }}>Cement</option>
            </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="window_type">Window Type:</label>
            <input type="text" id="window_type" name="window_type" class="form-control" value="{{ $inspection->window_type }}" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="wiring_type">Wiring Type:</label>
            <select id="wiring_type" name="wiring_type" class="form-control" required>
            <option value="Surface" {{ $inspection->wiring_type == 'Surface' ? 'selected' : '' }}>Surface</option>
            <option value="Concealed" {{ $inspection->wiring_type == 'Concealed' ? 'selected' : '' }}>Concealed</option>
            </select>
            </div>
        </div>

        <h2>5. Location Details</h2>
        <div class="row">
            <div class="col-md-4 form-group">
            <label for="nearest_bus_stand_name">Nearest Bus Stand Name:</label>
            <input type="text" id="nearest_bus_stand_name" name="nearest_bus_stand_name" class="form-control" value="{{ $inspection->nearest_bus_stand_name }}" maxlength="255">
            </div>
            <div class="col-md-4 form-group">
            <label for="nearest_bus_stand_distance">Nearest Bus Stand Distance:</label>
            <input type="text" id="nearest_bus_stand_distance" name="nearest_bus_stand_distance" class="form-control" value="{{ $inspection->nearest_bus_stand_distance }}" maxlength="255">
            </div>
            <div class="col-md-4 form-group">
            <label for="nearest_railway_station_name">Nearest Railway Station Name:</label>
            <input type="text" id="nearest_railway_station_name" name="nearest_railway_station_name" class="form-control" value="{{ $inspection->nearest_railway_station_name }}" maxlength="255">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
            <label for="nearest_railway_station_distance">Nearest Railway Station Distance:</label>
            <input type="text" id="nearest_railway_station_distance" name="nearest_railway_station_distance" class="form-control" value="{{ $inspection->nearest_railway_station_distance }}" maxlength="255">
            </div>
            <div class="col-md-4 form-group">
            <label for="nearest_landmark_name">Nearest Landmark Name:</label>
            <input type="text" id="nearest_landmark_name" name="nearest_landmark_name" class="form-control" value="{{ $inspection->nearest_landmark_name }}" maxlength="255">
            </div>
            <div class="col-md-4 form-group">
            <label for="nearest_landmark_distance">Nearest Landmark Distance:</label>
            <input type="text" id="nearest_landmark_distance" name="nearest_landmark_distance" class="form-control" value="{{ $inspection->nearest_landmark_distance }}" maxlength="255">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
            <label for="connected_road">Connected Road:</label>
            <input type="text" id="connected_road" name="connected_road" class="form-control" value="{{ $inspection->connected_road }}" maxlength="255">
            </div>
        </div>

        <h2>6. Plot & Measurement Details</h2>
        <div class="row">
            <div class="col-md-4 form-group">
            <label for="plot_demarcated">Plot Demarcated:</label>
            <select id="plot_demarcated" name="plot_demarcated" class="form-control" required>
            <option value="Yes" {{ $inspection->plot_demarcated == 'Yes' ? 'selected' : '' }}>Yes</option>
            <option value="No" {{ $inspection->plot_demarcated == 'No' ? 'selected' : '' }}>No</option>
            </select>
            </div>
            <div class="col-md-4 form-group">
            <label for="plot_demarcated_description">Plot Demarcated Description:</label>
            <textarea id="plot_demarcated_description" name="plot_demarcated_description" class="form-control">{{ $inspection->plot_demarcated_description }}</textarea>
            </div>
            <div class="col-md-4 form-group">
            <label for="wall_height">Wall Height:</label>
            <input type="text" id="wall_height" name="wall_height" class="form-control" value="{{ $inspection->wall_height }}" maxlength="255">
            </div>
            <div class="col-md-4 form-group">
            <label for="length">Length:</label>
            <input type="text" id="length" name="length" class="form-control" value="{{ $inspection->length }}" maxlength="255">
            </div>
        </div>

        <h2>7. Image Uploads</h2>
        <div class="row">
            <div class="col-md-12 form-group">
            <label for="uploaded_images">Upload Images:</label>
            <input type="file" id="uploaded_images" name="uploaded_images[]" class="form-control" multiple>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 form-group">
            <input type="submit" value="Submit" class="btn btn-primary">
            </div>
        </div>
    </form>

</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let oldValues = @json(old());

        document.querySelectorAll("input[type='text'], input[type='number']").forEach(input => {
            let inputName = input.getAttribute("name");
            if (inputName && oldValues[inputName] !== undefined) {
                input.value = oldValues[inputName];
            }
        });

        document.querySelectorAll("select").forEach(select => {
            let selectName = select.getAttribute("name");
            if (selectName && oldValues[selectName] !== undefined) {
                select.value = oldValues[selectName];
            }
        });
    });
</script>
<script>
    function updateJsonData() {
        const block = document.getElementById("block").value;
        const phase = document.getElementById("phase").value;
        const tower = document.getElementById("tower").value;

        const jsonData = JSON.stringify({ block, phase, tower });

        document.getElementById("jsonData").value = jsonData;
    }

    document.getElementById("block").addEventListener("input", updateJsonData);
    document.getElementById("phase").addEventListener("input", updateJsonData);
    document.getElementById("tower").addEventListener("input", updateJsonData);
</script>
<script>
    function updateGarageJsonData() {
        const garageAvailable = document.getElementById("garage_available").value;
        const garageType = document.getElementById("garageType").value;

        const jsonData = JSON.stringify({ garageAvailable, garageType });

        document.getElementById("garageJsonData").value = jsonData;
    }

    document.getElementById("garage_available").addEventListener("change", updateGarageJsonData);
    document.getElementById("garageType").addEventListener("change", updateGarageJsonData);
</script>
<script>
    function updateLandAreaJson() {
        const superBuiltup = document.getElementById("super_builtup_area").value;
        const builtup = document.getElementById("builtup_area").value;
        const carpet = document.getElementById("carpet_area").value;
        const roof = document.getElementById("measurement_of_roof").value;

        const jsonData = JSON.stringify({ 
            super_builtup_area: superBuiltup, 
            builtup_area: builtup, 
            carpet_area: carpet,
            measurement_of_roof:roof
        });

        document.getElementById("land_area").value = jsonData;
    }

    document.getElementById("super_builtup_area").addEventListener("input", updateLandAreaJson);
    document.getElementById("builtup_area").addEventListener("input", updateLandAreaJson);
    document.getElementById("carpet_area").addEventListener("input", updateLandAreaJson);
    document.getElementById("measurement_of_roof").addEventListener("input", updateLandAreaJson);
</script>
@endsection
