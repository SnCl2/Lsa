@extends('layouts.app')

@section('content')

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center mt-4">
    <div class="w-100 h-100">
        @include('reports.lnb_show')
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    // updateOutput("#cs_dag_no", "#cs_dag_no_out");
    // updateOutput("#rs_dag_no", "#rs_dag_no_out");
    updateOutput("#dag_no", "#dag_no_out");
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






@endsection
