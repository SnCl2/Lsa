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
    <h1>Create Inspection for Work: {{ $work->name }}</h1>
    <form method="POST" action="{{ route('inspections.store',$work->id) }}" enctype="multipart/form-data">
        @csrf
        <h2>1. General Information</h2>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="bank_branch">Bank Branch:</label>
            <input type="text" class="form-control" id="bank_branch" name="bank_branch" required value="{{$work->bankBranch?->name}}">
            </div>
            <div class="col-md-3 form-group">
            <label for="phone_no">Phone Number:</label>
            <input type="text" class="form-control" id="phone_no" name="phone_no" value="{{ $work->number_of_applicants }}" required >
            </div>
            <div class="col-md-3 form-group">
            <label for="representative">Representative:</label>
            <input type="text" class="form-control" id="representative" name="representative" required>
            </div>
            <div class="col-md-3 form-group">
            <label for="applicant_name">Applicant Name:</label>
            <input type="text" class="form-control" id="applicant_name" name="applicant_name" value="{{ $work->name_of_applicant }}" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
            <label for="address">Postal Address:</label>
            <input type="text" class="form-control" id="address" name="address"  required>
            </div>
            <div class="col-md-6 form-group">
            <label for="apartment_name">Apartment Name:</label>
            <input type="text" class="form-control" id="apartment_name" name="apartment_name" >
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
            <label for="holding_no">Holding Number:</label>
            <input type="text" class="form-control" id="holding_no" name="holding_no" value="{{ $work->address_line_1 }}">
            </div>
            <div class="col-md-6 form-group">
            <label for="road">Road:</label>
            <input type="text" class="form-control" id="road" name="road" value="{{ $work->address_line_2 }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
            <label for="post_office">Post Office:</label>
            <input type="text" class="form-control" id="post_office" name="post_office" value="{{ $work->post_office }}">
            </div>
            <div class="col-md-6 form-group">
            <label for="police_station">Police Station:</label>
            <input type="text" class="form-control" id="police_station" name="police_station" value="{{ $work->police_station }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
            <label for="pin_code">Pin Code:</label>
            <input type="text" class="form-control" id="pin_code" name="pin_code" value="{{ $work->pin_code }}">
            </div>
            <div class="col-md-6 form-group">
            <label for="ward">Ward:</label>
            <input type="text" class="form-control" id="ward" name="ward">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
            <label for="district">District:</label>
            <input type="text" class="form-control" id="district" name="district" value="{{ $work->district }}">
            </div>
            <div class="col-md-6 form-group">
            <label for="authority">Authority:</label>
            <input type="text" class="form-control" id="authority" name="authority">
            </div>
        </div>
    </div>
        <!-- Update ^^^ -->
        <h2>2. Boundaries and Dimensions</h2>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="boundary_flat_actual_north">Boundary Flat Actual North:</label>
            <input type="text" class="form-control" id="boundary_flat_actual_north" name="boundary_flat_actual_north" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="boundary_flat_actual_south">Boundary Flat Actual South:</label>
            <input type="text" class="form-control" id="boundary_flat_actual_south" name="boundary_flat_actual_south" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="boundary_flat_actual_east">Boundary Flat Actual East:</label>
            <input type="text" class="form-control" id="boundary_flat_actual_east" name="boundary_flat_actual_east" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="boundary_flat_actual_west">Boundary Flat Actual West:</label>
            <input type="text" class="form-control" id="boundary_flat_actual_west" name="boundary_flat_actual_west" maxlength="255">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="boundary_flat_deed_north">Boundary Flat Deed North:</label>
            <input type="text" class="form-control" id="boundary_flat_deed_north" name="boundary_flat_deed_north" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="boundary_flat_deed_south">Boundary Flat Deed South:</label>
            <input type="text" class="form-control" id="boundary_flat_deed_south" name="boundary_flat_deed_south" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="boundary_flat_deed_east">Boundary Flat Deed East:</label>
            <input type="text" class="form-control" id="boundary_flat_deed_east" name="boundary_flat_deed_east" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="boundary_flat_deed_west">Boundary Flat Deed West:</label>
            <input type="text" class="form-control" id="boundary_flat_deed_west" name="boundary_flat_deed_west" maxlength="255">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="boundary_building_actual_north">Boundary Building Actual North:</label>
            <input type="text" class="form-control" id="boundary_building_actual_north" name="boundary_building_actual_north" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="boundary_building_actual_south">Boundary Building Actual South:</label>
            <input type="text" class="form-control" id="boundary_building_actual_south" name="boundary_building_actual_south" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="boundary_building_actual_east">Boundary Building Actual East:</label>
            <input type="text" class="form-control" id="boundary_building_actual_east" name="boundary_building_actual_east" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="boundary_building_actual_west">Boundary Building Actual West:</label>
            <input type="text" class="form-control" id="boundary_building_actual_west" name="boundary_building_actual_west" maxlength="255">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="boundary_building_deed_north">Boundary Building Deed North:</label>
            <input type="text" class="form-control" id="boundary_building_deed_north" name="boundary_building_deed_north" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="boundary_building_deed_south">Boundary Building Deed South:</label>
            <input type="text" class="form-control" id="boundary_building_deed_south" name="boundary_building_deed_south" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="boundary_building_deed_east">Boundary Building Deed East:</label>
            <input type="text" class="form-control" id="boundary_building_deed_east" name="boundary_building_deed_east" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="boundary_building_deed_west">Boundary Building Deed West:</label>
            <input type="text" class="form-control" id="boundary_building_deed_west" name="boundary_building_deed_west" maxlength="255">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="dimensions_flat_actual_north">Dimensions Flat Actual North:</label>
            <input type="text" class="form-control" id="dimensions_flat_actual_north" name="dimensions_flat_actual_north" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="dimensions_flat_actual_south">Dimensions Flat Actual South:</label>
            <input type="text" class="form-control" id="dimensions_flat_actual_south" name="dimensions_flat_actual_south" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="dimensions_flat_actual_east">Dimensions Flat Actual East:</label>
            <input type="text" class="form-control" id="dimensions_flat_actual_east" name="dimensions_flat_actual_east" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="dimensions_flat_actual_west">Dimensions Flat Actual West:</label>
            <input type="text" class="form-control" id="dimensions_flat_actual_west" name="dimensions_flat_actual_west" maxlength="255">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="dimensions_flat_deed_north">Dimensions Flat Deed North:</label>
            <input type="text" class="form-control" id="dimensions_flat_deed_north" name="dimensions_flat_deed_north" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="dimensions_flat_deed_south">Dimensions Flat Deed South:</label>
            <input type="text" class="form-control" id="dimensions_flat_deed_south" name="dimensions_flat_deed_south" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="dimensions_flat_deed_east">Dimensions Flat Deed East:</label>
            <input type="text" class="form-control" id="dimensions_flat_deed_east" name="dimensions_flat_deed_east" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="dimensions_flat_deed_west">Dimensions Flat Deed West:</label>
            <input type="text" class="form-control" id="dimensions_flat_deed_west" name="dimensions_flat_deed_west" maxlength="255">
            </div>
        </div>
<!-- updated ^^^^ -->
        <h2>3. Property Details</h2>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="property_type">Property Type:</label>
            <select id="property_type" name="property_type" class="form-control" required>
                <option value="">Select</option>
                <option value="Flat">Flat</option>
                <option value="Land & Building">Land & Building</option>
                <option value="Land">Land</option>
                <option value="Factory">Factory</option>
                <option value="Garage">Garage</option>
                <option value="Shop">Shop</option>
            </select>
            </div>
            <div class="col-md-3 form-group">
            <label for="nature_property">Nature of Property:</label>
            <select id="nature_property" name="nature_property" class="form-control" required>
                <option value="">Select</option>
                <option value="Residential">Residential</option>
                <option value="Commercial">Commercial</option>
                <option value="Both">Both</option>
            </select>
            </div>
            <div class="col-md-3 form-group">
            <label for="flat_no">Flat Number:</label>
            <input type="text" id="flat_no" name="flat_no" class="form-control" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="floor">Floor:</label>
            <input type="text" id="floor" name="floor" class="form-control" maxlength="255">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="located_at_side">Located at Side:</label>
            <input type="text" id="located_at_side" name="located_at_side" class="form-control" maxlength="255">
            </div>
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
            <input type="Hidden" id="jsonData" name="block">

            <div class="col-md-3 form-group">
            <label for="lift_available">Lift Available:</label>
            <select id="lift_available" name="lift_available" class="form-control" required>
                <option value="">Select</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
            </div>
            <div class="col-md-3 form-group">
            <label for="garage_available">Garage Available:</label>
            <select id="garage_available" name="_garage_available" class="form-control" required>
                <option value="">Select</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
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
            <input type="Hidden" id="garageJsonData" name="garage_available" >

        </div>
        
        <div class="row">
        <div class="col-md-3 form-group">
            <label for="super_builtup_area">Super Built-up Area:</label>
            <div class="input-group">
                <input type="text" id="super_builtup_area" name="super_builtup_area" class="form-control ">
                <select class="form-control unit-dropdown" data-input="super_builtup_area">
                    <option value="">Select Unit</option>
                    <option value="SQ.FT">SQ.FT</option>
                    <option value="SQ.MTR">SQ.MTR</option>
                </select>
            </div>
        </div>
    
        <div class="col-md-3 form-group">
            <label for="builtup_area">Built-up Area:</label>
            <div class="input-group">
                <input type="text" id="builtup_area" name="builtup_area" class="form-control">
                <select class="form-control unit-dropdown" data-input="builtup_area">
                    <option value="">Select Unit</option>
                    <option value="SQ.FT">SQ.FT</option>
                    <option value="SQ.MTR">SQ.MTR</option>
                </select>
            </div>
        </div>
    
        <div class="col-md-3 form-group">
            <label for="carpet_area">Carpet Area:</label>
            <div class="input-group">
                <input type="text" id="carpet_area" name="carpet_area" class="form-control">
                <select class="form-control unit-dropdown" data-input="carpet_area">
                    <option value="">Select Unit</option>
                    <option value="SQ.FT">SQ.FT</option>
                    <option value="SQ.MTR">SQ.MTR</option>
                </select>
            </div>
        </div>
    </div>
        <div class="row">
            
            <div class="col-md-3 form-group">
            <label for="measurement_of_roof">Measurement of roof:</label>
            <input type="text" id="measurement_of_roof" name="measurement_of_roof" class="form-control" maxlength="255">
            </div>
            <!-- Hidden input to store JSON format of all three fields -->
            <input type="Hidden" id="land_area" name="land_area">
            
            <div class="col-md-3 form-group">
            <label for="flats_per_floor">Flats per Floor:</label>
            <input type="text" id="flats_per_floor" name="flats_per_floor" class="form-control" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="dwelling_unit">Dwelling Unit:</label>
            <input type="text" id="dwelling_unit" name="dwelling_unit" class="form-control" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="number_of_floors">Number of Floors:</label>
            <input type="text" id="number_of_floors" name="number_of_floors" class="form-control" maxlength="255">
            </div>
            
            
        </div>
        <div class="row">
            <div class="col-md-3 form-group"  style="display: none;">
            <label for="super_built_up_area">Super Built Up Area:</label>
            <input type="text" id="super_built_up_area" name="super_built_up_area" class="form-control" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="occupied_by">Occupied By:</label>
            <select id="occupied_by" name="occupied_by" class="form-control" required>
                <option value="Owner">Owner</option>
                <option value="Tenant">Tenant</option>
                <option value="Vacant">Vacant</option>
            </select>
            </div>
            <div class="col-md-3 form-group">
            <label for="year_of_occupancy">Year of Occupancy:</label>
            <input type="text" id="year_of_occupancy" name="year_of_occupancy" class="form-control" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="year_of_construction">Year of Construction:</label>
            <input type="text" id="year_of_construction" name="year_of_construction" class="form-control" maxlength="255">
            </div>
        </div>
        <h2>4. Electrical & Plumbing Details</h2>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="light_points">Light Points:</label>
            <input type="number" id="light_points" name="light_points" class="form-control">
            </div>
            <div class="col-md-3 form-group">
            <label for="fan_points">Fan Points:</label>
            <input type="number" id="fan_points" name="fan_points" class="form-control">
            </div>
            <div class="col-md-3 form-group">
            <label for="water_closets">Water Closets:</label>
            <input type="number" id="water_closets" name="water_closets" class="form-control">
            </div>
            <div class="col-md-3 form-group">
            <label for="washbasins">Washbasins:</label>
            <input type="number" id="washbasins" name="washbasins" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="bathtubs">Bathtubs:</label>
            <input type="number" id="bathtubs" name="bathtubs" class="form-control">
            </div>
            <div class="col-md-3 form-group">
            <label for="plug_points">Plug Points:</label>
            <input type="number" id="plug_points" name="plug_points" class="form-control">
            </div>
            <div class="col-md-3 form-group">
            <label for="door_type">Door Type:</label>
            <select type="text" id="door_type" name="door_type" class="form-control" maxlength="255">
                <option value="Wooden Door">Wooden Door</option>
                <option value="Steel Door">Steel Door</option>
                <option value="Glass Door">Glass Door</option>
                <option value="PVC (UPVC) Door">PVC (UPVC) Door</option>
                <option value="French Door">French Door</option>
                <option value="Sliding Door">Sliding Door</option>
                <option value="Folding Door">Folding Door (Bi-Fold Door)</option>
                <option value="Hinged Door">Hinged Door</option>
                <option value="Others">Others</option>
            </select>
            </div>
            <div class="col-md-3 form-group">
            <label for="flooring_type">Flooring Type:</label>
            <select id="flooring_type" name="flooring_type" class="form-control" required>
                <option value="Mosaic">Mosaic</option>
                <option value="Tiles">Tiles</option>
                <option value="Marble">Marble</option>
                <option value="Cement">Cement</option>
            </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 form-group">
            <label for="window_type">Window Type:</label>
            <input type="text" id="window_type" name="window_type" class="form-control" maxlength="255">
            </div>
            <div class="col-md-3 form-group">
            <label for="wiring_type">Wiring Type:</label>
            <select id="wiring_type" name="wiring_type" class="form-control" required>
                <option value="Surface">Surface</option>
                <option value="Concealed">Concealed</option>
            </select>
            </div>
        </div>

        <h2>5. Location Details</h2>
        <div class="row">
            <div class="col-md-4 form-group">
            <label for="nearest_bus_stand_name">Nearest Bus Stand Name:</label>
            <input type="text" id="nearest_bus_stand_name" name="nearest_bus_stand_name" class="form-control" maxlength="255">
            </div>
            <div class="col-md-4 form-group">
                <label for="nearest_bus_stand_distance">Nearest Bus Stand Distance:</label>
                
                <div class="d-flex gap-2">
                    <input type="text" id="nearest_bus_stand_distance" name="nearest_bus_stand_distance" class="form-control" maxlength="255" style="flex: 1;">
                    
                    <select class="form-control unit-dropdown" data-input="nearest_bus_stand_distance" style="width: 100px;">
                        <option value="">Unit</option>
                        <option value="MTR">MTR</option>
                        <option value="KM">KM</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4 form-group">
            <label for="nearest_railway_station_name">Nearest Railway Station Name:</label>
            <input type="text" id="nearest_railway_station_name" name="nearest_railway_station_name" class="form-control" maxlength="255">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
                <label for="nearest_railway_station_distance">Nearest Railway Station Distance:</label>
                <select class="form-control unit-dropdown" data-input="nearest_railway_station_distance">
                    <option value="">Select Unit</option>
                    <option value="MTR">MTR</option>
                    <option value="KM">KM</option>
                </select>
            
            
            <input type="text" id="nearest_railway_station_distance" name="nearest_railway_station_distance" class="form-control" maxlength="255">
            </div>
            <div class="col-md-4 form-group">
            <label for="nearest_landmark_name">Nearest Landmark Name:</label>
            
            <input type="text" id="nearest_landmark_name" name="nearest_landmark_name" class="form-control" maxlength="255">
            </div>
            <div class="col-md-4 form-group">
            <label for="nearest_landmark_distance">Nearest Landmark Distance:</label>
            <select class="form-control unit-dropdown" data-input="nearest_landmark_distance">
                <option value="">Select</option>
                <option value="">Select Unit</option>
                <option value="MTR">MTR</option>
                <option value="KM">KM</option>
            </select>
            
            <input type="text" id="nearest_landmark_distance" name="nearest_landmark_distance" class="form-control" maxlength="255">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 form-group">
            <label for="connected_road">Connected Road:</label>
            <input type="text" id="connected_road" name="connected_road" class="form-control" maxlength="255">
            </div>
        </div>

        <h2>6. Plot & Measurement Details</h2>
        <div class="row">
            <div class="col-md-4 form-group">
            <label for="plot_demarcated">Plot Demarcated:</label>
            <select id="plot_demarcated" name="plot_demarcated" class="form-control" required>
                <option value="">Select</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
            </div>
            <div class="col-md-4 form-group">
            <label for="plot_demarcated_description">Plot Demarcated Description:</label>
            <textarea id="plot_demarcated_description" name="plot_demarcated_description" class="form-control"></textarea>
            </div>
            <div class="col-md-4 form-group">
                <label for="wall_height">Wall Height:</label>
                <div class="row">
                    <div class="col-6 pr-1">
                        <input type="text" id="wall_height" name="wall_height" class="form-control" maxlength="255">
                    </div>
                    <div class="col-6 pl-1">
                        <select class="form-control unit-dropdown" data-input="wall_height">
                            <option value="">Select Unit</option>
                            <option value="FT">FT</option>
                            <option value="FT INCH">FT INCH</option>
                            <option value="MTR">MTR</option>
                        </select>
                    </div>
                </div>
            </div>

            </div>
            <div class="col-md-4 form-group">
                <label for="length">Length:</label>
                <div class="row">
                    <div class="col-6 pr-1">
                        <input type="text" id="length" name="length" class="form-control" maxlength="255">
                    </div>
                    <div class="col-6 pl-1">
                        <select class="form-control unit-dropdown" data-input="length">
                            <option value="">Select Unit</option>
                            <option value="FT">FT</option>
                            <option value="RFT">RFT</option>
                            <option value="FT INCH">FT INCH</option>
                            <option value="MTR">MTR</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 form-group">
            <label for="uploaded_images">Image Uploads:</label>
            <input type="file" id="uploaded_images" name="uploaded_images[]" class="form-control" multiple>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 form-group">
            <input type="submit" value="Submit" class="btn btn-primary">
            </div>
        </div>
    </form>
</body>
</html>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let oldValues = @json(old());

        document.querySelectorAll("input[type='text'], input[type='number'],input[type='Hidden']").forEach(input => {
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
// <script>
// document.querySelectorAll(".unit-dropdown").forEach(dropdown => {
//     dropdown.addEventListener("change", function() {
//         let inputId = this.getAttribute("data-input");
//         let inputField = document.getElementById(inputId);
//         let unit = this.value;

//         if (unit) {
//             inputField.value += " " + unit; // Concatenate unit without replacing existing value
//         }
//     });
// });
// </script>

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
<script>
document.querySelectorAll(".unit-dropdown").forEach(dropdown => {
    dropdown.addEventListener("change", function() {
        let inputId = this.getAttribute("data-input");
        let inputField = document.getElementById(inputId);
        let unit = this.value;

        // Remove existing unit if already appended
        inputField.value = inputField.value.replace(/\s*(SQ\.FT|SQ\.MTR)$/, "");

        if (unit) {
            inputField.value += " " + unit; // Append selected unit
        }
    });
});
</script>

@endsection
