<?php

namespace App\Http\Controllers;

use App\Models\Inspection;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InspectionController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $inspection = Inspection::findOrFail($id);
        return view('inspections.show', compact('inspection'));
    }


    public function create($workId)
    {
        $work = Work::findOrFail($workId);
        return view('inspections.create', compact('work'));
    }
    public function store(Request $request, $workId)
    {
        
    try{
        $validatedData = $request->validate([
            // 'work_id' => 'required|exists:works,id',
            // 'created_by' => 'required|exists:users,id',
            'bank_branch' => 'required|string|max:255',
            'phone_no' => 'required|string|max:20',
            'representative' => 'required|string|max:255',
            'applicant_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'apartment_name' => 'nullable|string|max:255',
            'holding_no' => 'nullable|string|max:255',
            'road' => 'nullable|string|max:255',
            'post_office' => 'nullable|string|max:255',
            'police_station' => 'nullable|string|max:255',
            'pin_code' => 'nullable|string|max:10',
            'ward' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'authority' => 'nullable|string|max:255',

            // Boundaries and Dimensions
            'boundary_flat_actual_north' => 'nullable|string|max:255',
            'boundary_flat_actual_south' => 'nullable|string|max:255',
            'boundary_flat_actual_east' => 'nullable|string|max:255',
            'boundary_flat_actual_west' => 'nullable|string|max:255',
            'boundary_flat_deed_north' => 'nullable|string|max:255',
            'boundary_flat_deed_south' => 'nullable|string|max:255',
            'boundary_flat_deed_east' => 'nullable|string|max:255',
            'boundary_flat_deed_west' => 'nullable|string|max:255',
            'boundary_building_actual_north' => 'nullable|string|max:255',
            'boundary_building_actual_south' => 'nullable|string|max:255',
            'boundary_building_actual_east' => 'nullable|string|max:255',
            'boundary_building_actual_west' => 'nullable|string|max:255',
            'boundary_building_deed_north' => 'nullable|string|max:255',
            'boundary_building_deed_south' => 'nullable|string|max:255',
            'boundary_building_deed_east' => 'nullable|string|max:255',
            'boundary_building_deed_west' => 'nullable|string|max:255',
            'dimensions_flat_actual_north' => 'nullable|string|max:255',
            'dimensions_flat_actual_south' => 'nullable|string|max:255',
            'dimensions_flat_actual_east' => 'nullable|string|max:255',
            'dimensions_flat_actual_west' => 'nullable|string|max:255',
            'dimensions_flat_deed_north' => 'nullable|string|max:255',
            'dimensions_flat_deed_south' => 'nullable|string|max:255',
            'dimensions_flat_deed_east' => 'nullable|string|max:255',
            'dimensions_flat_deed_west' => 'nullable|string|max:255',

            'property_type' => 'required|in:Flat,Land & Building,Land,Factory,Garage,Shop',
            'nature_property' => 'required|in:Residential,Commercial,Both',
            'flat_no' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'located_at_side' => 'nullable|string|max:255',
            'block' => 'nullable|string|max:255',
            'lift_available' => 'required|in:Yes,No',
            'garage_available' => 'nullable|string|max:255',
            'land_area' => 'nullable|string|max:255',
            'flats_per_floor' => 'nullable|string|max:255',
            'dwelling_unit' => 'nullable|string|max:255',
            'number_of_floors' => 'nullable|string|max:255',
            'super_built_up_area' => 'nullable|string|max:255',
            'occupied_by' => 'required|in:Owner,Tenant,Vacant',
            'year_of_occupancy' => 'nullable|string|max:255',
            'year_of_construction' => 'nullable|string|max:255',
            'light_points' => 'nullable|integer',
            'fan_points' => 'nullable|integer',
            'water_closets' => 'nullable|integer',
            'washbasins' => 'nullable|integer',
            'bathtubs' => 'nullable|integer',
            'plug_points' => 'nullable|integer',
            'door_type' => 'nullable|string|max:255',
            'flooring_type' => 'required|in:Mosaic,Tiles,Marble,Cement',
            'window_type' => 'nullable|string|max:255',
            'wiring_type' => 'required|in:Surface,Concealed',
            'nearest_bus_stand_name' => 'nullable|string|max:255',
            'nearest_bus_stand_distance' => 'nullable|string|max:255',
            'nearest_railway_station_name' => 'nullable|string|max:255',
            'nearest_railway_station_distance' => 'nullable|string|max:255',
            'nearest_landmark_name' => 'nullable|string|max:255',
            'nearest_landmark_distance' => 'nullable|string|max:255',
            'connected_road' => 'nullable|string|max:255',
            'plot_demarcated' => 'required|in:Yes,No',
            'plot_demarcated_description' => 'nullable|string',
            'wall_height' => 'nullable|string|max:255',
            'length' => 'nullable|string|max:255',

            // Image Uploads
            'uploaded_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048000000000000',
        ]);
        

        // Handling Image Uploads
        $uploadedImages = [];
        if ($request->hasFile('uploaded_images')) {
            foreach ($request->file('uploaded_images') as $image) {
                $path = $image->store('inspections', 'public');
                $uploadedImages[] = $path;
            }
        }
        $validatedData['created_by'] = Auth::id();
        $validatedData['work_id'] = $workId;
        $validatedData['uploaded_images'] = $uploadedImages;
        $inspection = Inspection::create($validatedData);
        // dd($validatedData);
        Work::findOrFail($workId)->update(['status' => 'Reporting']);
        
        

        return redirect()->route('inspections.show', $inspection->id)->with('success', 'Inspection created successfully!');
    } 
    catch (\Exception $e) {
         return back()->withInput()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
    }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $inspection = Inspection::findOrFail($id);
        return view('inspections.edit', compact('inspection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                // 'work_id' => 'required|exists:works,id',
            // 'created_by' => 'required|exists:users,id',
            'bank_branch' => 'required|string|max:255',
            'phone_no' => 'required|string|max:20',
            'representative' => 'required|string|max:255',
            'applicant_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'apartment_name' => 'nullable|string|max:255',
            'holding_no' => 'nullable|string|max:255',
            'road' => 'nullable|string|max:255',
            'post_office' => 'nullable|string|max:255',
            'police_station' => 'nullable|string|max:255',
            'pin_code' => 'nullable|string|max:10',
            'ward' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'authority' => 'nullable|string|max:255',

            // Boundaries and Dimensions
            'boundary_flat_actual_north' => 'nullable|string|max:255',
            'boundary_flat_actual_south' => 'nullable|string|max:255',
            'boundary_flat_actual_east' => 'nullable|string|max:255',
            'boundary_flat_actual_west' => 'nullable|string|max:255',
            'boundary_flat_deed_north' => 'nullable|string|max:255',
            'boundary_flat_deed_south' => 'nullable|string|max:255',
            'boundary_flat_deed_east' => 'nullable|string|max:255',
            'boundary_flat_deed_west' => 'nullable|string|max:255',
            'boundary_building_actual_north' => 'nullable|string|max:255',
            'boundary_building_actual_south' => 'nullable|string|max:255',
            'boundary_building_actual_east' => 'nullable|string|max:255',
            'boundary_building_actual_west' => 'nullable|string|max:255',
            'boundary_building_deed_north' => 'nullable|string|max:255',
            'boundary_building_deed_south' => 'nullable|string|max:255',
            'boundary_building_deed_east' => 'nullable|string|max:255',
            'boundary_building_deed_west' => 'nullable|string|max:255',
            'dimensions_flat_actual_north' => 'nullable|string|max:255',
            'dimensions_flat_actual_south' => 'nullable|string|max:255',
            'dimensions_flat_actual_east' => 'nullable|string|max:255',
            'dimensions_flat_actual_west' => 'nullable|string|max:255',
            'dimensions_flat_deed_north' => 'nullable|string|max:255',
            'dimensions_flat_deed_south' => 'nullable|string|max:255',
            'dimensions_flat_deed_east' => 'nullable|string|max:255',
            'dimensions_flat_deed_west' => 'nullable|string|max:255',

            'property_type' => 'required|in:Flat,Land & Building,Land,Factory,Garage,Shop',
            'nature_property' => 'required|in:Residential,Commercial,Both',
            'flat_no' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:255',
            'located_at_side' => 'nullable|string|max:255',
            'block' => 'nullable|string|max:255',
            'lift_available' => 'required|in:Yes,No',
            'garage_available' => 'nullable|string|max:255',
            'land_area' => 'nullable|string|max:255',
            'flats_per_floor' => 'nullable|string|max:255',
            'dwelling_unit' => 'nullable|string|max:255',
            'number_of_floors' => 'nullable|string|max:255',
            'super_built_up_area' => 'nullable|string|max:255',
            'occupied_by' => 'required|in:Owner,Tenant,Vacant',
            'year_of_occupancy' => 'nullable|string|max:255',
            'year_of_construction' => 'nullable|string|max:255',
            'light_points' => 'nullable|integer',
            'fan_points' => 'nullable|integer',
            'water_closets' => 'nullable|integer',
            'washbasins' => 'nullable|integer',
            'bathtubs' => 'nullable|integer',
            'plug_points' => 'nullable|integer',
            'door_type' => 'nullable|string|max:255',
            'flooring_type' => 'required|in:Mosaic,Tiles,Marble,Cement',
            'window_type' => 'nullable|string|max:255',
            'wiring_type' => 'required|in:Surface,Concealed',
            'nearest_bus_stand_name' => 'nullable|string|max:255',
            'nearest_bus_stand_distance' => 'nullable|string|max:255',
            'nearest_railway_station_name' => 'nullable|string|max:255',
            'nearest_railway_station_distance' => 'nullable|string|max:255',
            'nearest_landmark_name' => 'nullable|string|max:255',
            'nearest_landmark_distance' => 'nullable|string|max:255',
            'connected_road' => 'nullable|string|max:255',
            'plot_demarcated' => 'required|in:Yes,No',
            'plot_demarcated_description' => 'nullable|string',
            'wall_height' => 'nullable|string|max:255',
            'length' => 'nullable|string|max:255',

            // Image Uploads
            'uploaded_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048000000000000',
            ]);

            // Handling Image Uploads
            $uploadedImages = [];
            if ($request->hasFile('uploaded_images')) {
                foreach ($request->file('uploaded_images') as $image) {
                    $path = $image->store('inspections', 'public');
                    $uploadedImages[] = $path;
                }
            }

            $inspection = Inspection::findOrFail($id);
            $validatedData['uploaded_images'] = $uploadedImages;
            $inspection->update($validatedData);

            return redirect()->route('inspections.show', $inspection->id)->with('success', 'Inspection updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}

