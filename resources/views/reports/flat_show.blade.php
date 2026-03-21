
<div class="card shadow-sm p-3" id="Report_layout">
                <h4>Output</h4>
                <div id="outputArea" class="border p-3">
                    Ref.: KKDA/SBI/<span class="text-justify" id="racpc_out1">........</span>/RACPC/<span class="text-justify" id="currentMonth">.....</span>/{{$work->id}}/<span class="text-justify" id="currentYear">.....</span>-<span class="text-justify" id="currentDate">.....</span>
                    <div>

                        TO, <br>
                        ASSISTANT GENERAL MANAGER, <br>
                        STATE BANK OF INDIA <br>
                        RACPC, <span class="text-justify" id="racpc_out2" >........</span>,

                    </div>
                    <div>
                        <div class="text-center font-weight-bold">
                            VALUATION REPORT (IN RESPECT OF FLAT)
                        </div>
                        <span class="text-justify" id="report_description_out">......</span>
                    </div>
                    <div>
                        <div class="text-center font-weight-bold">
                            ::OWNER::
                        </div>
                        <span class="text-justify" id="OWNER_out">......</span>
                    </div>
                    <div class="mb-3">
                        <div class="text-center font-weight-bold">
                            ::PURCHASER/BORROWER::
                        </div>
                        <span class="text-justify" id="PURCHASER_BORROWER_out">......</span>

                    </div>

                    <div>
                        <table class="table table-bordered ">
                            <thead class="thead-light">

                                <tr>
                                    <th class="font-weight-bold">
                                        I.
                                    </th>
                                    <th colspan="2" class="font-weight-bold"> GENERAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1.</td>
                                    <td class="font-weight-bold"> PURPOSE FOR WHICH THE VALUATION IS MADE</td>
                                    <td>
                                        <span class="text-justify" id="purpose_out">......</span>
                                    </td>
                                </tr>
                                <tr><td rowspan="2">2</td>
                                    <td class="font-weight-bold">A) DATE OF INSPECTION</td>
                                    <td><span class="text-justify" id="inspection_date_out"></span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">B) DATE ON WHICH THE VALUATION IS MADE</td>
                                    <td><span class="text-justify" id="valuation_date_out"></span></td>
                                </tr>
                                @php
                                    $validDocumentsCount = $work->documents->where('date_of_issue', '!=', '2000-01-01')->count();
                                @endphp
                                <tr>
                                    <td rowspan="{{$validDocumentsCount+1}}">3</td>
                                    <td class="font-weight-bold" colspan="1"> LIST OF DOCUMENTS PRODUCED FOR PERUSAL</td>
                                    <td><span class="text-justify" id="list_of_doc_out"></span></td>
                                </tr>
                                @foreach ($work->documents as $index => $document)
                                    @if ($document->date_of_issue != '2000-01-01')
                                        <tr>
                                            <td class="pl-4">{{  $index + 1}})</td> {{-- I), II), III) ... --}}
                                            <td>XEROX COPY OF {{ $document->document_name }} - Date of Issue: {{ $document->date_of_issue }}</td>

                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td>4</td>
                                    <td class="font-weight-bold"> NAME OF THE OWNER(S)/ <span class="text-justify" class="font-weight-bold">BORROWERS</span> AND HIS / THEIR ADDRESS (ES) WITH
                                        PHONE NO. (DETAILS OF SHARE OF EACH
                                        OWNER IN CASE OF JOINT OWNERSHIP) </td>
                                    <td><div class="text-center">
                                        ::OWNER::
                                    </div>
                                     <br>
                                     <span class="text-justify" id="OWNER_out2">......</span>
                                     <br>
                                     <div class="text-center">
                                        ::PURCHASER/BORROWER::
                                    </div>
                                    <br><span class="text-justify" id="PURCHASER_BORROWERS_out3">......</span> </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td class="font-weight-bold"> BRIEF DESCRIPTION OF THE PROPERTY
                                        (INCLUDING LEASEHOLD / FREEHOLD ETC.)  </td>
                                    <td>
                                        <span class="text-justify" id="report_description_out2">......</span>
                                    </td>
                                </tr>
                                <td rowspan="10">6</td>
                                <td class="font-weight-bold"> LOCATION OF PROPERTY    </td>

                                </tr>
                                <tr>
                                    <td class="font-weight-bold">a) Plot No. / Survey No.</td>
                                    <td>
                                            <span class="text-justify" id="dag_no_out">.....</span> <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">b) Door No.</td>
                                    <td><span class="text-justify" id="door_no_out">......</span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">c) T. S. No. / Village/ Mouza</td>
                                    <td><span class="text-justify" id="village_mouza_out">......</span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">d) Ward / Taluka</td>
                                    <td><span class="text-justify" id="ward_taluka_out">......</span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">e) Mandal / District</td>
                                    <td><span class="text-justify" id="mandal_district_out">......</span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">f) Date of Issue and Validity of Layout of Approved / Plan</td>
                                    <td>
                                        <span class="text-justify" id="layout_validity_out">......</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">g) Approved map/Plan Issuing Authority</td>
                                    <td><span class="text-justify" id="approved_authority_out">......</span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">h) Whether genuineness or authenticity of approved map/plan is verified.</td>
                                    <td><span class="text-justify" id="authenticity_verified_out">......</span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">i) Any other Comments by our empanelled valuers on authentic of approved plan.</td>
                                    <td><span class="text-justify" id="valuer_comments_out">......</span></td>
                                </tr>


                                <tr>
                                    <td>7</td>
                                    <td class="font-weight-bold"> POSTAL ADDRESS OF THE PROPERTY  </td>
                                <td>
                                    <span class="text-justify" id="postal_address_out" >..........</span>
                                </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold" rowspan="4">8.</td>
                                    <td class="font-weight-bold">CITY / TOWN  </td>
                                <td>
                                    <span class="text-justify" id="city_town_out" >..........</span>

                                </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold">RESIDENTIAL AREA</td>
                                <td>
                                    <span class="text-justify" id="residential_area_out" >..........</span>

                                </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold">COMMERCIAL AREA</td>
                                <td>
                                    <span class="text-justify" id="commercial_area_out" >..........</span>

                                </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold">INDUSTRIAL AREA</td>
                                <td>
                                    <span class="text-justify" id="industrial_area_out" >..........</span>

                                </td>
                                </tr>
                                <tr>
                                    <td rowspan="3">9</td>
                                    <td class="font-weight-bold" colspan="2"> CLASSIFICATION OF THE AREA  </td>
                                
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">HIGH / MIDDLE / POOR</td>
                                <td>
                                    <span class="text-justify" id="high_middle_poor_out" >..........</span>


                                </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold">URBAN / SEMI URBAN / RURAL </td>
                                <td>
                                    <span class="text-justify" id="urban_semiurban_rural_out" >..........</span>

                                </td>
                                </tr>

                                <tr>
                                    <td>10</td>
                                    <td class="font-weight-bold"> COMING UNDER CORPORATION LIMIT /
                                        VILLAGE PANCHAYET / MUNICIPALITY/
                                        CORPORATION   </td>
                                    <td>
                                        <span class="text-justify" id="corporation_limit_out" >..........</span>

                                    </td>
                                </tr>

                                <tr>
                                    <td>11</td>
                                    <td class="font-weight-bold"> WHETHER COVERED UNDER ANY STATE /
                                        CENTRAL GOVT. ENACTMENTS (E.G. URBAN
                                        LAND CEILING ACT) OR NOTIFIED UNDER
                                        AGENCY AREA / SCHEDULED AREA /
                                        CANTONMENT AREA.</td>
                                    <td>
                                        <span  id="govt_enactments_out" >..........</span>

                                    </td>
                                </tr>
                                <!-- ------------------------------------------ -->

                            </tbody>
                        </table>
                        <table class="table table-bordered  mt-3">
                            <tbody>
                                <!-- 12 -->
                                <tr>
                                    <td rowspan="5" >12</td>
                                    <td colspan="2" class="font-weight-bold">BOUNDARIES OF THE PROPERTY(AS PER
                                        AGREEMENT) </td>
                                    <td class="font-weight-bold">BUILDING (ACTUAL)</td>
                                    <td class="font-weight-bold">FLAT (ACTUAL) </td>
                                </tr>
                                <tr>
                                    <td>NORTH</td>
                                    <td><span  id="north_agreement_out" >..........</span></td>
                                    <td><span  id="north_building_out" >..........</span></td>
                                    <td><span  id="north_flat_out" >..........</span></td>
                                </tr>
                                <tr>
                                    <td>SOUTH</td>
                                    <td><span  id="south_agreement_out" >..........</span></td>
                                    <td><span  id="south_building_out" >..........</span></td>
                                    <td><span  id="south_flat_out" >..........</span></td>
                                </tr>
                                <tr>
                                    <td>EAST</td>
                                    <td><span  id="east_agreement_out" >..........</span></td>
                                    <td><span  id="east_building_out" >..........</span></td>
                                    <td><span  id="east_flat_out" >..........</span></td>
                                </tr>
                                <tr>
                                    <td>WEST</td>
                                    <td><span  id="west_agreement_out" >..........</span></td>
                                    <td><span  id="west_building_out" >..........</span></td>
                                    <td><span  id="west_flat_out" >..........</span></td>
                                </tr>
                                <!-- 13 -->

                                <tr>
                                    <td rowspan="5" >13</td>
                                    <td colspan="2" class="font-weight-bold">DIMENSION OF THE PROPERTY </td>
                                    <td class="font-weight-bold">AS PER PLAN
                                        (SQ.FT./SQ.MTR.) </td>
                                    <td class="font-weight-bold">ACTUAL
                                        (SQ.FT./SQ.MTR.) </td>
                                </tr>
                                <tr>
                                    <td>NORTH</td>
                                    <td><span  id="north_DIMENSION_out" >..........</span></td>
                                    <td><span  id="north_PLAN_out" >..........</span></td>
                                    <td><span  id="north_ACTUAL_out" >..........</span></td>
                                </tr>
                                <tr>
                                    <td>SOUTH</td>
                                    <td><span  id="south_DIMENSION_out" >..........</span></td>
                                    <td><span  id="south_PLAN_out" >..........</span></td>
                                    <td><span  id="south_ACTUAL_out" >..........</span></td>
                                </tr>
                                <tr>
                                    <td>EAST</td>
                                    <td><span  id="east_DIMENSION_out" >..........</span></td>
                                    <td><span  id="east_PLAN_out" >..........</span></td>
                                    <td><span  id="east_ACTUAL_out" >..........</span></td>
                                </tr>
                                <tr>
                                    <td>WEST</td>
                                    <td><span  id="west_DIMENSION_out" >..........</span></td>
                                    <td><span  id="west_PLAN_out" >..........</span></td>
                                    <td><span  id="west_ACTUAL_out" >..........</span></td>
                                </tr>
                                <!-- 14 -->
                                 <tr>
                                    <td>14</td>
                                    <td colspan="2" class="font-weight-bold">LATITUDE, LONGITUDE AND COORDINATES OF
                                        THE SITE  </td>
                                    <td colspan="2">Latitude: <span id="latitude_out">.......</span> & Longitude: <span id="longitude_out">.......</span></td>

                                </tr>
                                 <!-- 15 -->
                                <tr>
                                    <td >15</td>
                                    <td colspan="2"  class="font-weight-bold">EXTENT OF THE SITE</td>
                                    <td> <span  id="EXTENT_SITE_out" >..........</span></td>
                                    <td> <span  id="EXTENT_SITE_unit_out" >..........</span></td>
                                </tr>

                                <!-- 16 -->
                                <tr>
                                    <td >16</td>
                                    <td colspan="2"  class="font-weight-bold">EXTENT OF THE SITE CONSIDERED FOR
                                        VALUATION (LEAST OF 14 A & 14 B)</td>
                                    <td> <span  id="EXTENT_SITE_2_out" >..........</span></td>
                                    <td> <span  id="EXTENT_SITE_unit_2_out" >..........</span></td>
                                </tr>

                                <!-- 17 -->

                                 <tr>
                                    <td>17</td>
                                    <td colspan="2" class="font-weight-bold">Whether occupied by the owner / tenant?  If
                                        occupied by tenant, since how long? Rent
                                        received per month.   </td>
                                    <td colspan="2"> <span  id="occupied_by_out" >..........</span></td>

                                </tr>
                            </tbody>

                        </table>
                                <table class="table table-bordered ">
                                    <thead class="thead-light">

                                        <tr>
                                            <th class="font-weight-bold">
                                                II.
                                            </th>
                                            <th colspan="2" class="font-weight-bold"> APARTMENT BUILDING  </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td class="font-weight-bold"> Nature of the Apartment  </td>
                                            <td> <span  id="nature_out" >..........</span></td>
                                        </tr>
                                        <!-- 2 -->
                                        <tr>
                                            <td rowspan="8">2</td>
                                            <td  colspan="2"> Location.  </td>
                                        </tr>
                                        <tr>
                                            <td >T. S. No. </td>
                                            <td> <span  id="tsno_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td >Block No.  </td>
                                            <td> <span  id="block_no_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td >Ward No.    </td>
                                            <td> <span  id="ward_no_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td >Village/ Municipality / Corporation  </td>
                                            <td> <span  id="Village_Municipality_Corporation_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td >Door No., Street or Road (Pin Code)  </td>
                                            <td> <span  id="door_street_road_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td >Feasibility to the Civic amenities like school,
                                                hospital, bus stop, market etc.  </td>
                                            <td> <span  id="Feasibility_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td >Road Facility  </td>
                                            <td> <span  id="Facility_out" >..........</span></td>
                                        </tr>
                                        <!-- 3 -->
                                        <tr>
                                            <td>3</td>
                                            <td class="font-weight-bold"> Description of the locality Residential /
                                                Commercial / Mixed    </td>
                                            <td> <span  id="Description_locality_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td class="font-weight-bold"> Year of Construction  </td>
                                            <td> <span  id="Year_of_Construction_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td class="font-weight-bold"> Number of Floors  </td>
                                            <td> <span  id="Number_of_Floors_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td class="font-weight-bold"> Type of Structure   </td>
                                            <td> <span  id="Type_of_Structure_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td class="font-weight-bold"> Number of Dwelling units in the building  </td>
                                            <td> <span  id="number_unit_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td class="font-weight-bold"> Quality of Construction  </td>
                                            <td> <span  id="QUALITY_OF_CONSTRUCTION_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td class="font-weight-bold"> Appearance of the Building  </td>
                                            <td> <span  id="APPEARANCE_OF_THE_BUILDING_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td class="font-weight-bold"> Maintenance of the Building  </td>
                                            <td> <span  id="MAINTENANCE_OF_THE_BUILDING_out" >..........</span></td>
                                        </tr>
                                        <!-- 11 -->
                                        <tr>
                                            <td rowspan="7">11</td>
                                            <td calss="font-weight-bold" colspan="2" > Facilities Available.  </td>
                                        </tr>
                                        <tr>
                                            <td >Lift  </td>
                                            <td> <span  id="lift_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td >Protected Water Supply  </td>
                                            <td> <span  id="water_supply_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td >Underground Sewerage  </td>
                                            <td> <span  id="sewerage_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td >Car Parking - Open/ Covered  </td>
                                            <td> <span  id="car_parking_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td >Is  Compound wall existing?  </td>
                                            <td> <span  id="compound_wall_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td >Is pavement laid around the Building   </td>
                                            <td> <span  id="pavement_out" >..........</span></td>
                                        </tr>

                                    </tbody>

                                </table>
                                <table class="table table-bordered ">
                                    <thead class="thead-light">

                                        <tr>
                                            <th class="font-weight-bold">
                                                III.
                                            </th>
                                            <th colspan="2" class="font-weight-bold"> Flat  </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td class="font-weight-bold"> THE FLOOR ON WHICH THE FLAT IS
                                                SITUATED    </td>
                                            <td> <span  id="floor_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="font-weight-bold"> DOOR NO. OF THE FLAT     </td>
                                            <td> <span  id="door_no_2_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td rowspan="8">3</td>
                                            <td class="font-weight-bold" colspan="2"> SPECIFICATIONS OF THE FLAT      </td>

                                        </tr>
                                        <tr>

                                            <td class="font-weight-bold"> Roof </td>
                                            <td> <span  id="roof_out" >..........</span></td>
                                        </tr>
                                        <tr>

                                            <td class="font-weight-bold"> FLOORING </td>
                                            <td> <span  id="FLOORING_out" >..........</span></td>
                                        </tr>
                                        <tr>

                                            <td class="font-weight-bold"> DOORS </td>
                                            <td> <span  id="door_type_out" >..........</span></td>
                                        </tr>
                                        <tr>

                                            <td class="font-weight-bold"> WINDOWS </td>
                                            <td> <span  id="window_type_out" >..........</span></td>
                                        </tr>
                                        <tr>

                                            <td class="font-weight-bold"> FITTINGS </td>
                                            <td> <span  id="fittings_out" >..........</span></td>
                                        </tr>
                                        <tr>

                                            <td class="font-weight-bold"> FINISHING </td>
                                            <td> <span  id="finishing_out" >..........</span></td>
                                        </tr>
                                        <tr>

                                            <td class="font-weight-bold"> ACCOMMODATION  </td>
                                            <td>
                                                <span  id="ACCOMMODATION_out" >..........</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td rowspan="4">4</td>
                                            <td class="font-weight-bold"> House Tax</td>
                                            <td> <span  id="house_tax_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold"> Assessment No. </td>
                                            <td> <span  id="assessment_no_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold"> Tax paid in the name of </td>
                                            <td> <span  id="tax_paid_name_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold"> Tax amount  </td>
                                            <td> <span  id="tax_amount_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2">5</td>
                                            <td class="font-weight-bold"> Electricity Service Connection no. </td>
                                            <td> <span  id="electricity_connection_no_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold"> Meter Card is in the name of </td>
                                            <td> <span  id="meter_card_name_out" >..........</span></td>

                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td class="font-weight-bold">How is the maintenance of the flat? </td>
                                            <td><span  id="flat_maintenance_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td class="font-weight-bold">Sale Agreement  executed in the name of </td>
                                            <td><span  id="sale_agreement_name_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td class="font-weight-bold">What is the undivided area of land as per
                                                Sale Agreement ? </td>
                                            <td><span  id="undivided_area_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td class="font-weight-bold">What is the plinth area of the flat?   </td>
                                            <td><span  id="plinth_area_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td class="font-weight-bold">What is the floor space index (app.)  </td>
                                            <td><span  id="floor_space_index_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td class="font-weight-bold">What is the Carpet Area of the flat? </td>
                                            <td><span  id="carpet_area_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td class="font-weight-bold">Is it Posh/ I class / Medium / Ordinary? </td>
                                            <td><span  id="flat_class_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>13</td>
                                            <td class="font-weight-bold">Is it being used for Residential or
                                                Commercial purpose? </td>
                                            <td><span  id="usage_purpose_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>14</td>
                                            <td class="font-weight-bold">Is it Owner-occupied or let out? </td>
                                            <td><span  id="occupancy_status_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>15</td>
                                            <td class="font-weight-bold">If rented, what is the monthly rent? </td>
                                            <td><span  id="monthly_rent_out" >..........</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="font-weight-bold">
                                                IV.
                                            </th>
                                            <th colspan="2" class="font-weight-bold"> MARKETABILITY   </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td class="font-weight-bold"> How is the marketability?   </td>
                                            <td> <span  id="marketability_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="font-weight-bold"> What are the factors favouring for an extra
                                                Potential Value?   </td>
                                            <td> <span  id="extra_potential_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td class="font-weight-bold"> Any negative factors are observed which
                                                affect the market value in general?    </td>
                                            <td> <span  id="negative_factors_out" >..........</span></td>
                                        </tr>
                                </table>
                                <table class="table table-bordered ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="font-weight-bold">
                                                V.
                                            </th>
                                            <th colspan="2" class="font-weight-bold"> Rate   </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td class="font-weight-bold"> After analysing the comparable
                                                sale instances, what is the
                                                composite rate for a similar flat
                                                with same specifications in the
                                                adjoining locality? - (Along with
                                                details /reference of at-least
                                                two latest deals/transactions
                                                with respect to adjacent
                                                properties in the areas)   </td>
                                            <td> <span  id="composite_rate_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="font-weight-bold"> Assuming it is a new
                                                construction, what is the
                                                adopted basic composite rate of
                                                the flat under valuation after
                                                comparing with the
                                                specifications and other factors
                                                with the flat under comparison
                                                (give details).    </td>
                                            <td> <span  id="adopted_rate_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td rowspan="3">3</td>
                                            <td class="font-weight-bold" colspan="2"> Break - up for the rate      </td>

                                        </tr>
                                        <tr>

                                            <td class="font-weight-bold"> i)  Building + Services  </td>
                                            <td> <span  id="building_services_out" >..........</span></td>
                                        </tr>
                                        <tr>

                                            <td class="font-weight-bold"> ii)  Land + Others  </td>
                                            <td> <span  id="land_others_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="font-weight-bold"> Guideline rate obtained from the
                                                Registrar's office (an evidence
                                                thereof to be enclosed)</td>
                                            <td> <span  id="guideline_rate_out" >..........</span></td>
                                        </tr>
                                </table>
                                <table class="table table-bordered ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="font-weight-bold">
                                                VI.
                                            </th>
                                            <th colspan="2" class="font-weight-bold"> COMPOSITE RATE ADOPTED AFTER COMPLETION:    </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td rowspan="6">A</td>
                                            <td class="font-weight-bold">Depreciated building rate</td>
                                            <td><span  id="depreciated_building_rate_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Replacement cost of flat with Services {V
                                                (3)i} </td>
                                            <td><span  id="replacement_cost_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Age of the building </td>
                                            <td><span  id="building_age_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Life of the building estimated  </td>
                                            <td><span  id="building_life_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Depreciation percentage assuming the
                                                salvage value as 10% </td>
                                            <td><span  id="depreciation_percentage_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Depreciated Ratio of the building </td>
                                            <td><span  id="depreciated_ratio_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td rowspan="4">B</td>
                                            <td class="font-weight-bold" colspan="2">Total composite rate arrived for valuation</td>

                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Depreciated building rate VI (a)</td>
                                            <td><span  id="depreciated_building_rate_total_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Rate for Land & other V (3)ii</td>
                                            <td><span  id="rate_land_other_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td class="font-weight-bold">Total Composite Rate </td>
                                            <td><span  id="total_composite_out" >..........</span></td>
                                        </tr>


                                    </tbody>
                                </table>
                                <table class="table table-bordered ">
                                    <thead class="thead-light">
                                        <tr>

                                            <th colspan="5" class="font-weight-bold"> VALUATION </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                SR.NO.
                                            </th>
                                            <th>
                                                Description
                                            </th>
                                            <th>
                                                Qty.
                                            </th>
                                            <th>
                                                RATE PER UNIT
                                            </th>
                                            <th>
                                                ESTIMATED VALUE RS.
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>After completion value of the flat  </td>
                                            <td><span  id="qty_1_out" >..........</span></td>
                                            <td><span  id="rate_1_out" >..........</span></td>
                                            <td><span  id="value_1_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Wardrobes  </td>
                                            <td><span  id="qty_2_out" >..........</span></td>
                                            <td><span  id="rate_2_out" >..........</span></td>
                                            <td><span  id="value_2_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Showcases  </td>
                                            <td><span  id="qty_3_out" >..........</span></td>
                                            <td><span  id="rate_3_out" >..........</span></td>
                                            <td><span  id="value_3_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Kitchen Arrangements  & wardrobe    </td>
                                            <td><span  id="qty_4_out" >..........</span></td>
                                            <td><span  id="rate_4_out" >..........</span></td>
                                            <td><span  id="value_4_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Superfine Finish  </td>
                                            <td><span  id="qty_5_out" >..........</span></td>
                                            <td><span  id="rate_5_out" >..........</span></td>
                                            <td><span  id="value_5_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Interior Decorations  </td>
                                            <td><span  id="qty_6_out" >..........</span></td>
                                            <td><span  id="rate_6_out" >..........</span></td>
                                            <td><span  id="value_6_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>Electricity deposits / electrical fittings,
                                                etc.,  </td>
                                            <td><span  id="qty_7_out" >..........</span></td>
                                            <td><span  id="rate_7_out" >..........</span></td>
                                            <td><span  id="value_7_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>Extra collapsible gates / grill works etc., </td>
                                            <td><span  id="qty_8_out" >..........</span></td>
                                            <td><span  id="rate_8_out" >..........</span></td>
                                            <td><span  id="value_8_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>Potential value, if any</td>
                                            <td><span  id="qty_9_out" >..........</span></td>
                                            <td><span  id="rate_9_out" >..........</span></td>
                                            <td><span  id="value_9_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>Two Covered Car Parking</td>
                                            <td><span  id="qty_10_out" >..........</span></td>
                                            <td><span  id="rate_10_out" >..........</span></td>
                                            <td><span  id="value_10_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Total</td>
                                            <td></td>
                                            <td></td>
                                            <td><span  id="total_value_out" >..........</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Rounded Off</td>
                                            <td><span  id="rounded_value_out" >..........</span></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div >
                                    <div class="font-weight-bold text-center">
                                        :PRESENT VALUE OF THE SAID FLAT:
                                    </div>

                                    <p style="text-align: justify;"> AS A RESULT OF MY APPRAISAL AND ANALYSIS, IT IS MY CONSIDERED OPINION THAT THE</p>


                                    <p style="text-align: justify;"> FAIR MARKET VALUE OF THE ABOVE PROPERTY IS RS.16,05,000.00 (RUPEES SIXTEEN  LAKHS FIVE THOUSAND
                                    ONLY).
                                    REALIZABLE VALUE OF THE ABOVE PROPERTY IS RS.16,05,000.00 (RUPEES SIXTEEN  LAKHS FIVE THOUSAND
                                    ONLY).(DEDUCT 10% FROM FAIR MARKET VALUE)
                                    THE DISTRESS VALUE OF THE ABOVE PROPERTY IS RS. 12,84,000.00 (RUPEES TWELVE LAKH EIGHTY FOUR
                                    THOUSAND AND ONLY).(DEDUCT 20% FROM FAIR MARKET VALUE)</p>

                                </div>
                                <div >
                                    <div class="font-weight-bold text-center">
                                        : FUTURE VALUE OF THE SAID FLAT:
                                    </div>

                                    <p style="text-align: justify;"> AS A RESULT OF MY APPRAISAL AND ANALYSIS, IT IS MY CONSIDERED OPINION THAT THE</p>


                                    <p style="text-align: justify;"> FAIR MARKET VALUE OF THE ABOVE PROPERTY IS RS.16,05,000.00 (RUPEES SIXTEEN  LAKHS FIVE THOUSAND
                                    ONLY).
                                    REALIZABLE VALUE OF THE ABOVE PROPERTY IS RS.16,05,000.00 (RUPEES SIXTEEN  LAKHS FIVE THOUSAND
                                    ONLY).(DEDUCT 10% FROM FAIR MARKET VALUE)
                                    THE DISTRESS VALUE OF THE ABOVE PROPERTY IS RS. 12,84,000.00 (RUPEES TWELVE LAKH EIGHTY FOUR
                                    THOUSAND AND ONLY).(DEDUCT 20% FROM FAIR MARKET VALUE)</p>

                                </div>
                                <div>
                                    <p class="font-weight-bold">Place: Kolkata</p>
                                    <p class="font-weight-bold">Date: Input</p>
                                </div>
                                <div >

                                    <p style="text-align: justify;"> THE UNDERSIGNED HAS INSPECTED THE PROPERTY DETAILED IN THE VALUATION REPORT
                                        DATED 01.03.2025 WE ARE SATISFIED THAT THE FAIR AND REASONABLE MARKET VALUE OF
                                        THE PROPERTY IS
                                        RS............................................(RUPEES.....................................................................................................................................
                                         ........................................................................)</p>

                                </div>
                                <div>
                                    <p class="text-center">
                                        SIGNATURE <br>
                                        <br>
                                        (SAME OF THE BRANCH MANAGER WITH OFFICIAL SEAL WITH DATE)
                                    </p>
                                </div>
                                <div class="font-weight-bold">

                                    ENCLOSE:  <br>
                                    TO BE OBTAINED FROM VALUERS ALONGWITH THE VALUATION REPORT.  <br>
                                    1. DECLARATION-CUM-UNDERTAKING FROM THE VALUER (ANNEXURE-I) <br>
                                    2. MODEL CODE OF CONDUCT FOR VALUER (ANNEXURE – II) <br>

                                </div>
                                <div class="font-weight-bold">
                                    <p style="text-align: right;">(ANNEXURE-I)</p>
                                    <p class="text-center font-weight-bold">
                                        FORMAT OF UNDERTAKING TO BE SUBMITTED BY INDIVIDUALS/ PROPRIETOR/ <br>
                                        PARTNERS/ DIRECTORS DECLARATION- CUM- UNDERTAKING  <br>
                                    I,  KOUSHIK KUMAR DAS,   SONOF  LATE SUDHIRRANJAN DAS, DO HEREBY SOLEMNLY AFFIRM AND <br>
                                    </p>
                                    <p>STATE THAT:  </p>
                                </div>
                                <div>
                                    <p style="text-align: justify;">a. I AM A CITIZEN OF INDIA</p>
                                    <p style="text-align: justify;">b. I WILL NOT UNDERTAKE VALUATION OF ANY ASSETS IN WHICH I HAVE A DIRECT OR INDIRECT INTEREST OR BECOME
                                    SO INTERESTED AT ANY TIME DURING A PERIOD OF THREE YEARS PRIOR TO MY APPOINTMENT AS VALUER OR THREE
                                    YEARS AFTER THE VALUATION OF ASSETS WAS CONDUCTED BY ME THE INFORMATION FURNISHED IN MY VALUATION
                                    REPORT DATED 01.03.2025 TRUE AND CORRECT TO THE BEST OF MY KNOWLEDGE AND BELIEF AND I HAVE MADE AN
                                    IMPARTIAL AND TRUE VALUATION OF THE PROPERTY.</p>
                                    <p style="text-align: justify;">c. MY REPRESENTATIVE INSPECTED THE PROPERTY ON 01.03.2025 THE WORK IS NOT SUBCONTRACTED TO ANY OTHER
                                    VALUER AND CARRIED OUT BY MYSELF.</p>
                                    <p style="text-align: justify;">d. VALUATION REPORT IS SUBMITTED IN THE FORMAT AS PRESCRIBED BY THE BANK.</p>
                                    <p style="text-align: justify;">e. I HAVE NOT BEEN DEPANELLED/ DELISTED BY ANY OTHER BANK AND IN CASE ANY SUCH DEPANELMENT BY OTHER
                                    BANKS DURING MY EMPANELMENT WITH YOU, I WILL INFORM YOU WITHIN 3 DAYS OF SUCH DEPANELMENT.</p>
                                    <p style="text-align: justify;">f. I HAVE NOT BEEN REMOVED/DISMISSED FROM SERVICE/EMPLOYMENT EARLIER</p>
                                    <p style="text-align: justify;">g. I HAVE NOT BEEN CONVICTED OF ANY OFFENCE AND SENTENCED TO A TERM OF IMPRISONMENT</p>
                                    <p style="text-align: justify;">h. I HAVE NOT BEEN FOUND GUILTY OF MISCONDUCT IN PROFESSIONAL CAPACITY</p>
                                    <p style="text-align: justify;">i. I HAVE NOT BEEN DECLARED TO BE UNSOUND MIND</p>
                                    <p style="text-align: justify;">j. I AM NOT AN UNDERCHARGED BANKRUPT, OR HAS NOT APPLIED TO BE ADJUDICATED AS A BANKRUPT;</p>
                                    <p style="text-align: justify;">k. I AM NOT AN UNDERCHARGED INSOLVENT</p>
                                    <p style="text-align: justify;">l. I HAVE NOT BEEN LEVIED A PENALTY UNDER SECTION 271J OF INCOME-TAX ACT, 1961 (43 OF 1961) AND TIME LIMIT
                                    FOR FILING APPEAL BEFORE COMMISSIONER OF INCOME TAX (APPEALS) OR INCOME-TAX APPELLATE TRIBUNAL, AS THE
                                    CASE MAY BE HAS EXPIRED, OR SUCH PENALTY HAS BEEN CONFIRMED BY INCOME-TAX APPELLATE TRIBUNAL, AND FIVE
                                    YEARS HAVE NOT ELAPSED AFTER LEVY OF SUCH PENALTY</p>
                                    <p style="text-align: justify;">m. I HAVE NOT BEEN CONVICTED OF AN OFFENCE CONNECTED WITH ANY PROCEEDING UNDER THE INCOME TAX ACT
                                    1961, WEALTH TAX ACT 1957 OR GIFT TAX ACT 1958 AND</p>
                                    <p style="text-align: justify;">n. MY PAN CARD NUMBER/SERVICE TAX NUMBER AS APPLICABLE IS: AHAPD5062G</p>
                                    <p style="text-align: justify;">o. I UNDERTAKE TO KEEP YOU INFORMED OF ANY EVENTS OR HAPPENINGS WHICH WOULD MAKE ME INELIGIBLE FOR
                                    EMPANELMENT AS A VALUER</p>
                                    <p style="text-align: justify;">p. I HAVE NOT CONCEALED OR SUPPRESSED ANY MATERIAL INFORMATION, FACTS AND RECORDS AND I HAVE MADE A
                                    COMPLETE AND FULL DISCLOSURE</p>
                                    <p style="text-align: justify;">q. I HAVE READ THE HANDBOOK ON POLICY, STANDARDS AND PROCEDURE FOR REAL ESTATE VALUATION, 2011 OF THE
                                    IBA AND THIS REPORT IS IN CONFORMITY TO THE “STANDARDS” ENSHRINED FOR VALUATION IN THE PART-B OF THE
                                    ABOVE HANDBOOK TO THE BEST OF MY ABILITY.</p>
                                    <p style="text-align: justify;">r. I HAVE READ THE INTERNATIONAL VALUATION STANDARDS (IVS) AND THE REPORT SUBMITTED TO THE BANK FOR
                                    THE RESPECTIVE ASSET CLASS IS IN CONFORMITY TO THE “STANDARDS” AS ENSHRINED FOR VALUATION IN THE IVS IN
                                    “GENERAL STANDARDS” AND “ASSET STANDARDS” AS APPLICABLE</p>
                                    <p style="text-align: justify;">s. I ABIDE BY THE MODEL CODE OF CONDUCT FOR EMPANELMENT OF VALUER IN THE BANK. (ANNEXURE V- A SIGNED
                                    COPY OF SAME TO BE TAKEN AND KEPT ALONG WITH THIS DECLARATION)</p>
                                    <p style="text-align: justify;">t. I AM REGISTERED UNDER SECTION 34 AB OF THE WEALTH TAX ACT, 1957. (STRIKE OFF, IF NOT APPLICABLE)</p>
                                    <p style="text-align: justify;"><s>u.  I AM VALUER REGISTERED WITH INSOLVENCY & BANKRUPTCY BOARD OF INDIA (IBBI) (STRIKE OFF, IF NOT
                                        APPLICABLE)  </s></p>
                                    <p style="text-align: justify;">v. MY CIBIL SCORE AND CREDIT WORTHINESS IS AS PER BANK’S GUIDELINES.</p>
                                    <p style="text-align: justify;">w. I AM THE PROPRIETOR / PARTNER / AUTHORIZED OFFICIAL OF THE FIRM / COMPANY, WHO IS COMPETENT TO SIGN THIS VALUATION REPORT.</p>
                                    <p style="text-align: justify;">x. I WILL UNDERTAKE THE VALUATION WORK ON RECEIPT OF LETTER OF ENGAGEMENT GENERATED FROM THE SYSTEM (I.E. LLMS/LOS) ONLY.</p>
                                    <p style="text-align: justify;">y. FURTHER, I HEREBY PROVIDE THE FOLLOWING</p>
                                </div>
                                <table class="table table-bordered ">
                                    <thead class="thead-light">

                                        <tr>
                                            <th class="font-weight-bold">
                                                SL.NO.
                                            </th>
                                            <th class="font-weight-bold"> PARTICULARS    </th>
                                            <th class="font-weight-bold"> VALUER COMMENT    </th>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>BACKGROUND INFORMATION OF THE ASSET
                                                BEING VALUED;</td>
                                            <td>Yes</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>PURPOSE OF VALUATION AND APPOINTING
                                                AUTHORITY  </td>
                                            <td><span id="valuation_PURPOSE_out">...........</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>IDENTITY OF THE VALUER AND ANY OTHER
                                                EXPERTS INVOLVED IN THE VALUATION;</td>
                                            <td>MR. ALOKE CHATTERJEE.
                                                SURVEYOR.</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>DISCLOSURE OF VALUER INTEREST OR CONFLICT,
                                                IF ANY;   </td>
                                            <td>No</td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2">5</td>
                                            <td>DATE OF APPOINTMENT,  </td>
                                            <td><span id="work_date"> {{$work->created_at ?? ""}} </span></td>
                                        </tr>
                                        <tr>
                                            <td>VALUATION DATE AND DATE OF REPORT;  </td>
                                            <td><span id="valuation_date_out3"></span></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>INSPECTIONS AND/OR INVESTIGATIONS
                                                UNDERTAKEN</td>
                                            <td><span id="inspection_date_out2"></span></td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>NATURE AND SOURCES OF THE INFORMATION
                                                USED OR RELIED UPON; </td>
                                            <td>Yes</td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>PROCEDURES ADOPTED IN CARRYING OUT THE
                                                VALUATION AND VALUATION STANDARDS
                                                FOLLOWED;   </td>
                                            <td><span id="valuation_procedure_out"></span> </td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>RESTRICTIONS ON USE OF THE REPORT, IF ANY; </td>
                                            <td><span id="report_restrictions_out"></span></td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>MAJOR FACTORS THAT WERE TAKEN INTO
                                                ACCOUNT DURING THE VALUATION; </td>
                                            <td><span id="factors_considered_out"></span></td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td>MAJOR FACTORS THAT WERE NOT TAKEN INTO
                                                ACCOUNT DURING THE VALUATION;  </td>
                                            <td><span id="factors_not_considered_out"></span></td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td>CAVEATS, LIMITATIONS AND DISCLAIMERS TO
                                                THE EXTENT THEY EXPLAIN OR ELUCIDATE THE
                                                LIMITATIONS FACED BY VALUER, WHICH SHALL
                                                NOT BE FOR THE PURPOSE OF LIMITING HIS
                                                RESPONSIBILITY FOR THE VALUATION REPORT. </td>
                                            <td><span id="caveats_limitations_out"></span></td>
                                        </tr>
                                    </thead>
                                </table>
                                <div>
                                    <p class="font-weight-bold">Place: Kolkata</p>
                                    <p class="font-weight-bold">Date: <span id="valuation_date_out2">.......</span></p>
                                </div>
                                <div class="container py-5">
                                    <p style="text-align: right;">(ANNEXURE-II)</p>
                                    <h2 class="text-center mb-4">MODEL CODE OF CONDUCT FOR VALUERS</h2>
                                    
                                    <div class="mb-4">
                                        <h4 class="fw-bold">Integrity and Fairness</h4>
                                        <ol>
                                            <li>A valuer shall, in the conduct of his/its business, follow high standards of integrity and fairness in all his/its dealings with his/its clients and other valuers.</li>
                                            <li>A valuer shall maintain integrity by being honest, straightforward, and forthright in all professional relationships.</li>
                                            <li>A valuer shall endeavour to ensure that he/it provides true and adequate information and shall not misrepresent any facts or situations.</li>
                                            <li>A valuer shall refrain from being involved in any action that would bring disrepute to the profession.</li>
                                            <li>A valuer shall keep public interest foremost while delivering his services.</li>
                                        </ol>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h4 class="fw-bold">Professional Competence and Due Care</h4>
                                        <ol start="6">
                                            <li>A valuer shall render at all times high standards of service, exercise due diligence, ensure proper care and exercise independent professional judgment.</li>
                                            <li>A valuer shall carry out professional services in accordance with the relevant technical and professional standards that may be specified from time to time.</li>
                                            <li>A valuer shall continuously maintain professional knowledge and skill to provide competent professional service based on up-to-date developments in practice, prevailing regulations/guidelines, and techniques.</li>
                                            <li>A valuer shall not disclaim liability for his/its expertise or deny his/its duty of care, except for assumptions based on statements of fact provided by the company or its auditors, consultants, or public domain information.</li>
                                            <li>A valuer shall not carry out any instruction of the client incompatible with integrity, objectivity, and independence.</li>
                                            <li>A valuer shall clearly state to his client the services he is competent to provide and those requiring other professionals.</li>
                                        </ol>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h4 class="fw-bold">Independence and Disclosure of Interest</h4>
                                        <ol start="12">
                                            <li>A valuer shall act with objectivity in professional dealings, free from bias, conflict of interest, coercion, or undue influence.</li>
                                            <li>A valuer shall not take up assignments where he/it or any relative/associate lacks independence.</li>
                                            <li>A valuer shall maintain complete independence in professional relationships.</li>
                                            <li>A valuer shall disclose potential conflicts of interest to clients while providing unbiased services.</li>
                                            <li>A valuer shall not deal in securities of the subject company until the valuation report becomes public.</li>
                                            <li>A valuer shall not engage in mandate snatching or convenience valuations.</li>
                                            <li>An independent valuer shall not charge a success fee.</li>
                                            <li>Any fairness opinion or independent expert opinion shall declare any prior engagement in the last five years.</li>
                                        </ol>
                                    </div>
                            
                                    <div class="mb-4">
                                        <h4 class="fw-bold">Confidentiality</h4>
                                        <ol start="20">
                                            <li>A valuer shall not use or disclose confidential information without proper authority unless legally required.</li>
                                        </ol>
                                    </div>
                            
                                    <div class="mb-4">
                                        <h4 class="fw-bold">Information Management</h4>
                                        <ol start="21">
                                            <li>A valuer shall maintain written records for decisions taken and evidence supporting those decisions.</li>
                                            <li>A valuer shall cooperate with inspections and investigations conducted by the authority or regulatory bodies.</li>
                                            <li>A valuer shall provide information and records as required by regulatory authorities.</li>
                                            <li>A valuer shall maintain working papers for three years or as required in a contract, ensuring record retention until case disposal.</li>
                                        </ol>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h4 class="fw-bold">Gifts and Hospitality</h4>
                                        <ol start="25">
                                            <li>A valuer or relative shall not accept gifts or hospitality affecting independence.</li>
                                            <li>A valuer shall not offer gifts, hospitality, or advantages to public servants to obtain or retain work.</li>
                                        </ol>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h4 class="fw-bold">Remuneration and Costs</h4>
                                        <ol start="27">
                                            <li>A valuer shall charge remuneration transparently and reasonably, reflecting the necessary work done.</li>
                                            <li>A valuer shall not accept fees other than those disclosed in a written contract.</li>
                                        </ol>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h4 class="fw-bold">Occupation, Employability, and Restrictions</h4>
                                        <ol start="29">
                                            <li>A valuer shall not accept too many assignments if unable to devote adequate time.</li>
                                            <li>A valuer shall not conduct business that discredits the profession.</li>
                                        </ol>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h4 class="fw-bold">Miscellaneous</h4>
                                        <ol start="31">
                                            <li>A valuer shall not review another valuer’s work without a written order from banks or financial institutions.</li>
                                            <li>A valuer shall follow this code as amended or revised from time to time.</li>
                                        </ol>
                                    </div>
                                </div>
                                <p class="font-weight-bold">Place: Kolkata</p>
                                <p class="font-weight-bold">Date: <span id="valuation_date_out4">.......</span></p>
                                
                                @foreach ($work->documents as $document)
                                    <div class="text-center my-4"> {{-- Centering div --}}
                                        <h3 class="font-bold text-lg">{{ $document->document_name }}</h3> {{-- Document Name --}}
                                        <img src="https://valuerkkda.com/public/storage/{{ ($document->image) }}" alt="{{ $document->document_name }}" class="mx-auto mt-2 border rounded shadow-lg" style="width: 800px;"> 
                                    </div>
                                @endforeach

                                @if (!empty($work->inspection->uploaded_images) && count($work->inspection->uploaded_images) > 0)
                                    <div class="container my-4">
                                        <h3 class="text-center fw-bold text-lg mb-4">Inspection Uploaded Images</h3>
                                        <div class="table-responsive">
                                            <table class="table table-borderless"> {{-- Bootstrap table without borders --}}
                                                <tbody>
                                                    @foreach (array_chunk($work->inspection->uploaded_images, 3) as $imageRow) {{-- Group images into rows of 3 --}}
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

            <div class="text-center mt-4">
                <button id="downloadWord" class="btn btn-primary">Download Word Document</button>
                <!--<button id="downloadPDF" class="btn btn-danger">Download PDF</button>-->
            </div>

            <!-- Include required libraries -->
            <script src="https://cdn.jsdelivr.net/npm/html-docx-js@0.3.1/dist/html-docx.min.js"></script>



            <script>

    document.getElementById('downloadWord').addEventListener('click', function () {
        const content = document.getElementById('outputArea').innerHTML;

        const formData = new FormData();
        formData.append('content', content);

        fetch('/download-word', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.blob();
        })
        .then(blob => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'valuation_report.docx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        })
        .catch(error => console.error("Error downloading the document:", error));
    });
</script>


