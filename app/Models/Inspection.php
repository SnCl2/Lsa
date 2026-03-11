<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Work;
use App\Models\User;

class Inspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_id',
        'created_by',
        'bank_branch',
        'phone_no',
        'representative',
        'applicant_name',
        'address',
        'apartment_name',
        'holding_no',
        'road',
        'post_office',
        'police_station',
        'pin_code',
        'ward',
        'district',
        'authority',

        'boundary_flat_actual_north', 'boundary_flat_actual_south', 'boundary_flat_actual_east', 'boundary_flat_actual_west',
        'boundary_flat_deed_north', 'boundary_flat_deed_south', 'boundary_flat_deed_east', 'boundary_flat_deed_west',
        'boundary_building_actual_north', 'boundary_building_actual_south', 'boundary_building_actual_east', 'boundary_building_actual_west',
        'boundary_building_deed_north', 'boundary_building_deed_south', 'boundary_building_deed_east', 'boundary_building_deed_west',
        'dimensions_flat_actual_north', 'dimensions_flat_actual_south', 'dimensions_flat_actual_east', 'dimensions_flat_actual_west',
        'dimensions_flat_deed_north', 'dimensions_flat_deed_south', 'dimensions_flat_deed_east', 'dimensions_flat_deed_west',


        'property_type',
        'nature_property',
        'flat_no',
        'floor',
        'located_at_side',
        'block',
        'lift_available',
        'garage_available',
        'land_area',
        'flats_per_floor',
        'dwelling_unit',
        'number_of_floors',
        'super_built_up_area',
        'occupied_by',
        'year_of_occupancy',
        'year_of_construction',
        'light_points',
        'fan_points',
        'water_closets',
        'washbasins',
        'bathtubs',
        'plug_points',
        'door_type',
        'flooring_type',
        'window_type',
        'wiring_type',
        'nearest_bus_stand_name',
        'nearest_bus_stand_distance',
        'nearest_railway_station_name',
        'nearest_railway_station_distance',
        'nearest_landmark_name',
        'nearest_landmark_distance',
        'connected_road',
        'plot_demarcated',
        'plot_demarcated_description',
        'wall_height',
        'length',
        'uploaded_images',
    ];

    protected $casts = [
        'uploaded_images' => 'array',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
