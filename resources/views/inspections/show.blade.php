@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Inspection Details</h3>
        </div>
        <div class="card-body">
            <div class="card mb-3 shadow-sm border">
            <div class="card-header font-weight-bold">Bank Details</div>
            <div class="card-body">
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Bank Branch</div>
                <div class="col-md-8">{{ $inspection->bank_branch }}</div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Phone No</div>
                <div class="col-md-8">{{ $inspection->phone_no }}</div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Representative</div>
                <div class="col-md-8">{{ $inspection->representative }}</div>
                </div>
            </div>
            </div>
            <div class="card mb-3 shadow-sm border">
            <div class="card-header font-weight-bold">Applicant Details</div>
            <div class="card-body">
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Applicant Name</div>
                <div class="col-md-8">{{ $inspection->applicant_name }}</div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Address</div>
                <div class="col-md-8">{{ $inspection->address }}</div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Apartment Name</div>
                <div class="col-md-8">{{ $inspection->apartment_name }}</div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Holding No</div>
                <div class="col-md-8">{{ $inspection->holding_no }}</div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Road</div>
                <div class="col-md-8">{{ $inspection->road }}</div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Post Office</div>
                <div class="col-md-8">{{ $inspection->post_office }}</div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Police Station</div>
                <div class="col-md-8">{{ $inspection->police_station }}</div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Pin Code</div>
                <div class="col-md-8">{{ $inspection->pin_code }}</div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Ward</div>
                <div class="col-md-8">{{ $inspection->ward }}</div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">District</div>
                <div class="col-md-8">{{ $inspection->district }}</div>
                </div>
                <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Authority</div>
                <div class="col-md-8">{{ $inspection->authority }}</div>
                </div>
            </div>
            </div>
        </div>
        <div class="card-body">
            <h4>Boundaries and Dimensions</h4>
            <div class="card mb-3 shadow-sm border">
                <div class="card-header font-weight-bold">Boundary Flat Actual</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">North</div>
                        <div class="col-md-3">{{ $inspection->boundary_flat_actual_north }}</div>
                        <div class="col-md-3 font-weight-bold">South</div>
                        <div class="col-md-3">{{ $inspection->boundary_flat_actual_south }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">East</div>
                        <div class="col-md-3">{{ $inspection->boundary_flat_actual_east }}</div>
                        <div class="col-md-3 font-weight-bold">West</div>
                        <div class="col-md-3">{{ $inspection->boundary_flat_actual_west }}</div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 shadow-sm border">
                <div class="card-header font-weight-bold">Boundary Flat Deed</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">North</div>
                        <div class="col-md-3">{{ $inspection->boundary_flat_deed_north }}</div>
                        <div class="col-md-3 font-weight-bold">South</div>
                        <div class="col-md-3">{{ $inspection->boundary_flat_deed_south }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">East</div>
                        <div class="col-md-3">{{ $inspection->boundary_flat_deed_east }}</div>
                        <div class="col-md-3 font-weight-bold">West</div>
                        <div class="col-md-3">{{ $inspection->boundary_flat_deed_west }}</div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 shadow-sm border">
                <div class="card-header font-weight-bold">Boundary Building Actual</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">North</div>
                        <div class="col-md-3">{{ $inspection->boundary_building_actual_north }}</div>
                        <div class="col-md-3 font-weight-bold">South</div>
                        <div class="col-md-3">{{ $inspection->boundary_building_actual_south }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">East</div>
                        <div class="col-md-3">{{ $inspection->boundary_building_actual_east }}</div>
                        <div class="col-md-3 font-weight-bold">West</div>
                        <div class="col-md-3">{{ $inspection->boundary_building_actual_west }}</div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 shadow-sm border">
                <div class="card-header font-weight-bold">Boundary Building Deed</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">North</div>
                        <div class="col-md-3">{{ $inspection->boundary_building_deed_north }}</div>
                        <div class="col-md-3 font-weight-bold">South</div>
                        <div class="col-md-3">{{ $inspection->boundary_building_deed_south }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">East</div>
                        <div class="col-md-3">{{ $inspection->boundary_building_deed_east }}</div>
                        <div class="col-md-3 font-weight-bold">West</div>
                        <div class="col-md-3">{{ $inspection->boundary_building_deed_west }}</div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 shadow-sm border">
                <div class="card-header font-weight-bold">Dimensions Flat Actual</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">North</div>
                        <div class="col-md-3">{{ $inspection->dimensions_flat_actual_north }}</div>
                        <div class="col-md-3 font-weight-bold">South</div>
                        <div class="col-md-3">{{ $inspection->dimensions_flat_actual_south }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">East</div>
                        <div class="col-md-3">{{ $inspection->dimensions_flat_actual_east }}</div>
                        <div class="col-md-3 font-weight-bold">West</div>
                        <div class="col-md-3">{{ $inspection->dimensions_flat_actual_west }}</div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 shadow-sm border">
                <div class="card-header font-weight-bold">Dimensions Flat Deed</div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">North</div>
                        <div class="col-md-3">{{ $inspection->dimensions_flat_deed_north }}</div>
                        <div class="col-md-3 font-weight-bold">South</div>
                        <div class="col-md-3">{{ $inspection->dimensions_flat_deed_south }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3 font-weight-bold">East</div>
                        <div class="col-md-3">{{ $inspection->dimensions_flat_deed_east }}</div>
                        <div class="col-md-3 font-weight-bold">West</div>
                        <div class="col-md-3">{{ $inspection->dimensions_flat_deed_west }}</div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 shadow-sm border">
                <div class="card-header font-weight-bold">Property Details</div>
                <div class="card-body">
                <div class="row ">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Property Type</span>
                                <span class="w-50">{{ $inspection->property_type }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Nature of Property</span>
                                <span class="w-50">{{ $inspection->nature_property }}</span>
                            </div>
                            <div class="d-flex  mb-3">
                                <span class="font-weight-bold w-50">Flat No</span>
                                <span class="w-50">{{ $inspection->flat_no }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Floor</span>
                                <span class="w-50">{{ $inspection->floor }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Located at Side</span>
                                <span class="w-50">{{ $inspection->located_at_side }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Block</span>
                                <span class="w-50">{{ $inspection->block }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Lift Available</span>
                                <span class="w-50">{{ $inspection->lift_available }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Garage Available</span>
                                <span class="w-50">{{ $inspection->garage_available }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Land Area</span>
                                <span class="w-50">{{ $inspection->land_area }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Flats per Floor</span>
                                <span class="w-50">{{ $inspection->flats_per_floor }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Dwelling Unit</span>
                                <span class="w-50">{{ $inspection->dwelling_unit }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Number of Floors</span>
                                <span class="w-50">{{ $inspection->number_of_floors }}</span>
                            </div>
                            <div class="d-flex">
                                <span class="font-weight-bold w-50">Super Built-up Area</span>
                                <span class="w-50">{{ $inspection->super_built_up_area }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Occupied By</span>
                                <span class="w-50">{{ $inspection->occupied_by }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Year of Occupancy</span>
                                <span class="w-50">{{ $inspection->year_of_occupancy }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Year of Construction</span>
                                <span class="w-50">{{ $inspection->year_of_construction }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Light Points</span>
                                <span class="w-50">{{ $inspection->light_points }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Fan Points</span>
                                <span class="w-50">{{ $inspection->fan_points }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Water Closets</span>
                                <span class="w-50">{{ $inspection->water_closets }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Washbasins</span>
                                <span class="w-50">{{ $inspection->washbasins }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Bathtubs</span>
                                <span class="w-50">{{ $inspection->bathtubs }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Plug Points</span>
                                <span class="w-50">{{ $inspection->plug_points }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Door Type</span>
                                <span class="w-50">{{ $inspection->door_type }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Flooring Type</span>
                                <span class="w-50">{{ $inspection->flooring_type }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Window Type</span>
                                <span class="w-50">{{ $inspection->window_type }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Wiring Type</span>
                                <span class="w-50">{{ $inspection->wiring_type }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3 shadow-sm border">
                <div class="card-header font-weight-bold">Nearest Facilities</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Nearest Bus Stand Name</span>
                                <span class="w-50">{{ $inspection->nearest_bus_stand_name }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Nearest Bus Stand Distance</span>
                                <span class="w-50">{{ $inspection->nearest_bus_stand_distance }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Nearest Railway Station Name</span>
                                <span class="w-50">{{ $inspection->nearest_railway_station_name }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Nearest Railway Station Distance</span>
                                <span class="w-50">{{ $inspection->nearest_railway_station_distance }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Nearest Landmark Name</span>
                                <span class="w-50">{{ $inspection->nearest_landmark_name }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Nearest Landmark Distance</span>
                                <span class="w-50">{{ $inspection->nearest_landmark_distance }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Connected Road</span>
                                <span class="w-50">{{ $inspection->connected_road }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Plot Demarcated</span>
                                <span class="w-50">{{ $inspection->plot_demarcated }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Plot Demarcated Description</span>
                                <span class="w-50">{{ $inspection->plot_demarcated_description }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Wall Height</span>
                                <span class="w-50">{{ $inspection->wall_height }}</span>
                            </div>
                            <div class="d-flex mb-3">
                                <span class="font-weight-bold w-50">Length</span>
                                <span class="w-50">{{ $inspection->length }}</span>
                            </div>
                        </div>
                    </div>
                    @if (!empty($inspection->uploaded_images) && count($inspection->uploaded_images) > 0)
                                    <div class="container my-4">
                                        <h3 class="text-center fw-bold text-lg mb-4">Inspection Uploaded Images</h3>
                                        <div class="table-responsive">
                                            <table class="table table-borderless"> {{-- Bootstrap table without borders --}}
                                                <tbody>
                                                    @foreach (array_chunk($inspection->uploaded_images, 3) as $imageRow) {{-- Group images into rows of 3 --}}
                                                        <tr>
                                                            @foreach ($imageRow as $image)
                                                                <td class="text-center p-2">
                                                                    <img src="{{ asset('/public/storage/'.$image) }}" alt="Inspection Image" 
                                                                         class="rounded shadow-sm" style="width: 200px; height: 200px; object-fit: cover;">
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                                
                </div>
            </div>

        </div>
        <div class="card-footer">
            <a href="{{ route('inspections.edit', $inspection->id) }}" class="btn btn-primary">Edit</a>
        </div>
        




        </div>
    </div>
</div>
@endsection 