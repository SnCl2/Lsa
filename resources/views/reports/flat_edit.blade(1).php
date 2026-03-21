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

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Input Section -->
        <div class="col-md-4">
            <div class="card shadow-sm p-3 h-100 overflow-auto">
                <h4>Inputs</h4>
                <form id="inputForm">
                    @csrf
                    <input type="hidden" id="work_id" >
                    <div class="form-group">
                        <label for="racpc">RACPC</label>
                        <input type="text" id="racpc"  name="racpc" class="form-control" placeholder="Type something...">
                    </div>
                    <div class="form-group">
                        <label for="property_type">Select Property Type:</label>
                        <select id="property_type" name="property_type" onchange="updatePropertyType()">
                            <option value="FLAT">FLAT</option>
                            <option value="UNIT">UNIT</option>
                            <option value="APARTMENT">APARTMENT</option>
                        </select>
                        <label for="report_description">Report Description</label>
                        <textarea id="report_description" name="report_description" class="form-control" rows="10">
                            
                    </textarea>
                    </div>
                    <div class="form-group">
                        <label for="OWNER">OWNER Description</label>
                        <textarea id="OWNER" name="OWNER" class="form-control" rows="10">
                    </textarea>
                    </div>
                    <div class="form-group">
                        <label for="PURCHASER_BORROWER">PURCHASER/BORROWER Description</label>
                        <textarea id="PURCHASER_BORROWER" name="PURCHASER_BORROWER" class="form-control" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="valuation_PURPOSE">Select Purpose:</label>
                        <select id="valuation_PURPOSE" name="valuation_PURPOSE" onchange="updatePurpose()">
                            <option value="NPA">NPA</option>
                            <option value="PERSONAL">PERSONAL</option>
                            <option value="REVALUATION">REVALUATION</option>
                            <option value="RESALE">RESALE</option>
                            <option value="TOP-UP">TOP-UP</option>
                            <option value="SECURITY & BANK FINANCE">SECURITY & BANK FINANCE</option>
                        </select>
                        
                        <textarea id="purpose" name="purpose" class="form-control" rows="5">
                        TO ASSESS FAIR MARKET VALUE OF THE SAID PROPERTY.
                        NPA FOR F.M.V PURPOSE
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label for="inspection_date">Date of Inspection</label>
                        <input type="date" id="inspection_date" name="inspection_date" class="form-control" >

                    </div>
                    <div class="form-group">
                        <label for="valuation_date">Date of Valuation</label>
                        <input type="date" id="valuation_date" name="valuation_date" class="form-control" >
                        <label>Select DAG NO. OR PLOT NO. Type:</label>

                        <div class="form-check">
                            <input type="checkbox" id="cs_dag_check" class="form-check-input" value="C.S. DAG NO. OR PLOT NO." onclick="updateTextarea()">
                            <label for="cs_dag_check" class="form-check-label">C.S. DAG NO. OR PLOT NO.</label>
                        </div>
                    
                        <div class="form-check">
                            <input type="checkbox" id="rs_dag_check" class="form-check-input" value="R.S. DAG NO. OR PLOT NO." onclick="updateTextarea()">
                            <label for="rs_dag_check" class="form-check-label">R.S. DAG NO. OR PLOT NO.</label>
                        </div>
                    
                        <div class="form-check">
                            <input type="checkbox" id="lr_dag_check" class="form-check-input" value="L.R. DAG NO. OR PLOT NO." onclick="updateTextarea()">
                            <label for="lr_dag_check" class="form-check-label">L.R. DAG NO. OR PLOT NO.</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="lr_dag_check" class="form-check-input" value="J.L. NO" onclick="updateTextarea()">
                            <label for="lr_dag_check" class="form-check-label">J.L. NO.</label>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" id="lr_dag_check" class="form-check-input" value="L.R. KHATIAN NO." onclick="updateTextarea()">
                            <label for="lr_dag_check" class="form-check-label">L.R. KHATIAN NO.</label>
                        </div>
                    
                        <label for="dag_no" class="mt-3">Selected DAG NO. OR PLOT NO. (Editable):</label>
                        <textarea id="dag_no" name="dag_no" class="form-control" rows="3"></textarea>
                        

                        <label for="door_no">Door No.</label>
                        <input type="text" id="door_no" name="door_no" class="form-control" >

                        <label for="village_mouza">T. S. No. / Village/ Mouza</label>
                        <select  id="village_mouza_" name="village_mouza_" class="form-control" onchange="updateInputField()">
                            <option value="">___Select___</option>
                            <option value="Mouza">Mouza</option>
                            <option value="Village">Village</option>
                            <option value="TS No.">TS No.</option>
                        </select>
                        <input type="text" id="village_mouza" name="village_mouza" class="form-control" value="">

                        <label for="ward_taluka">Ward / Taluka</label>
                        <input type="text" id="ward_taluka" name="ward_taluka" class="form-control" >

                        <label for="mandal_district">Mandal / District</label>
                        <input type="text" id="mandal_district" name="mandal_district" class="form-control" >

                        <label for="layout_validity">Date of Issue and Validity of Layout of Approved / Plan</label>
                        <input type="text" id="layout_validity" name="layout_validity" class="form-control">

                        <label for="approved_authority" class="form-label">Approved Authority</label>
                        <input type="text" id="approved_authority" name="approved_authority" class="form-control">
                        <!--<select id="approved_authority" name="approved_authority" class="form-control">-->
                        <!--    <option value="">-- Select Authority --</option>-->
                        <!--    <option value="PANCHAYET">PANCHAYET</option>-->
                        <!--    <option value="MUNICIPALITY">MUNICIPALITY</option>-->
                        <!--    <option value="MUNICIPAL CORPORATION">MUNICIPAL CORPORATION</option>-->
                        <!--    <option value="DEVELOPMENT AUTHORITY">DEVELOPMENT AUTHORITY</option>-->
                        <!--    <option value="WEST BENGAL HOUSING COMPLEX">WEST BENGAL HOUSING COMPLEX</option>-->
                        <!--    <option value="OTHERS">OTHERS</option>-->
                        <!--</select>-->

                        <label for="authenticity_verified">Whether genuineness or authenticity of approved map/plan is verified</label>
                        <select type="text" id="authenticity_verified" name="authenticity_verified" class="form-control">
                            
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                            <option value="OTHERS">OTHERS</option>
                        </select>

                        <label for="valuer_comments">Any other Comments by our empanelled valuers on authenticity of approved plan</label>
                        <input type="text" id="valuer_comments" name="valuer_comments" class="form-control">

                        <label for="postal_address">Postal Address of the Property</label>
                        <textarea id="postal_address" name="postal_address" class="form-control" rows="3">{{ $work->address_line_1 ?? '' }} {{ $work->address_line_2 ?? '' }}</textarea>


                        <label for="city_town">City / Town</label>
                        <input type="text" id="city_town" name="city_town" class="form-control">

                        <label for="residential_area">Residential Area</label>
                        <select type="text" id="residential_area" name="residential_area" class="form-control">

                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                            <option value="OTHERS">OTHERS</option>
                        </select>

                        <label for="commercial_area">Commercial Area</label>
                        <Select type="text" id="commercial_area" name="commercial_area" class="form-control">
                            
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                            <option value="OTHERS">OTHERS</option>
                        </Select>

                        <label for="industrial_area">Industrial Area</label>
                        <Select type="text" id="industrial_area" name="industrial_area" class="form-control">
                            
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                            <option value="OTHERS">OTHERS</option>
                        </Select>

                        <label for="high_middle_poor">HIGH / MIDDLE / POOR </label>
                        <select id="high_middle_poor" name="high_middle_poor" class="form-control">
                            <option value="high">High</option>
                            <option value="middle">Middle</option>
                            <option value="poor">Poor</option>
                            <option value="OTHERS">OTHERS</option>
                        </select>

                        <label for="urban_semiurban_rural">Urban / Semi Urban / Rural</label>
                        <select id="urban_semiurban_rural" name="urban_semiurban_rural" class="form-control">
                            <option value="urban">Urban</option>
                            <option value="semi_urban">Semi Urban</option>
                            <option value="rural">Rural</option>
                            <option value="OTHERS">OTHERS</option>
                        </select>

                        <label for="corporation_limit">Coming Under</label>
                        <input type="text" id="corporation_limit" name="corporation_limit" class="form-control" >
                        <!--<select id="corporation_limit" name="corporation_limit" class="form-control">-->
                        <!--    <option value="">Select an option</option>-->
                        <!--    <option value="Village Panchayet">Village Panchayet</option>-->
                        <!--    <option value="Municipality">Municipality</option>-->
                        <!--    <option value="Corporation">Corporation</option>-->
                        <!--    <option value="OTHERS">OTHERS</option>-->
                        <!--</select>-->


                        <label for="govt_enactments">Whether Covered Under Any State / Central Govt. Enactments (e.g. Urban Land Ceiling Act) or Notified Under Agency Area / Scheduled Area / Cantonment Area</label>
                        <input type="text" id="govt_enactments" name="govt_enactments" class="form-control">
                        <!-- ------------------------------- -->
                        <table class="table table-bordered mt-4">
                            <tr>
                                <td rowspan="5">12</td>
                                <td colspan="2" class="font-weight-bold">BOUNDARIES OF THE PROPERTY (AS PER AGREEMENT)</td>
                                <td class="font-weight-bold">BUILDING (ACTUAL)</td>
                                <td class="font-weight-bold">FLAT (ACTUAL)</td>
                            </tr>
                            <tr>
                                <td>NORTH</td>
                                <td><input type="text" id="north_agreement" name="north_agreement" class="form-control"></td>
                                <td><input type="text" id="north_building" name="north_building" class="form-control" ></td>
                                <td><input type="text" id="north_flat" name="north_flat" class="form-control" ></td>
                            </tr>
                            <tr>
                                <td>SOUTH</td>
                                <td><input type="text" id="south_agreement" name="south_agreement" class="form-control"></td>
                                <td><input type="text" id="south_building" name="south_building" class="form-control"></td>
                                <td><input type="text" id="south_flat" name="south_flat" class="form-control" ></td>
                            </tr>
                            <tr>
                                <td>EAST</td>
                                <td><input type="text" id="east_agreement" name="east_agreement" class="form-control"></td>
                                <td><input type="text" id="east_building" name="east_building" class="form-control" ></td>
                                <td><input type="text" id="east_flat" name="east_flat" class="form-control" ></td>
                            </tr>
                            <tr>
                                <td>WEST</td>
                                <td><input type="text" id="west_agreement" name="west_agreement" class="form-control"></td>
                                <td><input type="text" id="west_building" name="west_building" class="form-control" ></td>
                                <td><input type="text" id="west_flat" name="west_flat" class="form-control"></td>
                            </tr>
                        </table>
                        <table class="table table-bordered mt-4">
                            <tr>
                                <td rowspan="5">13</td>
                                <td colspan="2" class="font-weight-bold">DIMENSION OF THE PROPERTY</td>
                                <td class="font-weight-bold">AS PER PLAN</td>
                                <td class="font-weight-bold">ACTUAL</td>
                            </tr>
                            <tr>
                                <td>NORTH</td>
                                <td><input type="text" id="north_DIMENSION" name="north_DIMENSION" class="form-control"></td>
                                <td><input type="text" id="north_PLAN" name="north_PLAN" class="form-control"></td>
                                <td><input type="text" id="north_ACTUAL" name="north_ACTUAL" class="form-control" ></td>
                            </tr>
                            <tr>
                                <td>SOUTH</td>
                                <td><input type="text" id="south_DIMENSION" name="south_DIMENSION" class="form-control"></td>
                                <td><input type="text" id="south_PLAN" name="south_PLAN" class="form-control" ></td>
                                <td><input type="text" id="south_ACTUAL" name="south_ACTUAL" class="form-control"></td>
                            </tr>
                            <tr>
                                <td>EAST</td>
                                <td><input type="text" id="east_DIMENSION" name="east_DIMENSION" class="form-control"></td>
                                <td><input type="text" id="east_PLAN" name="east_PLAN" class="form-control" ></td>
                                <td><input type="text" id="east_ACTUAL" name="east_ACTUAL" class="form-control" ></td>
                            </tr>
                            <tr>
                                <td>WEST</td>
                                <td><input type="text" id="west_DIMENSION" name="west_DIMENSION" class="form-control"></td>
                                <td><input type="text" id="west_PLAN" name="west_PLAN" class="form-control" ></td>
                                <td><input type="text" id="west_ACTUAL" name="west_ACTUAL" class="form-control"></td>
                            </tr>
                        </table>
                        <table class="table table-bordered mt-4">
                            <tr>
                                <td>LATITUDE</td>
                                <td>LONGITUDE </td>
                            </tr>
                            <tr>
                                <td><input type="text" id="latitude" name="latitude" class="form-control"></td>
                                <td><input type="text" id="longitude" name="longitude" class="form-control"></td>
                            </tr>
                        </table>
                        <table class="table table-bordered mt-4">
                            <tr>
                                <td colspan="2">EXTENT OF THE SITE 
                                    @php
                                        $landAreaData = json_decode($work->inspection->land_area ?? '{}', true);
                                    @endphp
                                    
                                    <!-- Checkboxes for selecting values -->
                                    <div>
                                        <label><input type="checkbox" class="extent-checkbox" data-value="Super Built-up Area: {{ $landAreaData['super_builtup_area'] ?? '' }}"> Super Built-up Area</label><br>
                                        <label><input type="checkbox" class="extent-checkbox" data-value="Built-up Area: {{ $landAreaData['builtup_area'] ?? '' }}"> Built-up Area</label><br>
                                        <label><input type="checkbox" class="extent-checkbox" data-value="Carpet Area: {{ $landAreaData['carpet_area'] ?? '' }}"> Carpet Area</label><br>
                                        
                                    </div>
                                </td>

                            </tr>
                            <tr>
                                <td><input type="text" id="EXTENT_SITE" name="EXTENT_SITE" class="form-control" ></td>
                                <td>
                                    <select id="EXTENT_SITE_unit" name="EXTENT_SITE_unit" class="form-control">
                                        <option value="SQ.FT">SQ.FT</option>
                                        <option value="SQ.MTR">SQ.MTR</option>
                                        <option value="OTHERS">OTHERS</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <table class="table table-bordered mt-4">
                            <tr>
                                <td colspan="2">EXTENT OF THE SITE CONSIDERED FOR VALUATION (LEAST OF 14 A & 14 B)
                                <div>
                                        <label><input type="checkbox" class="extent-checkbox2" data-value="Super Built-up Area: {{ $landAreaData['super_builtup_area'] ?? '' }}"> Super Built-up Area</label><br>
                                        <label><input type="checkbox" class="extent-checkbox2" data-value="Built-up Area: {{ $landAreaData['builtup_area'] ?? '' }}"> Built-up Area</label><br>
                                        <label><input type="checkbox" class="extent-checkbox2" data-value="Carpet Area: {{ $landAreaData['carpet_area'] ?? '' }}"> Carpet Area</label><br>
                                        
                                    </div>
                                </td>

                            </tr>
                            <tr>
                                <td><input type="text" id="EXTENT_SITE_2" name="EXTENT_SITE_2" class="form-control"></td>
                                <td>
                                    <select id="EXTENT_SITE_unit_2" name="EXTENT_SITE_unit_2" class="form-control">
                                        <option value="SQ.FT">SQ.FT</option>
                                        <option value="SQ.MTR">SQ.MTR</option>
                                        <option value="OTHERS">OTHERS</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <label for="occupied_by">Whether occupied by the owner / tenant? If occupied by tenant, since how long? Rent received per month.</label>
                        <!--<input type="text" id="occupied_by" name="occupied_by" class="form-control">-->
                        <select name="occupied_by" id="occupied_by" class="form-control">
                            <option value="Owner" >Owner</option>
                            <option value="Tenant" >Tenant</option>
                            <option value="Vacant" >Vacant</option>
                            <option value="OTHERS" >OTHERS</option>
                        </select>

                        <label for="nature">Nature of the Apartment</label>
                        <select class="form-control" id="nature" name="nature" required>
                            <option value="">Select Nature</option>
                            <option value="Residential">Residential</option>
                            <option value="Commercial">Commercial</option>
                            <option value="OTHERS">OTHERS</option>
                        </select>

                        <label for="tsno">T. S. No.</label>
                        <input type="text" id="tsno" name="tsno" class="form-control">

                        <label for="block_no">Block No.</label>
                        <input type="text" id="block_no" name="block_no" class="form-control" >

                        <label for="ward_no">Ward No.</label>
                        <input type="text" id="ward_no" name="ward_no" class="form-control" >

                        <label for="Village_Municipality_Corporation">Village/ Municipality / Corporation.</label>
                        <input type="text" id="Village_Municipality_Corporation" name="Village_Municipality_Corporation" class="form-control" >

                        <label for="door_street_road">Door No., Street or Road (Pin Code)</label>
                        <input type="text" id="door_street_road" name="door_street_road" class="form-control" >

                        <label for="Feasibility">Feasibility to the Civic amenities like school, hospital, bus stop, market etc.</label>
                        <textarea type="text" id="Feasibility" name="Feasibility" class="form-control">

                        </textarea>

                        <label for="Facility">Road Facility</label>
                        <input type="text" id="Facility" name="Facility" class="form-control" >

                        <label for="Description_locality">Description of the locality</label>
                        <select id="Description_locality" name="Description_locality" class="form-control ">
                                <option value="Residential ">Residential </option>
                                <option value="Commercial">Commercial </option>
                                <option value="Mixed">Mixed </option>
                                <option value="Residential Cum Commercial">Residential Cum Commercial</option>
                                <option value="Industrial">Industrial</option>
                                <option value="OTHERS">OTHERS</option>
                        </select>

                        <label for="Year_of_Construction">Year of Construction</label>
                        <input type="text" id="Year_of_Construction" name="Year_of_Construction" class="form-control" >

                        <label for="Number_of_Floors">Number of Floors</label>
                        <input type="text" id="Number_of_Floors" name="Number_of_Floors" class="form-control" >

                        <label for="Type_of_Structure">Type of Structure</label>
                        <select id="Type_of_Structure" name="Type_of_Structure" class="form-control ">
                                <option value="RCC ">RCC </option>
                                <option value="LOAD BEARING">LOAD BEARING </option>
                                <option value="STEEL STRUCTURE">STEEL STRUCTURE </option>
                                <option value="OTHERS">OTHERS</option>
                        </select>

                        <label for="number_unit">NUMBER OF DWELLING UNITS IN THE
                        BUILDING</label>
                        <input type="text" id="number_unit" name="number_unit" class="form-control" >

                        <label for="QUALITY_OF_CONSTRUCTION">QUALITY OF CONSTRUCTION</label>
                        <select id="QUALITY_OF_CONSTRUCTION" name="QUALITY_OF_CONSTRUCTION" class="form-control ">
                                <option value="POOR">POOR</option>
                                <option value="GOOD">GOOD</option>
                                <option value="AVERAGE">AVERAGE</option>
                                <option value="SUPERIOR">SUPERIOR</option>
                                <option value="OTHERS">OTHERS</option>
                        </select>

                        <label for="APPEARANCE_OF_THE_BUILDING">APPEARANCE OF THE BUILDING</label>
                        <select id="APPEARANCE_OF_THE_BUILDING" name="APPEARANCE_OF_THE_BUILDING" class="form-control ">
                                <option value="POOR">POOR</option>
                                <option value="GOOD">GOOD</option>
                                <option value="AVERAGE">AVERAGE</option>
                                <option value="SUPERIOR">SUPERIOR</option>
                                <option value="OTHERS">OTHERS</option>
                        </select>

                        <label for="MAINTENANCE_OF_THE_BUILDING">MAINTENANCE OF THE BUILDING</label>
                        <select id="MAINTENANCE_OF_THE_BUILDING" name="MAINTENANCE_OF_THE_BUILDING" class="form-control" >
                                <option value="WELL MAINTENANCE">WELL MAINTENANCE</option>
                                <option value="GOOD MAINTENANCE">GOOD MAINTENANCE</option>
                                <option value="HIGH MAINTENANCE">HIGH MAINTENANCE</option>
                                <option value="OTHERS">OTHERS</option>
                        </select>

                        <label for="lift">Lift:</label>
                        <select name="lift" id="lift" class="form-control">
                            <option value="no" {{ ($work->inspection->lift_available ?? '') == 'No' ? 'selected' : '' }}>No</option>
                            <option value="yes" {{ ($work->inspection->lift_available ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="OTHERS" {{ ($work->inspection->lift_available ?? '') == 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                        </select>


                            <label for="water_supply">Protected Water Supply:</label>
                            <select name="water_supply" id="water_supply" class="form-control">
                                
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>


                            <label for="sewerage">Underground Sewerage:</label>
                            <select name="sewerage" id="sewerage" class="form-control">
                                
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>


                            <label for="car_parking">Car Parking - Open/Covered:</label>
                            <select name="car_parking" id="car_parking" class="form-control">
                                
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>


                            <label for="compound_wall">Is Compound Wall Existing?</label>
                            <select name="compound_wall" id="compound_wall" class="form-control">
                                
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>


                            <label for="pavement">Is Pavement Laid Around?</label>
                            <select name="pavement" id="pavement" class="form-control">
                                
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="floor">The Floor on Which the Flat is Situated:</label>
                            <input type="text" name="floor" id="floor" class="form-control" >


                            <label for="door_no_2">Door No. of the Flat:</label>
                            <input type="text" name="door_no_2" id="door_no_2" class="form-control" >

                            <label for="roof">ROOF:</label>
                            <select name="roof" id="roof" class="form-control">
                                <option value="RCC_frame">RCC Frame</option>
                                <option value="load_bearing_wall">Load Bearing Wall</option>
                                <option value="steel_structure">Steel Structure</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="FLOORING">FLOORING:</label>
                            <input type="text" name="FLOORING" id="FLOORING" class="form-control" >

                            <label for="door_type">DOORS:</label>
                            <select name="door_type" id="door_type" class="form-control">
                                <option value="Wooden Door" {{ optional($work->inspection)->door_type == 'Wooden Door' ? 'selected' : '' }}>Wooden Door</option>
                                <option value="Steel Door" {{ optional($work->inspection)->door_type == 'Steel Door' ? 'selected' : '' }}>Steel Door</option>
                                <option value="Glass Door" {{ optional($work->inspection)->door_type == 'Glass Door' ? 'selected' : '' }}>Glass Door</option>
                                <option value="PVC (UPVC) Door" {{ optional($work->inspection)->door_type == 'PVC (UPVC) Door' ? 'selected' : '' }}>PVC (UPVC) Door</option>
                                <option value="French Door" {{ optional($work->inspection)->door_type == 'French Door' ? 'selected' : '' }}>French Door</option>
                                <option value="Sliding Door" {{ optional($work->inspection)->door_type == 'Sliding Door' ? 'selected' : '' }}>Sliding Door</option>
                                <option value="Folding Door" {{ optional($work->inspection)->door_type == 'Folding Door (Bi-Fold Door)' ? 'selected' : '' }}>Folding Door (Bi-Fold Door)</option>
                                <option value="Hinged Door" {{ optional($work->inspection)->door_type == 'Hinged Door' ? 'selected' : '' }}>Hinged Door</option>
                                <option value="Others" {{ optional($work->inspection)->door_type == 'Others' ? 'selected' : '' }}>Others</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>


                            <label for="window_type">WINDOWS:</label>
                            <select name="window_type" id="window_type" class="form-control">
                                <option value="aluminum_channel_glass">Aluminum Channel Glass Fitted Sliding Window</option>
                                <option value="ms_grill_protected">Protected with M.S. Grill</option>
                                <option value="hinged_window">Hinged Window</option>
                                <option value="double_hung_window">Double Hung Window</option>
                                <option value="sliding_window">Sliding Window</option>
                                <option value="fixed_window">Fixed Window</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="fittings">FITTINGS:</label>
                            <select name="fittings" id="fittings" class="form-control">
                                <option value="conceal_wiring">Conceal Wiring</option>
                                <option value="surface_wiring">Surface Wiring</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="finishing">FINISHING:</label>
                            <select name="finishing" id="finishing" class="form-control">
                                <option value="putty_paint">Putty Wall with Paints</option>
                                <option value="pop">POP (Plaster of Paris)</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="ACCOMMODATION">ACCOMMODATION</label>
                            <input type="text" name="ACCOMMODATION" id="ACCOMMODATION" class="form-control">

                            <label for="house_tax">HOUSE TAX:</label>
                            <select name="house_tax" id="house_tax" class="form-control">
                                <option value="info_not_available">Information Not Available</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="assessment_no">ASSESSMENT NO.:</label>
                            <select name="assessment_no" id="assessment_no" class="form-control">
                                <option value="info_not_available">Information Not Available</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="tax_paid_name">TAX PAID IN THE NAME OF:</label>
                            <select name="tax_paid_name" id="tax_paid_name" class="form-control">
                                <option value="info_not_available">Information Not Available</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="tax_amount">TAX AMOUNT:</label>
                            <select name="tax_amount" id="tax_amount" class="form-control">
                                <option value="info_not_available">Information Not Available</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="electricity_connection_no">ELECTRICITY SERVICE CONNECTION NO.:</label>
                            <select name="electricity_connection_no" id="electricity_connection_no" class="form-control">
                                <option value="info_not_available">Information Not Available</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="meter_card_name">METER CARD IS IN THE NAME OF:</label>
                            <select name="meter_card_name" id="meter_card_name" class="form-control">
                                <option value="info_not_available">Information Not Available</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="flat_maintenance">How is the maintenance of the flat?</label>
                            <input type="text" name="flat_maintenance" id="flat_maintenance" class="form-control">

                            <label for="sale_agreement_name">Sale Agreement executed in the name of:</label>
                            <input type="text" name="sale_agreement_name" id="sale_agreement_name" class="form-control">

                            <label for="undivided_area">What is the undivided area of land as per Sale Agreement?</label>
                            <input type="text" name="undivided_area" id="undivided_area" class="form-control" >

                            <label for="plinth_area">What is the plinth area of the flat?</label>
                            <input type="text" name="plinth_area" id="plinth_area" class="form-control" >

                            <label for="floor_space_index">What is the floor space index (app.)?</label>
                            <select name="floor_space_index" id="floor_space_index" class="form-control">
                                <option value="info_not_available">Information Not Available</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="carpet_area">What is the Carpet Area of the flat?</label>
                            <input type="text" name="carpet_area" id="carpet_area" class="form-control" >

                            <label for="flat_class">Is it Posh/ I class / Medium / Ordinary?</label>
                            <select name="flat_class" id="flat_class" class="form-control">
                                <option value="medium">Medium</option>
                                <option value="Is it Posh">Is it Posh</option>
                                <option value="I class">I class</option>
                                <option value="Ordinary">Ordinary</option>
                                <option value="OTHERS">OTHERS</option>

                            </select>

                            <label for="usage_purpose">Is it being used for Residential or Commercial purpose?</label>
                            <input name="usage_purpose" id="usage_purpose" class="form-control">


                            <label for="occupancy_status">Is it Owner-occupied or let out?</label>
                            <input name="occupancy_status" id="occupancy_status" class="form-control">



                            <label for="monthly_rent">If rented, what is the monthly rent?</label>
                            <input type="text" name="monthly_rent" id="monthly_rent" class="form-control">

                            <label for="marketability">How is the marketability?</label>
                            <input type="text" name="marketability" id="marketability" class="form-control">

                            <label for="extra_potential">What are the factors favouring for an extra Potential Value?</label>
                            <select name="extra_potential" id="extra_potential" class="form-control">
                                
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>

                            <label for="negative_factors">Any negative factors are observed which affect the market value in general?</label>
                            <select name="negative_factors" id="negative_factors" class="form-control">
                                
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>

                            <label for="composite_rate">After analysing the comparable sale instances, what is the composite rate for a similar flat with same specifications in the adjoining locality?</label>
                            <textarea name="composite_rate" id="composite_rate" class="form-control">
                                1. Govt. Guide Line Price collected from Govt. Web Site : ----Link---- shows that Market & itshows that Market rate is Rs.---Rate--- per decimal
 
                                2. Price collected from 99acres.com Web Site : ----Link---- shows that Market & itshows that Market rate is Rs.---Rate--- per decimal
                                
                                3. Price collected from realestateindia.comWeb Site : ----Link---- shows that Market & itshows that Market rate is Rs.---Rate--- per decimal 

                            </textarea>

                            <label for="adopted_rate">Assuming it is a new construction, what is the adopted basic composite rate of the flat under valuation after comparing with the specifications and other factors with the flat under comparison (give details).</label>
                            <textarea name="adopted_rate" id="adopted_rate" class="form-control"></textarea>

                            <label for="building_services">Building + Services</label>
                            <input type="text" name="building_services" id="building_services" class="form-control">

                            <label for="land_others">Land + Others</label>
                            <input type="text" name="land_others" id="land_others" class="form-control">

                            <label for="guideline_rate">Guideline rate obtained from the Registrar's office (an evidence thereof to be enclosed)</label>
                            <textarea name="guideline_rate" id="guideline_rate" class="form-control"></textarea>


                            <label for="depreciated_building_rate">Depreciated building rate (Rs. Per Sq. Ft.)</label>
                            <input type="text" name="depreciated_building_rate" id="depreciated_building_rate" class="form-control">

                            <label for="replacement_cost">Replacement cost of flat with Services (Rs. Per Sq. Ft.)</label>
                            <input type="text" name="replacement_cost" id="replacement_cost" class="form-control">

                            <label for="building_age">Age of the building</label>
                            <input type="text" name="building_age" id="building_age" class="form-control">

                            <label for="building_life">Life of the building estimated (Years)</label>
                            <input type="text" name="building_life" id="building_life" class="form-control">

                            <label for="depreciation_percentage">Depreciation percentage assuming the salvage value as 10%</label>
                            <input type="text" name="depreciation_percentage" id="depreciation_percentage" class="form-control">

                            <label for="depreciated_ratio">Depreciated Ratio of the building</label>
                            <input type="text" name="depreciated_ratio" id="depreciated_ratio" class="form-control">

                            <label for="total_composite_rate">Total composite rate arrived for valuation</label>

                            <label for="depreciated_building_rate_total">Depreciated building rate VI (a) <label>
                            <input type="text" name="depreciated_building_rate_total" id="depreciated_building_rate_total" class="form-control">

                            <label for="rate_land_other">Rate for Land & other V (3)ii <label>
                            <input type="text" name="rate_land_other" id="rate_land_other" class="form-control">

                            <label for="total_composite">Total Composite Rate <label>
                            <input type="text" name="total_composite" id="total_composite" class="form-control">

                            <table class="table table-bordered mt-4">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Description</th>
                                        <th>Qty. Sq.ft</th>
                                        <th>Rate per Sq.ft. Rs.</th>
                                        <th>Estimated Value Rs.</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>After completion value of the flat</td>
                                        <td><input type="text" name="qty_1" id="qty_1" class="form-control"></td>
                                        <td><input type="text" name="rate_1" id="rate_1" class="form-control"></td>
                                        <td><input type="text" name="value_1" id="value_1" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Wardrobes</td>
                                        <td><input type="text" name="qty_2" id="qty_2" class="form-control"></td>
                                        <td><input type="text" name="rate_2" id="rate_2" class="form-control"></td>
                                        <td><input type="text" name="value_2" id="value_2" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Showcases</td>
                                        <td><input type="text" name="qty_3" id="qty_3" class="form-control"></td>
                                        <td><input type="text" name="rate_3" id="rate_3" class="form-control"></td>
                                        <td><input type="text" name="value_3" id="value_3" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Kitchen Arrangements & Wardrobe</td>
                                        <td><input type="text" name="qty_4" id="qty_4" class="form-control"></td>
                                        <td><input type="text" name="rate_4" id="rate_4" class="form-control"></td>
                                        <td><input type="text" name="value_4" id="value_4" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Superfine Finish</td>
                                        <td><input type="text" name="qty_5" id="qty_5" class="form-control"></td>
                                        <td><input type="text" name="rate_5" id="rate_5" class="form-control"></td>
                                        <td><input type="text" name="value_5" id="value_5" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>Interior Decorations</td>
                                        <td><input type="text" name="qty_6" id="qty_6" class="form-control"></td>
                                        <td><input type="text" name="rate_6" id="rate_6" class="form-control"></td>
                                        <td><input type="text" name="value_6" id="value_6" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>Electricity deposits / electrical fittings, etc.</td>
                                        <td><input type="text" name="qty_7" id="qty_7" class="form-control"></td>
                                        <td><input type="text" name="rate_7" id="rate_7" class="form-control"></td>
                                        <td><input type="text" name="value_7" id="value_7" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>Extra collapsible gates / grill works etc.</td>
                                        <td><input type="text" name="qty_8" id="qty_8" class="form-control"></td>
                                        <td><input type="text" name="rate_8" id="rate_8" class="form-control"></td>
                                        <td><input type="text" name="value_8" id="value_8" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>Potential value, if any</td>
                                        <td><input type="text" name="qty_9" id="qty_9" class="form-control"></td>
                                        <td><input type="text" name="rate_9" id="rate_9" class="form-control"></td>
                                        <td><input type="text" name="value_9" id="value_9" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>Covered Car Parking</td>
                                        <td><input type="text" name="qty_10" id="qty_10" class="form-control"></td>
                                        <td><input type="text" name="rate_10" id="rate_10" class="form-control"></td>
                                        <td><input type="text" name="value_10" id="value_10" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><strong>Total</strong></td>
                                        <td><input type="text" name="total_value" id="total_value" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4"><strong>Rounded Off</strong></td>
                                        <td><input type="text" name="rounded_value" id="rounded_value" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                            

                                



                                    <table class="table table-bordered mt-4">
                                        <thead >
                                            <tr>

                                                <th>PARTICULARS</th>
                                                <th>VALUER COMMENT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                              
                                                <td>BACKGROUND INFORMATION OF THE ASSET BEING VALUED;</td>
                                                <td>
                                                    <select id="BACKGROUND" class="form-select">
                                                        <option value="NO">NO</option>
                                                        <option value="YES">YES</option>
                                                        <option value="OTHERS">OTHERS</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                               
                                                <td>PURPOSE OF VALUATION AND APPOINTING AUTHORITY</td>
                                                <td><input id="PURPOSE2" type="text" class="form-control" value="NPA"></td>
                                            </tr>
                                            <tr>
                                                
                                                <td>IDENTITY OF THE VALUER AND ANY OTHER EXPERTS INVOLVED IN THE VALUATION;</td>
                                                <td><input id="IDENTITY" type="text" class="form-control" value="MR. ALOKE CHATTERJEE. SURVEYOR."  ></td>
                                            </tr>
                                            <tr>
                                               
                                                <td>DISCLOSURE OF VALUER INTEREST OR CONFLICT, IF ANY;</td>
                                                <td>
                                                    <select id="DISCLOSURE" name="valuation_procedure">
                                                        <option value="NO">NO</option>
                                                        <option value="YES">YES</option>
                                                        <option value="OTHERS">OTHERS</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                
                                                <td>DATE OF APPOINTMENT, VALUATION DATE AND DATE OF REPORT;</td>
                                                <td><input id="DATE_OF_APPOINTMENT"type="text" class="form-control" ></td>
                                            </tr>
                                            <tr>
                                            
                                                <td>INSPECTIONS AND/OR INVESTIGATIONS UNDERTAKEN</td>
                                                <td><input id="INSPECTIONS_UNDERTAKEN" type="text" class="form-control"></td>
                                            </tr>
                                            <tr>
                                             
                                                <td>NATURE AND SOURCES OF THE INFORMATION USED OR RELIED UPON;</td>
                                                <td>
                                                    <select id="NATURE_AND_SOURCES" name="valuation_procedure">
                                                        <option value="NO">NO</option>
                                                        <option value="YES">YES</option>
                                                        <option value="OTHERS">OTHERS</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                              
                                                <td>PROCEDURES ADOPTED IN CARRYING OUT THE VALUATION AND VALUATION STANDARDS FOLLOWED;</td>
                                                <td>
                                                    
                                                    <select id="valuation_procedure" name="valuation_procedure">
                                                        <option value="COMPOSITE RATE">COMPOSITE RATE</option>
                                                        <option value="STRAIGHT LINE METHOD">STRAIGHT LINE METHOD</option>
                                                        <option value="OTHERS">OTHERS</option>
                                                    </select>
                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                          
                                                <td>RESTRICTIONS ON USE OF THE REPORT, IF ANY;</td>
                                                <td>
                                                    <select id="report_restrictions" name="report_restrictions">
                                    
                                                        <option value="NO">NO</option>
                                                        <option value="YES">YES</option>
                                                        <option value="OTHERS">OTHERS</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                        
                                                <td>MAJOR FACTORS THAT WERE TAKEN INTO ACCOUNT DURING THE VALUATION;</td>
                                                <td>
                                                    <select id="factors_considered" name="factors_considered">
                                                        <option value="NO">NO</option>
                                                        <option value="YES">YES</option>
                                                        <option value="OTHERS">OTHERS</option>
                                                    </select>
                                                                    
                                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                           
                                                <td>MAJOR FACTORS THAT WERE NOT TAKEN INTO ACCOUNT DURING THE VALUATION;</td>
                                                <td>
                                                    <select id="factors_not_considered" name="factors_not_considered">
                                                        <option value="NO">NO</option>
                                                        <option value="YES">YES</option>
                                                        <option value="OTHERS">OTHERS</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                      
                                                <td>CAVEATS, LIMITATIONS AND DISCLAIMERS TO THE EXTENT THEY EXPLAIN OR ELUCIDATE THE LIMITATIONS FACED BY VALUER, WHICH SHALL NOT BE FOR THE PURPOSE OF LIMITING HIS RESPONSIBILITY FOR THE VALUATION REPORT.</td>
                                                <td>
                                                    <select id="caveats_limitations" name="caveats_limitations">
                                    
                                                        <option value="YES">YES</option>
                                                        <option value="NO">NO</option>
                                                        <option value="OTHERS">OTHERS</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>


                    </div>
                    <button type="submit" class="btn btn-success">Done</button>

                </form>
                
                <form id="reportForm" action="{{ route('report.update', $report->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Spoofing the PUT request for updating -->
                
                    <label for="work_id" style="display: none;">Work ID:</label>
                    <input type="text" id="work_id" name="work_id" >
                    <br>
                
                    <label for="report_data" style="display: none;">Report Data:</label>
                    <textarea id="report_data" name="report_data" required>{{ $report->data }}</textarea>
                    <br>
                    
                    <button type="submit">Update And Download Report</button>
                </form>
            </div>
        </div>

        <!-- Output Section -->
        <div class="col-md-8">
        @include('reports.flat_show')
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
//     document.addEventListener("DOMContentLoaded", function () {
//     // Assuming the JSON data is available in a JavaScript variable
//     // let reportData = @json($report->data); // Laravel Blade directive to pass JSON safely
//     // console.log(typeof reportData);
//     let reportData = JSON.parse(@json($report->data))
//     console.log(typeof reportData);

//     // Ensure the data is an object
//     if (typeof reportData !== "object" || reportData === null) {
//         console.error("Invalid JSON data");
//         return;
//     }

//     // Loop through each key-value pair in the JSON data
//     Object.keys(reportData).forEach(function (key) {
//         let element = document.querySelector(`#inputForm [id="${key}"]`);

//         if (element) {
//             if (element.tagName === "INPUT" || element.tagName === "TEXTAREA") {
//                 element.value = reportData[key];
//             } else if (element.tagName === "SELECT") {
//                 let optionExists = [...element.options].some(option => option.value === reportData[key]);
//                 if (optionExists) {
//                     element.value = reportData[key];
//                 }
//             }
//         }
//     });
// });

    document.addEventListener("DOMContentLoaded", function () {
        // Parse the JSON data safely
        let reportData;
        try {
            reportData = JSON.parse(@json($report->data));
        } catch (error) {
            console.error("Invalid JSON data:", error);
            return;
        }
    
        console.log(typeof reportData);
    
        // Ensure the data is an object
        if (typeof reportData !== "object" || reportData === null) {
            console.error("Invalid JSON data");
            return;
        }
    
        // Loop through each key-value pair in the JSON data
        Object.keys(reportData).forEach(function (key) {
            let element = document.querySelector(`#inputForm [id="${key}"]`);
    
            if (element) {
                if (element.tagName === "INPUT" || element.tagName === "TEXTAREA") {
                    element.value = reportData[key];
                } else if (element.tagName === "SELECT") {
                    let optionExists = [...element.options].some(option => option.value === reportData[key]);
    
                    if (optionExists) {
                        element.value = reportData[key];
                    } else {
                        // Convert select to text input
                        let textInput = document.createElement("input");
                        textInput.type = "text";
                        textInput.id = element.id;
                        textInput.name = element.name;
                        textInput.value = reportData[key];
                        textInput.className = element.className; // Preserve styling if any
    
                        // Replace select with text input
                        element.parentNode.replaceChild(textInput, element);
                    }
                }
            }
        });
    });

    
</script>


<script>
$(document).ready(function(){
    // Set current date values
    $("#currentMonth").html(new Date().getMonth() + 1);
    $("#currentYear").html(new Date().getFullYear());
    $("#currentDate").html(new Date().getDate());


    function updateOutput(inputSelector, outputSelector) {
    let $input = $(inputSelector);
    let $output = $(outputSelector);

    function formatText(text) {
        if (isNaN(text)) { // Check if the value is a string
            text = text.replace(/_/g, ' '); // Remove underscores
            text = text.charAt(0).toUpperCase() + text.slice(1); // Capitalize first letter
        }
        return text;
    }

    // Set initial value
    $output.html(formatText($input.val().trim()));

    // Listen for input changes
    $input.on("input", function() {
        $output.html(formatText($(this).val().trim()));
    });
}


    // Apply to relevant fields
    updateOutput("#report_description", "#report_description_out");
    updateOutput("#report_description", "#report_description_out2");
    updateOutput("#OWNER", "#OWNER_out");
    updateOutput("#PURCHASER_BORROWER", "#PURCHASER_BORROWER_out");
    updateOutput("#racpc", "#racpc_out1");
    updateOutput("#racpc", "#racpc_out2");
    updateOutput("#purpose", "#purpose_out");
    updateOutput("#inspection_date", "#inspection_date_out");
    updateOutput("#inspection_date", "#inspection_date_out2");
    updateOutput("#valuation_date", "#valuation_date_out");
    updateOutput("#valuation_date", "#valuation_date_out2");
    updateOutput("#valuation_date", "#valuation_date_out3");
    updateOutput("#valuation_date", "#valuation_date_out4");

    updateOutput("#PURCHASER_BORROWER", "#PURCHASER_BORROWERS_out3");
    updateOutput("#OWNER", "#OWNER_out2");
    updateOutput("#cs_dag_no", "#cs_dag_no_out");
    updateOutput("#rs_dag_no", "#rs_dag_no_out");
    updateOutput("#lr_dag_no", "#lr_dag_no_out");
    updateOutput("#door_no", "#door_no_out");
    updateOutput("#village_mouza", "#village_mouza_out");
    updateOutput("#ward_taluka", "#ward_taluka_out");
    updateOutput("#mandal_district", "#mandal_district_out");
    updateOutput("#layout_validity", "#layout_validity_out");
    updateOutput("#approved_authority", "#approved_authority_out");
    updateOutput("#authenticity_verified", "#authenticity_verified_out");
    updateOutput("#valuer_comments", "#valuer_comments_out");
    updateOutput("#postal_address", "#postal_address_out");
    updateOutput("#city_town", "#city_town_out");
    updateOutput("#residential_area", "#residential_area_out");
    updateOutput("#commercial_area", "#commercial_area_out");
    updateOutput("#industrial_area", "#industrial_area_out");
    updateOutput("#high_middle_poor", "#high_middle_poor_out");
    updateOutput("#urban_semiurban_rural", "#urban_semiurban_rural_out");
    updateOutput("#corporation_limit", "#corporation_limit_out");
    updateOutput("#govt_enactments", "#govt_enactments_out");
    updateOutput("#north_agreement", "#north_agreement_out");
    updateOutput("#north_building", "#north_building_out");
    updateOutput("#north_flat", "#north_flat_out");
    updateOutput("#south_agreement", "#south_agreement_out");
    updateOutput("#south_building", "#south_building_out");
    updateOutput("#south_flat", "#south_flat_out");
    updateOutput("#east_agreement", "#east_agreement_out");
    updateOutput("#east_building", "#east_building_out");
    updateOutput("#east_flat", "#east_flat_out");
    updateOutput("#west_agreement", "#west_agreement_out");
    updateOutput("#west_building", "#west_building_out");
    updateOutput("#west_flat", "#west_flat_out");
    updateOutput("#north_DIMENSION", "#north_DIMENSION_out");
    updateOutput("#north_PLAN", "#north_PLAN_out");
    updateOutput("#north_ACTUAL", "#north_ACTUAL_out");
    updateOutput("#south_DIMENSION", "#south_DIMENSION_out");
    updateOutput("#south_PLAN", "#south_PLAN_out");
    updateOutput("#south_ACTUAL", "#south_ACTUAL_out");
    updateOutput("#east_DIMENSION", "#east_DIMENSION_out");
    updateOutput("#east_PLAN", "#east_PLAN_out");
    updateOutput("#east_ACTUAL", "#east_ACTUAL_out");
    updateOutput("#west_DIMENSION", "#west_DIMENSION_out");
    updateOutput("#west_PLAN", "#west_PLAN_out");
    updateOutput("#west_ACTUAL", "#west_ACTUAL_out");
    updateOutput("#latitude", "#latitude_out");
    updateOutput("#longitude", "#longitude_out");
    updateOutput("#EXTENT_SITE", "#EXTENT_SITE_out");
    updateOutput("#EXTENT_SITE_unit", "#EXTENT_SITE_unit_out");
    updateOutput("#EXTENT_SITE_2", "#EXTENT_SITE_2_out");
    updateOutput("#EXTENT_SITE_unit_2", "#EXTENT_SITE_unit_2_out");
    updateOutput("#occupied_by", "#occupied_by_out");
    updateOutput("#nature", "#nature_out");
    updateOutput("#tsno", "#tsno_out");
    updateOutput("#block_no", "#block_no_out");
    updateOutput("#ward_no", "#ward_no_out");
    updateOutput("#Village_Municipality_Corporation", "#Village_Municipality_Corporation_out");
    updateOutput("#door_street_road", "#door_street_road_out");
    updateOutput("#Feasibility", "#Feasibility_out");
    updateOutput("#Facility", "#Facility_out");
    updateOutput("#Description_locality", "#Description_locality_out");
    updateOutput("#Year_of_Construction", "#Year_of_Construction_out");
    updateOutput("#Number_of_Floors", "#Number_of_Floors_out");
    updateOutput("#Type_of_Structure", "#Type_of_Structure_out");
    updateOutput("#number_unit", "#number_unit_out");
    updateOutput("#QUALITY_OF_CONSTRUCTION", "#QUALITY_OF_CONSTRUCTION_out");
    updateOutput("#APPEARANCE_OF_THE_BUILDING", "#APPEARANCE_OF_THE_BUILDING_out");
    updateOutput("#MAINTENANCE_OF_THE_BUILDING", "#MAINTENANCE_OF_THE_BUILDING_out");
    updateOutput("#lift", "#lift_out");
    updateOutput("#water_supply", "#water_supply_out");
    updateOutput("#sewerage", "#sewerage_out");
    updateOutput("#car_parking", "#car_parking_out");
    updateOutput("#compound_wall", "#compound_wall_out");
    updateOutput("#pavement", "#pavement_out");
    updateOutput("#floor", "#floor_out");
    updateOutput("#door_no_2", "#door_no_2_out");
    updateOutput("#roof", "#roof_out");
    updateOutput("#FLOORING", "#FLOORING_out");
    updateOutput("#door_type", "#door_type_out");
    updateOutput("#window_type", "#window_type_out");
    updateOutput("#fittings", "#fittings_out");
    updateOutput("#finishing", "#finishing_out");
    updateOutput("#ACCOMMODATION", "#ACCOMMODATION_out");
    updateOutput("#house_tax", "#house_tax_out");
    updateOutput("#assessment_no", "#assessment_no_out");
    updateOutput("#tax_paid_name", "#tax_paid_name_out");
    updateOutput("#tax_amount", "#tax_amount_out");
    updateOutput("#electricity_connection_no", "#electricity_connection_no_out");
    updateOutput("#meter_card_name", "#meter_card_name_out");
    updateOutput("#flat_maintenance", "#flat_maintenance_out");
    updateOutput("#sale_agreement_name", "#sale_agreement_name_out");
    updateOutput("#undivided_area", "#undivided_area_out");
    updateOutput("#plinth_area", "#plinth_area_out");
    updateOutput("#floor_space_index", "#floor_space_index_out");
    updateOutput("#carpet_area", "#carpet_area_out");
    updateOutput("#flat_class", "#flat_class_out");
    updateOutput("#usage_purpose", "#usage_purpose_out");
    updateOutput("#occupancy_status", "#occupancy_status_out");
    updateOutput("#monthly_rent", "#monthly_rent_out");
    updateOutput("#marketability", "#marketability_out");
    updateOutput("#extra_potential", "#extra_potential_out");
    updateOutput("#negative_factors", "#negative_factors_out");
    updateOutput("#composite_rate", "#composite_rate_out");
    updateOutput("#adopted_rate", "#adopted_rate_out");
    updateOutput("#building_services", "#building_services_out");
    updateOutput("#land_others", "#land_others_out");
    updateOutput("#guideline_rate", "#guideline_rate_out");
    updateOutput("#depreciated_building_rate", "#depreciated_building_rate_out");
    updateOutput("#replacement_cost", "#replacement_cost_out");
    updateOutput("#building_age", "#building_age_out");
    updateOutput("#building_life", "#building_life_out");
    updateOutput("#depreciation_percentage", "#depreciation_percentage_out");
    updateOutput("#depreciated_ratio", "#depreciated_ratio_out");
    // updateOutput("#total_composite_rate", "#total_composite_rate_out");
    updateOutput("#depreciated_building_rate_total", "#depreciated_building_rate_total_out");
    updateOutput("#rate_land_other", "#rate_land_other_out");
    updateOutput("#total_composite", "#total_composite_out");
    updateOutput("#qty_1", "#qty_1_out");
    updateOutput("#rate_1", "#rate_1_out");
    updateOutput("#value_1", "#value_1_out");
    updateOutput("#qty_2", "#qty_2_out");
    updateOutput("#rate_2", "#rate_2_out");
    updateOutput("#value_2", "#value_2_out");
    updateOutput("#qty_3", "#qty_3_out");
    updateOutput("#rate_3", "#rate_3_out");
    updateOutput("#value_3", "#value_3_out");
    updateOutput("#qty_4", "#qty_4_out");
    updateOutput("#rate_4", "#rate_4_out");
    updateOutput("#value_4", "#value_4_out");
    updateOutput("#qty_5", "#qty_5_out");
    updateOutput("#rate_5", "#rate_5_out");
    updateOutput("#value_5", "#value_5_out");
    updateOutput("#qty_6", "#qty_6_out");
    updateOutput("#rate_6", "#rate_6_out");
    updateOutput("#value_6", "#value_6_out");
    updateOutput("#qty_7", "#qty_7_out");
    updateOutput("#rate_7", "#rate_7_out");
    updateOutput("#value_7", "#value_7_out");
    updateOutput("#qty_8", "#qty_8_out");
    updateOutput("#rate_8", "#rate_8_out");
    updateOutput("#value_8", "#value_8_out");
    updateOutput("#qty_9", "#qty_9_out");
    updateOutput("#rate_9", "#rate_9_out");
    updateOutput("#value_9", "#value_9_out");
    updateOutput("#qty_10", "#qty_10_out");
    updateOutput("#rate_10", "#rate_10_out");
    updateOutput("#value_10", "#value_10_out");
    updateOutput("#total_value", "#total_value_out");
    updateOutput("#rounded_value", "#rounded_value_out");
    // new fields
    updateOutput("#valuation_PURPOSE", "#valuation_PURPOSE_out");
    updateOutput("#valuation_procedure", "#valuation_procedure_out");
    updateOutput("#report_restrictions", "#report_restrictions_out");
    updateOutput("#factors_considered", "#factors_considered_out");
    updateOutput("#factors_not_considered", "#factors_not_considered_out");
    updateOutput("#caveats_limitations", "#caveats_limitations_out");


});
</script>

<script>
        document.getElementById('inputForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = {};
            const elements = document.querySelectorAll('#inputForm input, #inputForm textarea, #inputForm select');
            
            elements.forEach(element => {
                formData[element.id] = element.value;
            });
            
            const jsonData = JSON.stringify(formData, null, 2);
            
            const reportData = document.getElementById('report_data');
            if (reportData) {
                reportData.value = jsonData;
            }
            const reportLayout = document.getElementById('Report_layout');
            const contentInput = document.getElementById('content');
            if (reportLayout && contentInput) {
                contentInput.value = reportLayout.innerHTML;
            }
            
        });
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Attach event listener to all existing and future <select> elements
    document.body.addEventListener("change", function (event) {
        if (event.target.tagName === "SELECT") {
            let select = event.target;
            let selectedValue = select.value;

            if (selectedValue === "yes" || selectedValue === "OTHERS" || selectedValue === "YES" || selectedValue === "Yes" ) {
                let inputField = document.createElement("input");

                // Copy attributes from the original select field
                inputField.id = select.id;
                inputField.name = select.name;
                inputField.type = "text";
                inputField.value = selectedValue; // Set default value
                inputField.placeholder = "Enter details";
                inputField.setAttribute("data-original-select", select.id); // Store reference

                // Restore dropdown if input is empty
                inputField.addEventListener("blur", function () {
                    if (this.value.trim() === "") {
                        restoreDropdown(this);
                    }
                });

                // Replace select with input field
                select.parentNode.replaceChild(inputField, select);
                inputField.focus();
            }
        }
    });
});

// Function to restore the select field if input is left empty
function restoreDropdown(inputField) {
    let originalSelect = document.createElement("select");

    // Restore ID and Name
    originalSelect.id = inputField.id;
    originalSelect.name = inputField.name;

    // Populate the dropdown with original options
    originalSelect.innerHTML = `
        <option value="">-- Select --</option>
        <option value="no">NO</option>
        <option value="yes">YES</option>
        <option value="other">Other (Specify)</option>
    `;

    // Replace input field with dropdown
    inputField.parentNode.replaceChild(originalSelect, inputField);
}

function updatePropertyType() {
    let dropdown = document.getElementById("property_type");
    let selectedValue = dropdown.value;
    let textArea = document.getElementById("report_description");

    // Replace "FLAT", "UNIT", or "APARTMENT" with the selected value
    textArea.value = textArea.value.replace(/\b(FLAT|UNIT|APARTMENT) NO\./, `${selectedValue} NO.`);
}
</script>
<script>
function updatePurpose() {
    let dropdown = document.getElementById("valuation_PURPOSE");
    let selectedValue = dropdown.value;
    let textArea = document.getElementById("purpose");

    // Replace the existing purpose with the selected value
    textArea.value = textArea.value.replace(/\b(NPA|PERSONAL|REVALUATION|RESALE|TOP-UP|SECURITY|BANK FINANCE)\b/, selectedValue);
}
</script>
<script>
function updateInputField() {
    document.getElementById("village_mouza").value = document.getElementById("village_mouza_").value;
}
</script>




@endsection
