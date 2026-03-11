<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_id');
            $table->unsignedBigInteger('created_by');
            
            // Inspector & Applicant Details
            $table->string('bank_branch');
            $table->string('phone_no');
            $table->string('representative');
            $table->string('applicant_name');
            
            // Property Address
            $table->string('address');
            $table->string('apartment_name')->nullable();
            $table->string('holding_no')->nullable();
            $table->string('road')->nullable();
            $table->string('post_office')->nullable();
            $table->string('police_station')->nullable();
            $table->string('pin_code')->nullable();
            $table->string('ward')->nullable();
            $table->string('district')->nullable();
            $table->string('authority')->nullable();
            
            // Boundaries and Dimensions
            $fields = ['boundary_flat_actual', 'boundary_flat_deed', 'boundary_building_actual', 'boundary_building_deed',
                       'dimensions_flat_actual', 'dimensions_flat_deed'];
            $directions = ['north', 'south', 'east', 'west'];
            foreach ($fields as $field) {
                foreach ($directions as $dir) {
                    $table->string("{$field}_{$dir}")->nullable();
                }
            }
            
            // Flat Details
            $table->enum('property_type', ['Flat', 'Land & Building', 'Land', 'Factory', 'Garage', 'Shop']);
            $table->enum('nature_property', ['Residential', 'Commercial', 'Both']);
            $table->string('flat_no')->nullable();
            $table->string('floor')->nullable();
            $table->string('located_at_side')->nullable();
            $table->string('block')->nullable();
            $table->enum('lift_available', ['Yes', 'No']);
            $table->enum('garage_available', ['Yes', 'No']);
            $table->string('land_area')->nullable();
            $table->string('flats_per_floor')->nullable();
            $table->string('dwelling_unit')->nullable();
            $table->string('number_of_floors')->nullable();
            $table->string('super_built_up_area')->nullable();
            $table->enum('occupied_by', ['Owner', 'Tenant', 'Vacant']);
            $table->string('year_of_occupancy')->nullable();
            $table->string('year_of_construction')->nullable();
            
            // Utility Details
            $table->integer('light_points')->nullable();
            $table->integer('fan_points')->nullable();
            $table->integer('water_closets')->nullable();
            $table->integer('washbasins')->nullable();
            $table->integer('bathtubs')->nullable();
            $table->integer('plug_points')->nullable();
            $table->string('door_type')->nullable();
            $table->enum('flooring_type', ['Mosaic', 'Tiles', 'Marble', 'Cement']);
            $table->string('window_type')->nullable();
            $table->enum('wiring_type', ['Surface', 'Concealed']);
            
            // Nearest Locations
            $table->string('nearest_bus_stand_name')->nullable();
            $table->string('nearest_bus_stand_distance')->nullable();
            $table->string('nearest_railway_station_name')->nullable();
            $table->string('nearest_railway_station_distance')->nullable();
            $table->string('nearest_landmark_name')->nullable();
            $table->string('nearest_landmark_distance')->nullable();
            $table->string('connected_road')->nullable();
            
            // Additional Details
            $table->enum('plot_demarcated', ['Yes', 'No']);
            $table->text('plot_demarcated_description')->nullable();
            $table->string('wall_height')->nullable();
            $table->string('length')->nullable();
            
            // Image Upload
            $table->json('uploaded_images')->nullable();
            
            // Foreign Keys
            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            // Timestamps
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inspections');
    }
};
