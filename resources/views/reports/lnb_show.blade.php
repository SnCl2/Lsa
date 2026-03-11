@php
    $block_data = json_decode($work->inspection->land_area ?? '{}', true);
    $landAreaData = json_decode($work->inspection->land_area ?? '{}', true);

    $parts = [];

    if (!empty($landAreaData['super_builtup_area'])) {
        $parts[] = 'SUPER BUILT-UP AREA: ' . $landAreaData['super_builtup_area'];
    }

    if (!empty($landAreaData['builtup_area'])) {
        $parts[] = 'BUILT-UP AREA: ' . $landAreaData['builtup_area'];
    }

    if (!empty($landAreaData['carpet_area'])) {
        $parts[] = 'CARPET AREA: ' . $landAreaData['carpet_area'];
    }

    $line = implode(' / ', $parts);
@endphp

<div class="card shadow-sm p-3 overflow-hidden" >
                <h4>Report of Land and Building</h4>
                <form id="inputForm">
                <div id="outputArea" class="border p-3">
                    Ref.: KKDA/SBI/<input type="text" id="racpc"  name="racpc" class="form-control mt-2" placeholder="Type something..." value="{{$work->bankBranch?->name}}">/RACPC/<span class="text-justify" id="currentMonth">.....</span>/{{$work->id}}/<span class="text-justify" id="currentYear">.....</span>-<span class="text-justify" id="currentDate">.....</span>
                    <div>

                        TO, <br>
                        ASSISTANT GENERAL MANAGER, <br>
                        STATE BANK OF INDIA <br>
                        RACPC, <span class="text-justify" id="racpc_out2" >........</span>,

                    </div>
                    <div>
                    <div class="text-center font-weight-bold" style="font-size: 1.2rem;">
                      VALUATION REPORT (IN RESPECT OF 
                      <select id="valuation-type" name="valuation_type" style="display: inline-block; width: auto; padding: 2px 6px; font-weight: bold; border: 1px solid #ccc; border-radius: 4px;">
                        <option value="">-- Select --</option>
                        <option value="LAND">LAND</option>
                        <option value="LAND AND BUILDING">LAND AND BUILDING</option>
                      </select>)
                    </div>

                        <textarea id="report_description" name="report_description" class="form-control mt-2" rows="10">
                            LAND WITH PROPOSED {{ $work->inspection->number_of_floors ?? '----------------' }}, STORIED R.C.C. FRAME WITH ROOF, BRICK BUILT RESIDENTIAL BUILDING & STRUCTURES, SITUATED AT: PREMISES NO. {{ $work->inspection->holding_no ?? '---------' }}, P.O.: {{ $work->inspection->post_office ?? '----------------' }}, P.S.: {{ $work->inspection->police_station ?? '--------------' }}, KOLKATA – {{ $work->inspection->pin_code ?? '--------------' }}, [MOUZA], [DAG],  DISTRICT– {{ $work->inspection->district ?? '-------------------' }}, WITHIN WARD NO.: {{ $work->inspection->ward ?? '------' }}, UNDER {{ $work->inspection->authority ?? '-----------------' }}.
                        </textarea> 
                    </div>
                    <div class="form-group">
                    <!-- OWNER Description Section -->

                    <select id="type1" class="form-control mt-2">
                      <option value="::PURCHASER/BORROWER::">PURCHASER/BORROWER</option>
                      <option value="::Land owner::">Land owner</option>
                      <option value="::Owner::">Owner</option>
                      <option value="::Leaser::">Leaser</option>
                      <option value="::Transferor::">Transferor</option>
                      <option value="::Seller::">Seller</option>
                    </select>
                

                    <textarea id="OWNER" name="OWNER" class="form-control mt-2" rows="10">
                @foreach($work->relatives as $relative)
                [{{ $loop->iteration }}] {{ strtoupper($relative->name) }} (PAN- {{ strtoupper($relative->pan_number) }}) {{ strtoupper($relative->relation) }}, SRI {{ strtoupper($relative->relative_name) }}, RESIDING AT: {{ strtoupper($relative->address) }}.
                @endforeach
                    </textarea>
                
                    <div class="form-check my-2">
                        <input type="checkbox" id="Show1" name="Show1"  onchange="toggleSection(1)">
                        <label for="Show1" class="form-check-label">Show Description</label>
                    </div>
                
                    <!-- PURCHASER/BORROWER Description Section -->

                    <select id="type2" class="form-control mt-2">
                      <option value="::Borrower::">Borrower</option>
                      <option value="::Leasee::">Leasee</option>
                      <option value="::Allottee::">Allottee</option>
                      <option value="::Transferee::">Transferee</option>
                      <option value="::Purchaser::">Purchaser</option>
                      <option value="::Buyer::">Buyer</option>
                    </select>
                                    

                    <textarea id="PURCHASER_BORROWER" name="PURCHASER_BORROWER" class="form-control mt-2" rows="10">
                -----------, RESIDING AT- HOLDING NO. /PREMISES NO.---------, MAKHLA 2 NO. GOVT. COLONY, P.O.: ----------------,
                P.S.: --------------, PIN/KOLKATA –--------------.
                    </textarea>
                
                    <div class="form-check my-2">
                        <input type="checkbox" id="Show2" name="Show2"   onchange="toggleSection(2)">
                        <label for="Show2" class="form-check-label">Show Description</label>
                    </div>
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
                                    <td class="font-weight-bold"> PURPOSE FOR WHICH THE VALUATION IS MADE
                                        <select id="valuation_PURPOSE" name="valuation_PURPOSE" onchange="updatePurpose()">
                                            <option value="NPA">NPA</option>
                                            <option value="PERSONAL">PERSONAL</option>
                                            <option value="REVALUATION">REVALUATION</option>
                                            <option value="RESALE">RESALE</option>
                                            <option value="TOP-UP">TOP-UP</option>
                                            <option value="SECURITY & BANK FINANCE">SECURITY & BANK FINANCE</option>
                                        </select>
                                                
                                    </td>
                                    <td>
                                        <textarea id="purpose" name="purpose" class="form-control mt-2" rows="5">
                                        TO ASSESS FAIR MARKET VALUE OF THE SAID PROPERTY.
                                        NPA FOR F.M.V PURPOSE
                                        </textarea>
                                    </td>
                                </tr>
                                <tr><td rowspan="2">2</td>
                                    <td class="font-weight-bold">A) DATE OF INSPECTION</td>
                                    <td><input type="date" id="inspection_date" name="inspection_date" class="form-control mt-2" value="{{ optional($work->inspection)->updated_at ? \Carbon\Carbon::parse($work->inspection->updated_at)->format('d-m-y') : '' }}"></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">B) DATE ON WHICH THE VALUATION IS MADE</td>
                                    <td><input type="date" id="valuation_date" name="valuation_date" class="form-control mt-2" value="{{ date('d-m-y') }}"></td>
                                </tr>
                                @php
                                    $validDocumentsCount = $work->documents->where('date_of_issue', '!=', '2000-01-01')->count();
                                @endphp

                                <tr>
                                    <td rowspan="{{$validDocumentsCount+1}}">3</td>
                                    <td class="font-weight-bold" colspan="1"> LIST OF DOCUMENTS PRODUCED FOR PERUSAL</td>
                                    <td>


                                        <input type="text" id="list_of_doc" name="list_of_doc" class="form-control mt-2" value="">


                                    </td>
                                </tr>
                                @foreach ($work->documents as $index => $document)
                                    @if ($document->date_of_issue != '2000-01-01')
                                        <tr>
                                            <td class="pl-4">{{  $index}})</td> {{-- I), II), III) ... --}}
                                            <td>XEROX COPY OF {{ $document->document_name }} - Date of Issue: {{ $document->date_of_issue }}</td>

                                        </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td>4</td>
                                    <td class="font-weight-bold"> NAME OF THE OWNER(S)/ <span class="text-justify" class="font-weight-bold">BORROWERS</span> AND HIS / THEIR ADDRESS (ES) WITH
                                        PHONE NO. (DETAILS OF SHARE OF EACH
                                        OWNER IN CASE OF JOINT OWNERSHIP) </td>
                                    <!--<td><div class="text-center">-->
                                    <!--    ::OWNER::-->
                                    <!--</div>-->
                                    <!-- <br>-->
                                    <!-- <span class="text-justify" id="OWNER_out2">......</span>-->
                                    <!-- <br>-->
                                    <!-- <div class="text-center">-->
                                    <!--    ::PURCHASER/BORROWER::-->
                                    <!--</div>-->
                                    <!--<br><span class="text-justify" id="PURCHASER_BORROWERS_out3">......</span> </td>-->
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td class="font-weight-bold"> BRIEF DESCRIPTION OF THE PROPERTY
                                        (INCLUDING LEASEHOLD / FREEHOLD ETC.)  </td>
                                    <td>
                                        <span class="text-justify" id="report_description_out2">......</span>
                                    </td>
                                </tr>
                                <td rowspan="7">6</td>
                                <td class="font-weight-bold"> LOCATION OF PROPERTY    </td>

                                </tr>
                                <tr>
                                    <td class="font-weight-bold">
                                        a) Plot No. / Survey No.
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
                                            <input type="checkbox" id="jl_dag_check" class="form-check-input" value="J.L. NO." onclick="updateTextarea()">
                                            <label for="jl_dag_check" class="form-check-label">J.L. NO.</label>
                                        </div>
                                        
                                        <div class="form-check">
                                            <input type="checkbox" id="lr_KHATIAN_check" class="form-check-input" value="L.R. KHATIAN NO." onclick="updateTextarea()">
                                            <label for="lr_KHATIAN_check" class="form-check-label">L.R. KHATIAN NO.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="lr_dag_check" class="form-check-input" value="R.S. KHATIAN NO." onclick="updateTextarea()">
                                            <label for="lr_dag_check" class="form-check-label">R.S. KHATIAN NO.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="lr_dag_check" class="form-check-input" value="C.S. KHATIAN NO." onclick="updateTextarea()">
                                            <label for="lr_dag_check" class="form-check-label">C.S. KHATIAN NO.</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" id="lr_dag_check" class="form-check-input" value="TOUZI NO." onclick="updateTextarea()">
                                            <label for="lr_dag_check" class="form-check-label">TOUZI NO.</label>
                                        </div>
                                    </td>
                                    <td>

                                            <textarea id="dag_no" name="dag_no" class="form-control mt-2" rows="3"></textarea>
                                            <a href"#" id="append_btn" class="btn btn-primary mt-2">Add to Report</a><br>
              
       
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">b) Door No.</td>
                                    <td><input type="text" id="door_no" name="door_no" class="form-control mt-2" value="{{ $work->inspection->holding_no ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">
                                        c) T. S. No. / Village/ Mouza
                                            <select  id="village_mouza_" name="village_mouza_" class="form-control mt-2" onchange="updateInputField()">
                                                <option value="">___Select___</option>
                                                <option value="Mouza">Mouza</option>
                                                <option value="Village">Village</option>
                                                <option value="TS No.">TS No.</option>
                                            </select>
                                    </td>
                                    <td>                        
                                    <input type="text" id="village_mouza" name="village_mouza" class="form-control mt-2" value="">
                                    <a href"#" id="append_btn2" class="btn btn-primary mt-2">Add to Report</a><br>
                                </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">d) Ward / Taluka</td>
                                    <td><input type="text" id="ward_taluka" name="ward_taluka" class="form-control mt-2" value="{{ $work->inspection->ward ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">e) Mandal / District</td>
                                    <td><input type="text" id="mandal_district" name="mandal_district" class="form-control mt-2" value="{{ $work->inspection->district ?? '' }}"></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">f) Pin No</td>
                                    <td>
                                        <input type="text" id="layout_validity" name="layout_validity" class="form-control mt-2">
                                    </td>
                                </tr>
                                <!--<tr>-->
                                <!--    <td class="font-weight-bold">g) Approved map/Plan Issuing Authority</td>-->
                                <!--    <td><input type="text" id="approved_authority" name="approved_authority" class="form-control mt-2" value="{{ $work->inspection->authority ?? '' }}"></td>-->
                                <!--</tr>-->
                                <!--<tr>-->
                                <!--    <td class="font-weight-bold">h) Whether genuineness or authenticity of approved map/plan is verified.</td>-->
                                <!--    <td>-->
                                <!--        <select type="text" id="authenticity_verified" name="authenticity_verified" class="form-control mt-2">-->
                                <!--        <option value="no">No</option>-->
                                <!--        <option value="yes">Yes</option>-->
                                <!--        <option value="OTHERS">OTHERS</option>-->
                                <!--        </select>-->
                                <!--    </td>-->
                                <!--</tr>-->
                                <!--<tr>-->
                                <!--    <td class="font-weight-bold">i) Any other Comments by our empanelled valuers on authentic of approved plan.</td>-->
                                <!--    <td><input type="text" id="valuer_comments" name="valuer_comments" class="form-control mt-2"></td>-->
                                <!--</tr>-->


                                <tr>
                                    <td>7</td>
                                    <td class="font-weight-bold"> POSTAL ADDRESS OF THE PROPERTY  </td>
                                <td>
                                    <textarea id="postal_address" name="postal_address" class="form-control mt-2" rows="3">{{ $work->address_line_1 ?? '' }} {{ $work->address_line_2 ?? '' }}</textarea>
                                </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold" rowspan="4">8.</td>
                                    <td class="font-weight-bold">CITY / TOWN  </td>
                                <td>
                                    <input type="text" id="city_town" name="city_town" class="form-control mt-2">

                                </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold">RESIDENTIAL AREA</td>
                                <td>
                                    <select type="text" id="residential_area" name="residential_area" class="form-control mt-2">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                        <option value="OTHERS">OTHERS</option>
                                    </select>

                                </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold">COMMERCIAL AREA</td>
                                <td>
                                    <Select type="text" id="commercial_area" name="commercial_area" class="form-control mt-2">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                        <option value="OTHERS">OTHERS</option>
                                    </Select>
                                </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold">INDUSTRIAL AREA</td>
                                <td>
                                    <Select type="text" id="industrial_area" name="industrial_area" class="form-control mt-2">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                        <option value="OTHERS">OTHERS</option>
                                    </Select>
                                </td>
                                </tr>
                                <tr>
                                    <td rowspan="3">9</td>
                                    <td class="font-weight-bold" colspan="2"> CLASSIFICATION OF THE AREA  </td>
                                
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">HIGH / MIDDLE / POOR</td>
                                <td>
                                    <select id="high_middle_poor" name="high_middle_poor" class="form-control mt-2">
                                        <option value="high">High</option>
                                        <option value="middle">Middle</option>
                                        <option value="poor">Poor</option>
                                        <option value="OTHERS">OTHERS</option>
                                    </select>
                                </td>
                                </tr>

                                <tr>
                                    <td class="font-weight-bold">URBAN / SEMI URBAN / RURAL </td>
                                <td>
                                    <select id="urban_semiurban_rural" name="urban_semiurban_rural" class="form-control mt-2">
                                        <option value="urban">Urban</option>
                                        <option value="semi_urban">Semi Urban</option>
                                        <option value="rural">Rural</option>
                                        <option value="OTHERS">OTHERS</option>
                                    </select>
                                </td>
                                </tr>

                                <tr>
                                    <td>10</td>
                                    <td class="font-weight-bold"> COMING UNDER CORPORATION LIMIT /
                                        VILLAGE PANCHAYET / MUNICIPALITY/
                                        CORPORATION   </td>
                                    <td>
                                        <input type="text" id="corporation_limit" name="corporation_limit" class="form-control mt-2" value="{{ $work->inspection->authority ?? '' }}">
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
                                        <input type="text" id="govt_enactments" name="govt_enactments" class="form-control mt-2">

                                    </td>
                                </tr>
                                <tr>
                                    <td>12</td>
                                    <td class="font-weight-bold"> 
                                    In case it is an agricultural land, any 
                                    conversion to house site plots is 
                                    contemplated  </td>
                                    <td>
                                        <input type="text" id="AGRICULTURAL" name="AGRICULTURAL" class="form-control mt-2">

                                    </td>
                                </tr>
                                <!-- ------------------------------------------ -->

                            </tbody>
                        </table>
                        <table class="table table-bordered  mt-3">
                            <tbody>
                                <tr>
                                    <td rowspan="5">12</td>
                                    <td colspan="2" class="font-weight-bold">BOUNDARIES OF THE PROPERTY</td>
                                    <td colspan="2" class="font-weight-bold"></td>
                                    <!--<td class="font-weight-bold">FLAT (ACTUAL)</td>-->
                                </tr>
                                <tr>
                                    <td colspan="2">NORTH</td>
                                    <td><input type="text" id="north_agreement" name="north_agreement" class="form-control mt-2"></td>
                                    <td><input type="text" id="north_building" name="north_building" class="form-control mt-2" value="{{$work->inspection->boundary_building_actual_north ?? ''}}" ></td>
                                    <!--<td><input type="text" id="north_flat" name="north_flat" class="form-control mt-2" value="{{$work->inspection->boundary_flat_actual_north ?? ''}}"></td>-->
                                </tr>
                                <tr>
                                    <td colspan="2">SOUTH</td>
                                    <td><input type="text" id="south_agreement" name="south_agreement" class="form-control mt-2"></td>
                                    <td><input type="text" id="south_building" name="south_building" class="form-control mt-2" value="{{$work->inspection->boundary_building_actual_south ?? ''}}"></td>
                                    <!--<td><input type="text" id="south_flat" name="south_flat" class="form-control mt-2" value="{{$work->inspection->boundary_flat_actual_south ?? ''}}"></td>-->
                                </tr>
                                <tr>
                                    <td colspan="2">EAST</td>
                                    <td><input type="text" id="east_agreement" name="east_agreement" class="form-control mt-2"></td>
                                    <td><input type="text" id="east_building" name="east_building" class="form-control mt-2" value="{{$work->inspection->boundary_building_actual_east ?? ''}}"></td>
                                    <!--<td><input type="text" id="east_flat" name="east_flat" class="form-control mt-2" value="{{$work->inspection->boundary_flat_actual_east ?? ''}}"></td>-->
                                </tr>
                                <tr>
                                    <td colspan="2">WEST</td>
                                    <td><input type="text" id="west_agreement" name="west_agreement" class="form-control mt-2"></td>
                                    <td><input type="text" id="west_building" name="west_building" class="form-control mt-2" value="{{$work->inspection->boundary_building_actual_west ?? ''}}"></td>
                                    <!--<td><input type="text" id="west_flat" name="west_flat" class="form-control mt-2"value="{{$work->inspection->boundary_flat_actual_west ?? ''}}"></td>-->
                                </tr>
                                <!-- 13 -->

                                <tr>
                                    <td rowspan="5">13</td>
                                    <td colspan="2" class="font-weight-bold">DIMENSION OF THE PROPERTY</td>
                                    <td class="font-weight-bold">AS PER PLAN</td>
                                    <td class="font-weight-bold">ACTUAL</td>
                                </tr>
                                <tr>
                                    <td colspan="2">NORTH</td>
                                    <!--<td><input type="text" id="north_DIMENSION" name="north_DIMENSION" class="form-control mt-2"></td>-->
                                    <td><input type="text" id="north_PLAN" name="north_PLAN" class="form-control mt-2" value="{{$work->inspection->dimensions_flat_deed_north ?? ''}}"></td>
                                    <td><input type="text" id="north_ACTUAL" name="north_ACTUAL" class="form-control mt-2" value="{{$work->inspection->dimensions_flat_actual_north ?? ''}}"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">SOUTH</td>
                                    <!--<td><input type="text" id="south_DIMENSION" name="south_DIMENSION" class="form-control mt-2"></td>-->
                                    <td><input type="text" id="south_PLAN" name="south_PLAN" class="form-control mt-2" value="{{$work->inspection->dimensions_flat_deed_south ?? ''}}"></td>
                                    <td><input type="text" id="south_ACTUAL" name="south_ACTUAL" class="form-control mt-2" value="{{$work->inspection->dimensions_flat_actual_south ?? ''}}"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">EAST</td>
                                    <!--<td><input type="text" id="east_DIMENSION" name="east_DIMENSION" class="form-control mt-2"></td>-->
                                    <td><input type="text" id="east_PLAN" name="east_PLAN" class="form-control mt-2" value="{{$work->inspection->dimensions_flat_deed_east ?? ''}}"></td>
                                    <td><input type="text" id="east_ACTUAL" name="east_ACTUAL" class="form-control mt-2" value="{{$work->inspection->dimensions_flat_actual_east ?? ''}}"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">WEST</td>
                                    <!--<td><input type="text" id="west_DIMENSION" name="west_DIMENSION" class="form-control mt-2"></td>-->
                                    <td><input type="text" id="west_PLAN" name="west_PLAN" class="form-control mt-2" value="{{$work->inspection->dimensions_flat_deed_west ?? ''}}"></td>
                                    <td><input type="text" id="west_ACTUAL" name="west_ACTUAL" class="form-control mt-2" value="{{$work->inspection->dimensions_flat_actual_west ?? ''}}"></td>
                                </tr>
                                <!-- 14 -->
                                 <tr>
                                    <td>14</td>
                                    <td colspan="2" class="font-weight-bold">LATITUDE, LONGITUDE AND COORDINATES OF
                                        THE SITE  </td>
                                    <td colspan="2">Latitude: <input type="text" id="latitude" name="latitude" class="form-control mt-2"> & Longitude: <input type="text" id="longitude" name="longitude" class="form-control mt-2"></td>

                                </tr>
                                 <!-- 15 -->
                                <tr>
                                    <td >15</td>
                                    <td colspan="2"  class="font-weight-bold">EXTENT OF THE SITE</td>
                                    <td> <input type="text" id="EXTENT_SITE" name="EXTENT_SITE" class="form-control mt-2"></td>
                                    <td> 
                                        <select id="EXTENT_SITE_unit" name="EXTENT_SITE_unit" class="form-control mt-2">
                                            <option value="SQ.FT">SQ.FT</option>
                                            <option value="SQ.MTR">SQ.MTR</option>
                                            <option value="Cottah">Cottah</option>
                                            <option value="Chittack">Chittack</option>
                                            <option value="Decimal">Decimal</option>
                                            <option value="Acre">Acre</option>
                                            <option value="Bigha">Bigha</option>
                                            <option value="OTHERS">OTHERS</option>
                                        </select>
                                    </td>
                                </tr>

                                <!-- 16 -->
                                <tr>
                                    <td >16</td>
                                    <td colspan="2"  class="font-weight-bold">EXTENT OF THE SITE CONSIDERED FOR
                                        VALUATION (LEAST OF 14 A & 14 B)</td>
                                    <td> <input type="text" id="EXTENT_SITE_2" name="EXTENT_SITE_2" class="form-control mt-2"></td>
                                    <td>
                                        <select id="EXTENT_SITE_unit_2" name="EXTENT_SITE_unit_2" class="form-control mt-2">
                                            <option value="SQ.FT">SQ.FT</option>
                                            <option value="SQ.MTR">SQ.MTR</option>
                                            <option value="Cottah">Cottah</option>
                                            <option value="Chittack">Chittack</option>
                                            <option value="Decimal">Decimal</option>
                                            <option value="Acre">Acre</option>
                                            <option value="Bigha">Bigha</option>
                                            <option value="OTHERS">OTHERS</option>
                                        </select>
                                    </td>
                                </tr>

                                <!-- 17 -->

                                 <tr>
                                    <td>17</td>
                                    <td colspan="2" class="font-weight-bold">Whether occupied by the owner / tenant?  If
                                        occupied by tenant, since how long? Rent
                                        received per month.   </td>
                                    <td colspan="2">
                                        <select name="occupied_by" id="occupied_by" class="form-control mt-2">
                                            <option value="Owner" {{ ($work->inspection->occupied_by ?? '') == 'Owner' ? 'selected' : '' }}>Owner</option>
                                            <option value="Tenant" {{ ($work->inspection->occupied_by ?? '') == 'Tenant' ? 'selected' : '' }}>Tenant</option>
                                            <option value="Vacant" {{ ($work->inspection->occupied_by ?? '') == 'Vacant' ? 'selected' : '' }}>Vacant</option>
                                            <option value="OTHERS" {{ ($work->inspection->occupied_by ?? '') == 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                                        </select>
                                    </td>

                                </tr>
                            </tbody>

                        </table>
                        
                        <!-- CHARACTERISTICS OF THE SITE -->
                        
                                <table class="table table-bordered ">
                                    <thead class="thead-light">

                                        <tr>
                                            <th class="font-weight-bold">
                                                II.
                                            </th>
                                            <th colspan="2" class="font-weight-bold"> CHARACTERISTICS OF THE SITE   </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td class="font-weight-bold"> Classification of locality  </td>
                                            <td> <input  id="classification" class="form-control mt-2" ></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="font-weight-bold"> Development of surrounding areas </td>
                                            <td> <input  id="development" class="form-control mt-2" ></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td class="font-weight-bold"> Possibility of frequent flooding / sub-merging   </td>

                                            <td> <input  id="possibility" class="form-control mt-2" ></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td class="font-weight-bold">Feasibility to the Civic amenities like school, hospital, bus stop, market etc.  </td>

                                            <td>                         
                                            <textarea type="text" id="Feasibility" name="Feasibility" class="form-control mt-2">
                                            Nearest landmark name:- {{ $work->inspection->nearest_landmark_name ?? '--' }} {{ $work->inspection->nearest_landmark_distance ?? '--' }}
                                            Nearest bus stand name:- {{ $work->inspection->nearest_bus_stand_name ?? '--' }} {{ $work->inspection->nearest_bus_stand_distance ?? '--' }}
                                            Nearest railway station name:- {{ $work->inspection->nearest_railway_station_name ?? '--' }} {{ $work->inspection->nearest_railway_station_distance ?? '--' }}
                                            </textarea>
                                        </td>
                                        </tr>
                                        
                                        <!-- new -->
                                        <tr>
                                            <td>5</td>
                                            <td class="font-weight-bold">Level of land with topographical conditions   </td>
                                            <td> <input  id="topographical_conditions" class="form-control mt-2" ></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td class="font-weight-bold"> Shape of land  </td>
                                            <td>
                                              <select id="Shape" class="form-control mt-2">
                                                <option value="">-- Select Shape --</option>
                                                <option value="Regular shape">Regular shape</option>
                                                <option value="Irregular shape">Irregular shape</option>
                                              </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td class="font-weight-bold"> Type of use to which it can be put    </td>
                                            
                                            <td>
                                              <select id="use_type" class="form-control mt-2">
                                                <option value="">-- Select Type --</option>
                                                <option value="Corner point">Corner point</option>
                                                <option value="Intermittent plot">Intermittent plot</option>
                                                <option value="Others">OTHERS</option>
                                              </select>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td class="font-weight-bold">Any usage restriction   </td>
                                            <td> <input  id="restriction" class="form-control mt-2"></td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td class="font-weight-bold">Is plot in town planning approved layout?   </td>
                                            <td> 
                                                <select id="approved" class="form-control mt-2">
                                                    <option value="">-- Select Type --</option>
                                                    <option value="Corner point">Corner point</option>
                                                    <option value="Intermittent plot">Intermittent plot</option>
                                                    <option value="Others">OTHERS</option>
                                                </select>
                                            </td>
                                             
                                            
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td class="font-weight-bold"> Corner plot or intermittent plot?  </td>
                                            <td> <input  id="intermittent" class="form-control mt-2"></td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td class="font-weight-bold"> Road facilities    </td>
                                            <td> <input  id="Road_facilities" class="form-control mt-2" value='{{$work->inspection->connected_road ?? ""}}'></td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td class="font-weight-bold">Type of road available at present   </td>
                                            <td>
                                              <select id="Road_available" class="form-control mt-2">
                                                <option value="">-- Select Road Type --</option>
                                                <option value="Moram Road">Moram Road</option>
                                                <option value="Kancha Road">Kancha Road</option>
                                                <option value="Metal Road">Metal Road</option>
                                                <option value="Black top road">Black top road</option>
                                                <option value="Concrete Roads: Durable and often used for high-traffic areas.">Concrete Roads: Durable and often used for high-traffic areas.</option>
                                                <option value="Asphalt Roads: Flexible and a common type of pavement.">Asphalt Roads: Flexible and a common type of pavement.</option>
                                                <option value=">Gravel Roads: Unpaved roads surfaced with gravel.">Gravel Roads: Unpaved roads surfaced with gravel.</option>
                                                <option value="Earthen Roads: Made of compacted soil.">Earthen Roads: Made of compacted soil.</option>
                                                <option value="Murrum Roads: Constructed with a type of soil known as murrum.">Murrum Roads: Constructed with a type of soil known as murrum.</option>
                                              </select>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>13</td>
                                            <td class="font-weight-bold"> Width of road – is it below 20 ft. or more than 20 ft.   </td>

                                            <td>
                                              <select id="Width_of_road" class="form-control mt-2">
                                                <option value="">-- Select Width --</option>
                                                <option value="Below 20 ft">Below 20 ft</option>
                                                <option value="More than 20 ft">More than 20 ft</option>
                                                <option value="Others">Others</option>
                                              </select>
                                            </td>

                                        </tr>
                                        
                                        <tr>
                                            <td>14</td>
                                            <td class="font-weight-bold"> Is it a land – locked land?    </td>

                                            <td>
                                              <select id="land_locked" class="form-control mt-2">
                                                <option value="">-- Select Option --</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                              </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>15</td>
                                            <td class="font-weight-bold">Water potentiality   </td>
                                            <td>
                                            <select id="Water_potentiality" class="form-control mt-2">
                                                <option value="">-- Select Option --</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                              </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>16</td>
                                            <td class="font-weight-bold"> Underground sewerage system   </td>
                                            <td>
                                            <select id="sewerage" class="form-control mt-2">
                                                <option value="">-- Select Option --</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                             </select>
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td>17</td>
                                            <td class="font-weight-bold">Is power supply available at the site?  </td>
                                           <td>
                                            <select id="power_supply" class="form-control mt-2">
                                                <option value="">-- Select Option --</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                              </select>
                                            </td>
                                            
                                        </tr>
                                        <tr>
                                            <td rowspan="2">18</td>
                                            <td rowspan="2" class="font-weight-bold"> Advantage of the site    </td>
                                            <td> <input  id="Advantage1" class="form-control mt-2"></td>
                                        </tr>
                                        <tr>

                                            <td> <input  id="Advantage2" class="form-control mt-2"></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>19</td>
                                            <td class="font-weight-bold" >Special remarks,<br> if any, like threat of acquisition of land for public service purposes,<br> road widening or applicability of CRZ provisions etc.<br> (Distance from sea-coast / tidal level must be incorporated)   </td>
                                            <td> <input  id="remarks" class="form-control mt-2"></td>
                                        </tr>
                                        
                                        <!--end-->
                                        
                                        


                                    </tbody>

                                </table>
                               
                                <table class="table table-bordered ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="font-weight-bold">
                                                III.
                                            </th>
                                            <th colspan="2" class="font-weight-bold"> Part – A (Valuation of land)    </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td rowspan="3">1</td>
                                            <td class="font-weight-bold"> Size of plot  </td>
                                            <td>
                                              <div style="display: flex; gap: 5px;">
                                                <!--<input id="plot_size" class="form-control mt-2" placeholder="Enter plot size" value >-->
                                                <!--<select id="plot_size_unit" name="unit" class="form-control mt-2" style="width: auto;" onchange="updateUnit('plot_size', 'plot_size_unit')">-->
                                                <!--  <option value="">Unit</option>-->
                                                <!--  <option value="SQ.FT">SQ.FT</option>-->
                                                <!--  <option value="MTR">MTR</option>-->
                                                <!--  <option value="Kata">Kata</option>-->
                                                <!--  <option value="Decimal">Decimal</option>-->
                                                <!--  <option value="Bigha">Bigha</option>-->
                                                <!--  <option value="Chitak">Chitak</option>-->
                                                <!--</select>-->
                                              </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            
                                            <td class="font-weight-bold"> North & South</td>
                                            <td>
                                              <div style="display: flex; gap: 5px;">
                                                <input id="North_South" class="form-control mt-2" placeholder="Enter value">
                                                <select id="North_South_unit" class="form-control mt-2" onchange="updateUnit('North_South', 'North_South_unit')">
                                                  <option value="">Unit</option>
                                                  <option value="FT">FT</option>
                                                  <option value="MTR">MTR</option>
                                                </select>
                                              </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            
                                            <td class="font-weight-bold"> East & West   </td>

                                            <td>
                                              <div style="display: flex; gap: 5px;">
                                                <input id="East_West" class="form-control mt-2" placeholder="Enter value">
                                                <select id="East_West_unit" class="form-control mt-2" onchange="updateUnit('East_West', 'East_West_unit')">
                                                  <option value="">Unit</option>
                                                  <option value="FT">FT</option>
                                                  <option value="MTR">MTR</option>
                                                </select>
                                              </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="font-weight-bold"> Total extent of the plot   </td>
                                            <td>
                                              <div style="display: flex; gap: 5px;">
                                                <input id="Total_extent" class="form-control mt-2" placeholder="Enter value">
                                                <select id="unitSelect" class="form-control mt-2" onchange="updateUnit('Total_extent', 'unitSelect')">
                                                  <option value="">Unit</option>
                                                  <option value="SQ.FT">SQ.FT</option>
                                                  <option value="MTR">MTR</option>
                                                  <option value="Kata">Kata</option>
                                                  <option value="Decimal">Decimal</option>
                                                  <option value="Bigha">Bigha</option>
                                                  <option value="Chitak">Chitak</option>
                                                </select>
                                              </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td class="font-weight-bold"> Prevailing market rate <br>(Along with details /reference of at least two latest deals/<br>transactions with respect to adjacent properties in the areas)    </td>
                                            <td> 
                                                <label>1. Govt. Guideline Price collected from Govt. Website:</label><br>
                                                Link: <input type="url" id="govt_link" name="govt_link" placeholder="https://example.gov.in" class="form-control mt-2"><br>
                                                Market Rate: Rs. <input type="number" id="govt_rate" name="govt_rate" step="0.01" placeholder="Rate per decimal" class="form-control mt-2"><br><br>
                                              
                                            
                
                                                <label>2. Price collected from 99acres.com Website:</label><br>
                                                Link: <input type="url" id="acres99_link" name="acres99_link" placeholder="https://www.99acres.com" class="form-control mt-2"><br>
                                                Market Rate: Rs. <input type="number" id="acres99_rate" name="acres99_rate" step="0.01" placeholder="Rate per decimal" class="form-control mt-2"><br><br>
                                              
                                            
                
                                                <label>3. Price collected from realestateindia.com Website:</label><br>
                                                Link: <input type="url" name="realestateindia_link" id="realestateindia_link" placeholder="https://www.realestateindia.com" class="form-control mt-2"><br>
                                                Market Rate: Rs. <input type="number" id="realestateindia_rate" name="realestateindia_rate" step="0.01" placeholder="Rate per decimal" class="form-control mt-2"><br><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td class="font-weight-bold"> Guideline rate obtained from the           Registrar’s Office (an evidence thereof to be enclosed)    </td>
                                            <td>
                                            <textarea name="Guideline" id="Guideline" class="form-control mt-2">
                                                    1. Govt. Guide Line Price collected from Govt. Web Site : ----Link---- shows that Market & itshows that Market rate is Rs.---Rate--- per decimal
                                            </textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td class="font-weight-bold">Assessed / adopted rate of valuation    </td>
                                            <td> 
                                            
                                                <div class="form-group">
                                                    <label for="rate">Rate per Unit (Rs.):</label>
                                                    <input type="number" step="0.01" id="rate" class="form-control mt-2" required>
                                                  </div>
                                            
                                                <textarea name="Assessed" id="Assessed" class="form-control mt-2">
                                                    From the local enquiry and market investigation it has 
                                                    been revealed that the rate for vacant, developed on-road 
                                                    in-and-around the site for residential land and its market 
                                                    trend varies between @ Rs.3,50,000.00 per Decimal to @ 
                                                    Rs.4,50,000.00 per Decimal. Therefore, it is understood 
                                                    that in-and-around the site, the market trend is @ Rs. 
                                                    4,00,000.00 per Decimal may be considered for residential 
                                                    use on an average considering the location, sites, access, 
                                                    size and shape infra-structural facilities and 
                                                    neighbourhood area.   
                                                     
                                                    The Govt. Rate is lower then Market Value. We consider the 
                                                    Rs. 4,00,000.00 per Decimal taken for consideration all 
                                                    possible factors of marketability, location, security, 
                                                    liquidity and risk factors and well developing area.  
                                                </textarea>
                                            
                                            
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td class="font-weight-bold"> Estimated value of land    
                                            
                                            </td>
                                            
                                            <td> 
                                            
                                                  
                                                
                                                  <button type="button" class="btn btn-primary mt-3" onclick="calculateValuation()">Calculate</button>
                                                
                                                  <div class="form-group mt-3">
                                                    <label for="Estimated">Estimated Valuation:</label>
                                                    <textarea name="Estimated" id="Estimated" class="form-control mt-2" rows="3" ></textarea>
                                                  </div>

                                                
                                                

                                            
                                            
                                            </td>
                                        </tr>
                                        
                                        
                                        
                                </table>
                                <table class="table table-bordered ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="font-weight-bold">
                                                IV.
                                            </th>
                                            <th colspan="3" class="font-weight-bold"> Part – B (Valuation of Building)    </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td rowspan="14">1.</td> 
                                            <td colspan="3">Technical details of the building </td>
                                        </tr>
                                        <tr>
                                            <td>a)</td>
                                            <td>Type of Building (Residential / Commercial / Industrial)</td>
                                            <td>
                                              <select id="Building_type" class="form-control mt-2">
                                                <option value="">-- Select Building Type --</option>
                                                <option value="Residential">Residential</option>
                                                <option value="Commercial">Commercial</option>
                                                <option value="Industrial">Industrial</option>
                                              </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>b)</td>
                                            <td>Type of construction (Load bearing / RCC / Steel Framed)</td>
                                            <td>
                                              <select id="construction_type" class="form-control mt-2">
                                                <option value="">-- Select Construction Type --</option>
                                                <option value="Load bearing">Load bearing</option>
                                                <option value="RCC">RCC</option>
                                                <option value="Steel Framed">Steel Framed</option>
                                              </select>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>c)</td>
                                            <td>Year of construction  </td>
                                            <td> <input  id="construction_year" value='{{$work->inspection->year_of_construction ?? ""}}' ></td>
                                        </tr>
                                        <tr>
                                            <td>d)</td>
                                            <td>Number of floors and height of each floor including basement, if any  </td>
                                            <td><textarea id="Number_floors" rows="3" class="form-control"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>e)</td>
                                            <td>Plinth area  </td>
                                            <td><textarea id="Plinth_area" rows="3" class="form-control"></textarea></td>
                                        </tr>
                                        <tr>
                                          <td rowspan="3">f)</td>
                                          <td>Condition of the building</td>
                                          <td></td>
                                        </tr>
                                        <tr>
                                          <td>i) Exterior – Excellent, Good, Normal, Poor</td>
                                          <td>
                                            <select id="Exterior" class="form-control mt-2">
                                              <option value="">-- Select Condition --</option>
                                              <option value="Excellent">Excellent</option>
                                              <option value="Good">Good</option>
                                              <option value="Normal">Normal</option>
                                              <option value="Poor">Poor</option>
                                            </select>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td>ii) Interior – Excellent, Good, Normal, Poor</td>
                                          <td>
                                            <select id="Interior" class="form-control mt-2">
                                              <option value="">-- Select Condition --</option>
                                              <option value="Excellent">Excellent</option>
                                              <option value="Good">Good</option>
                                              <option value="Normal">Normal</option>
                                              <option value="Poor">Poor</option>
                                            </select>
                                          </td>
                                        </tr>

                                        <tr>
                                            <td>g)</td>
                                            <td>Date of issue and validity of layout of approved map / plan  </td>
                                            <td> <input  id="Date_of_issue" ></td>
                                        </tr>
                                        <tr>
                                            <td>h)</td>
                                            <td>Approved map / plan issuing authority  </td>
                                            <td> <input  id="Approved_map" ></td>
                                        </tr>
                                        <tr>
                                            <td>i)</td>
                                            <td>Whether genuineness or authenticity of approved map / plan is verified  </td>
                                            <td>
                                                <select id="Whether_Approved_map" class="form-control mt-2">
                                                    <option value="">-- Select Option --</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                    <option value="OTHERS">OTHERS</option>
                                                 </select>
                                                <td/>
                                        </tr>
                                        <tr>
                                            <td>j)</td>
                                            <td>Any other comments by our empanelled valuers on authentic of approved plan  </td>

                                                <td>
                                                <select id="empanelled_valuers" class="form-control mt-2">
                                                    <option value="">-- Select Option --</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="No">No</option>
                                                    <option value="OTHERS">OTHERS</option>
                                                 </select>
                                                <td/>
                                        </tr>


                                    </tbody>
                                    </table>
                                    <h3>
                                    Specifications of construction (floor-wise) in respect of
                                    </h3>
                                
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>SL. No.</th>
                                            <th>Description</th>
                                            <th>Ground floor</th>
                                            <th>Other floors</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>2.</td>
                                            <td>Compound wall</td>
                                            <td><input type="text" id="ground_compound_wall" name="ground_compound_wall"></td>
                                            <td><input type="text" name="other_compound_wall" id="other_compound_wall"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Height</td>
                                            <td><input type="text" name="ground_height" id="ground_height"></td>
                <td><input type="text" id="other_height" name="other_height"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Length</td>
                                            <td><input type="text" name="ground_length" id="ground_length"></td>
                <td><input type="text" id="other_length" name="other_length"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Type of construction</td>
                                            <td><input type="text" name="ground_construction" id="ground_construction"></td>
                <td><input type="text" id="other_construction" name="other_construction"></td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>Electrical installation</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Type of wiring</td>
                                            <td><input type="text" name="ground_wiring" id="ground_wiring" value="Conceal Wiring"></td>
                <td><input type="text" id="other_wiring" name="other_wiring" value="Conceal Wiring"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Class of fittings (Superior / Ordinary / Poor)</td>
                                            <td>
                                              <select name="ground_fittings" id="ground_fittings" class="form-control mt-2">
                                                <option value="">-- Select --</option>
                                                <option value="Superior">Superior</option>
                                                <option value="Ordinary">Ordinary</option>
                                                <option value="Poor">Poor</option>
                                              </select>
                                            </td>
                                            <td>
                                              <select name="other_fittings" id="other_fittings" class="form-control mt-2">
                                                <option value="">-- Select --</option>
                                                <option value="Superior">Superior</option>
                                                <option value="Ordinary">Ordinary</option>
                                                <option value="Poor">Poor</option>
                                              </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Number of light points</td>
                                            <td><input type="text" name="ground_light_points" id="ground_light_points" value='{{$work->inspection->light_points ?? ""}}'></td>
                <td><input type="text" id="other_light_points" name="other_light_points" value="-Do-"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Fan points</td>
                                            <td><input type="text" name="ground_fan_points" id="ground_fan_points" value='{{$work->inspection->fan_points ?? ""}}'></td>
                <td><input type="text" id="other_fan_points" name="other_fan_points" value="-Do-"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Spare plug points</td>
                                            <td><input type="text" name="ground_plug_points" id="ground_plug_points" value='{{$work->inspection->plug_points ?? ""}}'></td>
                <td><input type="text" id="other_plug_points" name="other_plug_points" value="-Do-"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Any other item</td>
                                            <td><input type="text" name="ground_any_other" id="ground_any_other" value="-Do-"></td>
                <td><input type="text" id="other_any_other" name="other_any_other" value="-Do-"></td>
                                        </tr>
                                        <tr>
                                            <td>4.</td>
                                            <td>Plumbing installation</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>No. of water closets and their type</td>
                                            <td><input type="text" name="ground_water_closets" id="ground_water_closets" value='{{$work->inspection->water_closets ?? ""}}'></td>
                <td><input type="text" id="other_water_closets" name="other_water_closets"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>No. of wash basins</td>
                                            <td><input type="text" name="ground_wash_basins" id="ground_wash_basins" value='{{$work->inspection->washbasins ?? ""}}'></td>
                <td><input type="text" id="other_wash_basins" name="other_wash_basins"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>No. of urinals</td>
                                            <td><input type="text" name="ground_urinals" id="ground_urinals"></td>
                <td><input type="text" id="other_urinals" name="other_urinals"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>No. of bath tubs</td>
                                            <td><input type="text" name="ground_bath_tubs" id="ground_bath_tubs" value='{{$work->inspection->bathtubs ?? ""}}'></td>
                <td><input type="text" id="other_bath_tubs" name="other_bath_tubs"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Water meter, taps, etc.</td>
                                            <td><input type="text" name="ground_water_meter" id="ground_water_meter" value="Yes"></td>
                <td><input type="text" id="other_water_meter" name="other_water_meter"></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>Any other fixtures</td>
                                            <td><input type="text" name="ground_fixtures" id="ground_fixtures" value="No"></td>
                                            <td><input type="text" id="other_fixtures" name="other_fixtures"></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <h3 class="text-center mb-3">Details of Valuation</h3>
                                <table class="table table-bordered" id="valuationTable">
                                  <thead>
                                    <tr>
                                      <th>Sr. No.</th>
                                      <th>Particulars of Item</th>
                                      <th>Plinth area (Sq. ft.)</th>
                                      <th>Roof height</th>
                                      <th>Age of Building</th>
                                      <th>Rate (Rs.)</th>
                                      <th>Replacement Cost (Rs.)</th>
                                      <th>%</th>
                                      <th>Net Value (Rs.)</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>1</td>
                                      <td><input class="form-control" id="item_1" value="Foundation" ></td>
                                      <td><input type="number" class="form-control area" id="area_1" value="5158.58" step="0.01"></td>
                                      <td><input class="form-control" id="roof_1" value="10'-00&quot;" ></td>
                                      <td><input class="form-control" id="age_1" value="Under Construction" ></td>
                                      <td><input type="number" class="form-control rate" id="rate_1" value="400"></td>
                                      <td><input class="form-control replacement-cost" id="replacement_1" ></td>
                                      <td><input type="number" class="form-control percent" id="percent_1" value="100"></td>
                                      <td><input class="form-control net-value" id="net_1" ></td>
                                    </tr>
                                    <tr>
                                      <td>2</td>
                                      <td><input class="form-control" id="item_2" value="Ground Floor" ></td>
                                      <td><input type="number" class="form-control area" id="area_2" value="5158.58" step="0.01"></td>
                                      <td><input class="form-control" id="roof_2" value="10'-00&quot;" ></td>
                                      <td><input class="form-control" id="age_2" value="Construction" ></td>
                                      <td><input type="number" class="form-control rate" id="rate_2" value="2600"></td>
                                      <td><input class="form-control replacement-cost" id="replacement_2" ></td>
                                      <td><input type="number" class="form-control percent" id="percent_2" value="50"></td>
                                      <td><input class="form-control net-value" id="net_2" ></td>
                                    </tr>
                                    <tr>
                                      <td>3</td>
                                      <td><input class="form-control" id="item_3" value="First Floor" ></td>
                                      <td><input type="number" class="form-control area" id="area_3" value="3579.91" step="0.01"></td>
                                      <td><input class="form-control" id="roof_3" value="10'-00&quot;" ></td>
                                      <td><input class="form-control" id="age_3" value="" ></td>
                                      <td><input type="number" class="form-control rate" id="rate_3" value="2200"></td>
                                      <td><input class="form-control replacement-cost" id="replacement_3" ></td>
                                      <td><input type="number" class="form-control percent" id="percent_3" value="60"></td>
                                      <td><input class="form-control net-value" id="net_3" ></td>
                                    </tr>
                                    <tr>
                                      <td>4</td>
                                      <td><input class="form-control" id="item_4" value="Swimming Pool" ></td>
                                      <td><input type="number" class="form-control area" id="area_4" value="856.94" step="0.01"></td>
                                      <td><input class="form-control" id="roof_4" value="8'-00&quot;" ></td>
                                      <td><input class="form-control" id="age_4" value="" ></td>
                                      <td><input type="number" class="form-control rate" id="rate_4" value="3000"></td>
                                      <td><input class="form-control replacement-cost" id="replacement_4" ></td>
                                      <td><input type="number" class="form-control percent" id="percent_4" value="80"></td>
                                      <td><input class="form-control net-value" id="net_4" ></td>
                                    </tr>
                                  </tbody>
                                  <tfoot>
                                    <tr class="fw-bold">
                                      <td colspan="6" class="text-end">TOTAL</td>
                                      <td><input class="form-control" id="total-replacement" ></td>
                                      <td></td>
                                      <td><input class="form-control" id="total-net-value" ></td>
                                    </tr>
                                  </tfoot>
                                </table>

                                
                                <script>
                                function calculateTable() {
                                  let totalReplacement = 0;
                                  let totalNet = 0;
                                
                                  const rows = document.querySelectorAll("#valuationTable tbody tr");
                                  rows.forEach(row => {
                                    const area = parseFloat(row.querySelector(".area").value) || 0;
                                    const rate = parseFloat(row.querySelector(".rate").value) || 0;
                                    const percent = parseFloat(row.querySelector(".percent").value) || 0;
                                
                                    const replacementCost = area * rate;
                                    const netValue = replacementCost * (percent / 100);
                                
                                    row.querySelector(".replacement-cost").value = replacementCost.toLocaleString("en-IN");
                                    row.querySelector(".net-value").value = netValue.toLocaleString("en-IN");
                                
                                    totalReplacement += replacementCost;
                                    totalNet += netValue;
                                  });
                                
                                  document.getElementById("total-replacement").value = totalReplacement.toLocaleString("en-IN");
                                  document.getElementById("total-net-value").value = totalNet.toLocaleString("en-IN");
                                  document.getElementById("amount_building").value = totalNet.toLocaleString("en-IN");
                                }
                                
                                document.querySelectorAll(".area, .rate, .percent").forEach(input => {
                                  input.addEventListener("input", calculateTable);
                                });
                                
                                // Initial calculation
                                calculateTable();
                                </script>

                                <h3 class="text-center mb-3">Part C - Extra Items</h3>
                                <table class="table table-bordered">
                                  <thead class="thead-light">
                                    <tr>
                                      <th>SL. NO.</th>
                                      <th>Items</th>
                                      <th>Amount (in Rs.)</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>1</td>
                                      <td>Portico</td>
                                      <td><input type="text" id="amount_portico" name="amount_portico" value="Nil" oninput="calculateTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>2</td>
                                      <td>Ornamental front door</td>
                                      <td><input type="text" id="amount_front_door" name="amount_front_door" value="Nil" oninput="calculateTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>3</td>
                                      <td>Sit out/ Verandah with steel grills</td>
                                      <td><input type="text" id="amount_sitout" name="amount_sitout" value="Nil" oninput="calculateTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>4</td>
                                      <td>Overhead water tank</td>
                                      <td><input type="text" id="amount_water_tank" name="amount_water_tank" value="Nil" oninput="calculateTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>5</td>
                                      <td>Extra steel/ collapsible gates</td>
                                      <td><input type="text" id="amount_gates" name="amount_gates" value="Nil" oninput="calculateTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"><strong>Total</strong></td>
                                      <td><input type="text" id="amount_total" name="amount_total" value="Nil" ></td>
                                    </tr>
                                  </tbody>
                                </table>

                            
                                <h3 class="text-center mb-3">Part D - Amenities</h3>
                                <table class="table table-bordered">
                                  <thead class="thead-light">
                                    <tr>
                                      <th>SL. NO.</th>
                                      <th>Items</th>
                                      <th>Amount (in Rs.)</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>1</td>
                                      <td>Wardrobes</td>
                                      <td><input type="text" id="amount_wardrobes" name="amount_wardrobes" value="Nil" oninput="calculateAmenitiesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>2</td>
                                      <td>Glazed tiles</td>
                                      <td><input type="text" id="amount_glazed_tiles" name="amount_glazed_tiles" value="Nil" oninput="calculateAmenitiesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>3</td>
                                      <td>Extra sinks and bath tub</td>
                                      <td><input type="text" id="amount_sinks_bath_tub" name="amount_sinks_bath_tub" value="Nil" oninput="calculateAmenitiesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>4</td>
                                      <td>Marble / Ceramic tiles flooring</td>
                                      <td><input type="text" id="amount_flooring" name="amount_flooring" value="Nil" oninput="calculateAmenitiesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>5</td>
                                      <td>Interior decorations</td>
                                      <td><input type="text" id="amount_interior" name="amount_interior" value="Nil" oninput="calculateAmenitiesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>6</td>
                                      <td>Architectural elevation works</td>
                                      <td><input type="text" id="amount_elevation" name="amount_elevation" value="Nil" oninput="calculateAmenitiesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>7</td>
                                      <td>Panelling works</td>
                                      <td><input type="text" id="amount_panelling" name="amount_panelling" value="Nil" oninput="calculateAmenitiesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>8</td>
                                      <td>Aluminium works</td>
                                      <td><input type="text" id="amount_aluminium" name="amount_aluminium" value="Nil" oninput="calculateAmenitiesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>9</td>
                                      <td>Aluminium hand rails</td>
                                      <td><input type="text" id="amount_hand_rails" name="amount_hand_rails" value="Nil" oninput="calculateAmenitiesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>10</td>
                                      <td>False ceiling</td>
                                      <td><input type="text" id="amount_false_ceiling" name="amount_false_ceiling" value="Nil" oninput="calculateAmenitiesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"><strong>Total</strong></td>
                                      <td><input type="text" id="amount_amenities_total" name="amount_amenities_total" value="Nil" ></td>
                                    </tr>
                                  </tbody>
                                </table>

                            
                                <h3 class="text-center mb-3">Part E - Miscellaneous</h3>
                                <table class="table table-bordered">
                                  <thead class="thead-light">
                                    <tr>
                                      <th>SL. NO.</th>
                                      <th>Items</th>
                                      <th>Amount (in Rs.)</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>1</td>
                                      <td>Separate toilet room</td>
                                      <td><input type="text" id="amount_toilet_room" name="amount_toilet_room" value="Nil" oninput="calculateMiscellaneousTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>2</td>
                                      <td>Separate lumber room</td>
                                      <td><input type="text" id="amount_lumber_room" name="amount_lumber_room" value="Nil" oninput="calculateMiscellaneousTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>3</td>
                                      <td>Separate water tank/ sump</td>
                                      <td><input type="text" id="amount_water_tank_sump" name="amount_water_tank_sump" value="Nil" oninput="calculateMiscellaneousTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>4</td>
                                      <td>Trees, gardening</td>
                                      <td><input type="text" id="amount_trees_gardening" name="amount_trees_gardening" value="Nil" oninput="calculateMiscellaneousTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"><strong>Total</strong></td>
                                      <td><input type="text" id="amount_miscellaneous_total" name="amount_miscellaneous_total" value="Nil" ></td>
                                    </tr>
                                  </tbody>
                                </table>

                            
                                <h3 class="text-center mb-3">Part F - Services</h3>
                                <table class="table table-bordered">
                                  <thead class="thead-light">
                                    <tr>
                                      <th>SL. NO.</th>
                                      <th>Items</th>
                                      <th>Amount (in Rs.)</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>1</td>
                                      <td>Water supply arrangements</td>
                                      <td><input type="text" id="amount_water_supply" name="amount_water_supply" value="Nil" oninput="calculateServicesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>2</td>
                                      <td>Drainage arrangements</td>
                                      <td><input type="text" id="amount_drainage" name="amount_drainage" value="Nil" oninput="calculateServicesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>3</td>
                                      <td>Compound wall</td>
                                      <td><input type="text" id="amount_compound_wall" name="amount_compound_wall" value="Nil" oninput="calculateServicesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>4</td>
                                      <td>C. B. deposits, fittings etc.</td>
                                      <td><input type="text" id="amount_cb_deposits" name="amount_cb_deposits" value="Nil" oninput="calculateServicesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td>5</td>
                                      <td>Pavement</td>
                                      <td><input type="text" id="amount_pavement" name="amount_pavement" value="Nil" oninput="calculateServicesTotal()"></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"><strong>Total</strong></td>
                                      <td><input type="text" id="amount_services_total" name="amount_services_total" value="Nil" ></td>
                                    </tr>
                                  </tbody>
                                </table>

                            
                                <h3 class="text-center mb-3">Total Abstract of the Entire Property</h3>
                                <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Part</th>
                                        <th>Description</th>
                                        <th>Amount (in Rs.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Part A</td>
                                        <td>Land</td>
                                        <td><input type="text" id="amount_land" name="amount_land" placeholder="Rs. ......." oninput="calculateFinalTotal()"></td>
                                    </tr>
                                    <tr>
                                        <td>Part B</td>
                                        <td>Building (as on date)</td>
                                        <td><input type="text" id="amount_building" name="amount_building" placeholder="Rs. ......" oninput="calculateFinalTotal()"></td>
                                    </tr>
                                    <tr>
                                        <td>Part C</td>
                                        <td>Extra Items</td>
                                        <td><input type="text" id="amount_extra_items" name="amount_extra_items" value="Nil" oninput="calculateFinalTotal()"></td>
                                    </tr>
                                    <tr>
                                        <td>Part D</td>
                                        <td>Amenities</td>
                                        <td><input type="text" id="amount_amenities" name="amount_amenities" value="Nil" oninput="calculateFinalTotal()"></td>
                                    </tr>
                                    <tr>
                                        <td>Part E</td>
                                        <td>Miscellaneous</td>
                                        <td><input type="text" id="amount_miscellaneous" name="amount_miscellaneous" value="Nil" oninput="calculateFinalTotal()"></td>
                                    </tr>
                                    <tr>
                                        <td>Part F</td>
                                        <td>Services</td>
                                        <td><input type="text" id="amount_services" name="amount_services" value="Nil" oninput="calculateFinalTotal()"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Total</strong></td>
                                        <td><input type="text" id="amount_total_final" name="amount_total" placeholder="Rs. ......." ></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Say</strong></td>
                                        <td><input type="text" id="amount_say" name="amount_say" placeholder="Rs. ......." ></td>
                                    </tr>
                                </tbody>
                            </table>


                                <div >
                                    <div class="font-weight-bold text-center">
                                        :PRESENT VALUE OF THE SAID FLAT:
                                    </div>

                                    <p style="text-align: justify;"> AS A RESULT OF MY APPRAISAL AND ANALYSIS, IT IS MY CONSIDERED OPINION THAT THE</p>


                                    <p style="text-align: justify;"> FAIR MARKET VALUE OF THE ABOVE PROPERTY IS RS.<span  id="rounded_value_out" >..........</span> (RUPEES <span  id="rounded_value_out" >..........</span>
                                    ONLY).
                                    REALIZABLE VALUE OF THE ABOVE PROPERTY IS RS.<span  id="rounded_value_out" >..........</span> (RUPEES <span  id="rounded_value_out" >..........</span>
                                    ONLY).(DEDUCT 10% FROM FAIR MARKET VALUE)
                                    THE DISTRESS VALUE OF THE ABOVE PROPERTY IS RS.<span  id="rounded_value_out" >..........</span> (RUPEES <span  id="rounded_value_out" >..........</span> ONLY).(DEDUCT 20% FROM FAIR MARKET VALUE)</p>

                                </div>
                                <div >
                                    <div class="font-weight-bold text-center">
                                        : FUTURE VALUE OF THE SAID FLAT:
                                    </div>

                                    <p style="text-align: justify;"> AS A RESULT OF MY APPRAISAL AND ANALYSIS, IT IS MY CONSIDERED OPINION THAT THE</p>


                                    <p style="text-align: justify;"> FAIR MARKET VALUE OF THE ABOVE PROPERTY IS RS.<span  id="rounded_value_out" >..........</span> (RUPEES <span  id="rounded_value_out" >..........</span> ONLY).
                                    REALIZABLE VALUE OF THE ABOVE PROPERTY IS RS.<span  id="rounded_value_out" >..........</span> (RUPEES <span  id="rounded_value_out" >..........</span>
                                    ONLY).(DEDUCT 10% FROM FAIR MARKET VALUE)
                                    THE DISTRESS VALUE OF THE ABOVE PROPERTY IS RS. <span  id="rounded_value_out" >..........</span> (RUPEES <span  id="rounded_value_out" >..........</span> ONLY).(DEDUCT 20% FROM FAIR MARKET VALUE)</p>

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
                                            <td>BACKGROUND INFORMATION OF THE ASSET BEING VALUED</td>
                                            <td><input type="text" id="background_info" name="background_info" value="Yes"></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>PURPOSE OF VALUATION AND APPOINTING AUTHORITY</td>
                                            <td><input type="text" id="purpose_valuation" name="purpose_valuation"></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>IDENTITY OF THE VALUER AND ANY OTHER EXPERTS INVOLVED IN THE VALUATION</td>
                                            <td><input type="text" id="valuer_identity" name="valuer_identity" value="MR. ALOKE CHATTERJEE. SURVEYOR."></td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>DISCLOSURE OF VALUER INTEREST OR CONFLICT, IF ANY</td>
                                            <td><input type="text" id="valuer_conflict" name="valuer_conflict" value="No"></td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>DATE OF APPOINTMENT, VALUATION DATE AND DATE OF REPORT</td>
                                            <td><input type="text" id="valuation_date" name="valuation_date" value="2025-03-18 04:48:17"></td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>INSPECTIONS AND/OR INVESTIGATIONS UNDERTAKEN</td>
                                            <td><input type="text" id="inspections" name="inspections"></td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>NATURE AND SOURCES OF THE INFORMATION USED OR RELIED UPON</td>
                                            <td><input type="text" id="info_sources" name="info_sources" value="Yes"></td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>PROCEDURES ADOPTED IN CARRYING OUT THE VALUATION AND VALUATION STANDARDS FOLLOWED</td>
                                            <td><input type="text" id="procedures" name="procedures"></td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>RESTRICTIONS ON USE OF THE REPORT, IF ANY</td>
                                            <td><input type="text" id="restrictions" name="restrictions"></td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>MAJOR FACTORS THAT WERE TAKEN INTO ACCOUNT DURING THE VALUATION</td>
                                            <td><input type="text" id="factors_taken" name="factors_taken"></td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td>MAJOR FACTORS THAT WERE NOT TAKEN INTO ACCOUNT DURING THE VALUATION</td>
                                            <td><input type="text" id="factors_not_taken" name="factors_not_taken"></td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td>CAVEATS, LIMITATIONS AND DISCLAIMERS</td>
                                            <td><input type="text" id="caveats" name="caveats"></td>
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


                        <button type="submit" class="btn btn-success">Done</button>
                        </form>
                                        
                                        
                    </div>


                </div>
            </div>
                                            
            <form id="reportForm" 
                  action="{{ isset($report) ? route('report_lnb.update', $report->id) : route('report.lnb_store') }}" 
                  method="POST">
                @csrf
                @if(isset($report))
                    @method('PUT') {{-- Spoof PUT for update --}}
                @endif
            
                <input type="hidden" id="work_id" name="work_id" value="{{ $work->id }}">
            
                <label for="report_data" style="display: none;">Report Data:</label>
                <textarea id="report_data" name="report_data" required>{{ old('report_data', $report->data ?? '') }}</textarea>
            
                <br>
                <button type="submit" class="btn btn-primary">
                    {{ isset($report) ? 'Update Report' : 'Submit And Download Report' }}
                </button>
            </form>


<script>
document.getElementById('inputForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = {};
    const elements = document.querySelectorAll('#inputForm input, #inputForm textarea, #inputForm select');

    elements.forEach(element => {
        if (element.type === "date" && element.value) {
            // Convert date from yyyy-mm-dd to dd-mm-yyyy
            const dateParts = element.value.split("-");
            if (dateParts.length === 3) {
                formData[element.id] = `${dateParts[2]}-${dateParts[1]}-${dateParts[0]}`; 
            } else {
                formData[element.id] = element.value; // Fallback in case of an issue
            }
        } else {
            formData[element.id] = element.value;
        }
    });

    const jsonData = JSON.stringify(formData, null, 2);
    console.log(jsonData); // Debugging: See output in browser console

    const reportData = document.getElementById('report_data');
    if (reportData) {
        reportData.value = jsonData;
    }
});

</script>
<script>
        // Select all span elements and make them editable
        document.querySelectorAll("span").forEach(span => {
            span.setAttribute("contenteditable", "true");
        });
    </script>
    
<script>   
function updatePropertyType() {
    let dropdown = document.getElementById("property_type");
    let selectedValue = dropdown.value;
    let textArea = document.getElementById("report_description");

    // Replace "FLAT", "UNIT", or "APARTMENT" with the selected value
    textArea.value = textArea.value.replace(/\b(FLAT|UNIT|APARTMENT) NO\./, `${selectedValue} NO.`);
}
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
<script>
    function updateTextarea() {
        let textarea = document.getElementById("dag_no");
        let selectedValues = new Set(textarea.value.split("\n").filter(line => line.trim() !== "")); // Keep existing values

        document.querySelectorAll('.form-check-input').forEach(cb => {
            if (cb.checked) {
                selectedValues.add(cb.value); // Add checked values
            } else {
                selectedValues.delete(cb.value); // Remove unchecked values
            }
        });

        textarea.value = Array.from(selectedValues).join("\n"); // Convert set back to string
    }
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let doorInput = document.getElementById("dag_no");
    let reportInput = document.getElementById("report_description");
    let appendBtn = document.getElementById("append_btn");

    if (doorInput && reportInput && appendBtn) {
        appendBtn.addEventListener("click", function () {
            let newValue = doorInput.value.trim();
            if (!newValue) return; // Ignore empty input

            // Replace all [MOUZA] occurrences in the report description
            reportInput.value = reportInput.value.replace(/\[DAG\]/g, newValue);
        });
    }
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let doorInput = document.getElementById("village_mouza");
    let reportInput = document.getElementById("report_description");
    let appendBtn = document.getElementById("append_btn2");

    if (doorInput && reportInput && appendBtn) {
        appendBtn.addEventListener("click", function () {
            let newValue = doorInput.value.trim();
            if (!newValue) return; // Ignore empty input

            // Replace all [MOUZA] occurrences in the report description
            reportInput.value = reportInput.value.replace(/\[MOUZA]/g, newValue);
        });
    }
});
</script>
<script>
    


    document.addEventListener("DOMContentLoaded", function () {
        // Parse the JSON data safely
        let reportData;
        try {
            reportData = JSON.parse(@json($report->data ?? '{}');
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
function updateUnit(inputId, selectId) {
  const unit = document.getElementById(selectId).value;
  const input = document.getElementById(inputId);

  // Split value into number and unit
  let parts = input.value.trim().split(" ");
  let numericValue = parts[0];

  // If numeric value is empty, don't update
  if (!numericValue || isNaN(numericValue)) return;

  // Update with new unit
  if (unit) {
    input.value = numericValue + ' ' + unit;
  } else {
    input.value = numericValue;
  }
}
</script>

<script>
function calculateValuation() {
  const landAreaRaw = document.getElementById("Total_extent").value;
  const landArea = parseFloat(landAreaRaw.replace(/[^0-9.]/g, ''));
  const rate = parseFloat(document.getElementById("rate").value);

  if (isNaN(landArea) || isNaN(rate)) {
    alert("Please enter valid numbers for both Land Area and Rate.");
    return;
  }

  const totalValue = landArea * rate;

  // Format numbers with commas (Indian system)
  const formatter = new Intl.NumberFormat('en-IN');

  const landAreaFormatted = landArea.toFixed(2);
  const rateFormatted = "Rs. " + formatter.format(rate.toFixed(2));
  const totalFormatted = "Rs. " + formatter.format(totalValue.toFixed(2));

  const estimatedText = `Land area : ${landAreaRaw}  X @ ${rateFormatted} per Unit  = ${totalFormatted}`;

  document.getElementById("Estimated").value = estimatedText;
  document.getElementById("amount_land").value = totalFormatted;
}
</script>

<script>
function extractAmount(value) {
  // Remove non-numeric characters except decimal
  const num = parseFloat(value.replace(/[^0-9.]/g, ''));
  return isNaN(num) ? 0 : num;
}

function calculateTotal() {
  const inputs = [
    "amount_portico",
    "amount_front_door",
    "amount_sitout",
    "amount_water_tank",
    "amount_gates"
  ];

  let total = 0;

  inputs.forEach(id => {
    const val = document.getElementById(id).value;
    total += extractAmount(val);
  });

  // Format as Indian currency
  const formatter = new Intl.NumberFormat('en-IN');
  document.getElementById("amount_total").value = total === 0 ? "Nil" : "Rs. " + formatter.format(total.toFixed(2));
  document.getElementById("amount_extra_items").value = total === 0 ? "Nil" : "Rs. " + formatter.format(total.toFixed(2));
}
</script>
<script>
function extractAmount(value) {
  const num = parseFloat(value.replace(/[^0-9.]/g, ''));
  return isNaN(num) ? 0 : num;
}

function calculateAmenitiesTotal() {
  const ids = [
    "amount_wardrobes",
    "amount_glazed_tiles",
    "amount_sinks_bath_tub",
    "amount_flooring",
    "amount_interior",
    "amount_elevation",
    "amount_panelling",
    "amount_aluminium",
    "amount_hand_rails",
    "amount_false_ceiling"
  ];

  let total = 0;
  ids.forEach(id => {
    const value = document.getElementById(id).value;
    total += extractAmount(value);
  });

  const formattedTotal = total === 0 ? "Nil" : "Rs. " + new Intl.NumberFormat('en-IN').format(total.toFixed(2));
  document.getElementById("amount_amenities_total").value = formattedTotal;
  document.getElementById("amount_amenities").value = formattedTotal;
}
</script>
<script>
function extractAmount(value) {
  const num = parseFloat(value.replace(/[^0-9.]/g, ''));
  return isNaN(num) ? 0 : num;
}

function calculateMiscellaneousTotal() {
  const ids = [
    "amount_toilet_room",
    "amount_lumber_room",
    "amount_water_tank_sump",
    "amount_trees_gardening"
  ];

  let total = 0;
  ids.forEach(id => {
    const value = document.getElementById(id).value;
    total += extractAmount(value);
  });

  const formattedTotal = total === 0 ? "Nil" : "Rs. " + new Intl.NumberFormat('en-IN').format(total.toFixed(2));
  document.getElementById("amount_miscellaneous_total").value = formattedTotal;
  document.getElementById("amount_miscellaneous").value = formattedTotal;
}
</script>
<script>
function extractAmount(value) {
  const num = parseFloat(value.replace(/[^0-9.]/g, ''));
  return isNaN(num) ? 0 : num;
}

function calculateServicesTotal() {
  const ids = [
    "amount_water_supply",
    "amount_drainage",
    "amount_compound_wall",
    "amount_cb_deposits",
    "amount_pavement"
  ];

  let total = 0;
  ids.forEach(id => {
    const value = document.getElementById(id).value;
    total += extractAmount(value);
  });

  const formattedTotal = total === 0 ? "Nil" : "Rs. " + new Intl.NumberFormat('en-IN').format(total.toFixed(2));
  document.getElementById("amount_services_total").value = formattedTotal;
  document.getElementById("amount_services").value = formattedTotal;
}
</script>
<script>
function extractAmount(value) {
    const num = parseFloat(value.replace(/[^0-9.]/g, ''));
    return isNaN(num) ? 0 : num;
}

function calculateFinalTotal() {
    const ids = [
        "amount_land",
        "amount_building",
        "amount_extra_items",
        "amount_amenities",
        "amount_miscellaneous",
        "amount_services"
    ];

    let total = 0;
    ids.forEach(id => {
        const val = document.getElementById(id).value;
        total += extractAmount(val);
    });

    const formatted = total === 0 ? "Nil" : "Rs. " + new Intl.NumberFormat('en-IN').format(total.toFixed(2));
    document.getElementById("amount_total_final").value = formatted;

    // Round off to nearest thousand for 'Say'
    const rounded = total === 0 ? "Nil" : "Rs. " + new Intl.NumberFormat('en-IN').format(Math.round(total / 1000) * 1000);
    document.getElementById("amount_say").value = rounded;
}

document.addEventListener('DOMContentLoaded', function() {
    // Function to concatenate values and update inputs
    function updateConcatenatedFields() {
        const table = document.getElementById('valuationTable');
        const rows = table.querySelectorAll('tbody tr');
        
        // Initialize variables to store concatenated values
        let plinthAreaText = '';
        let numberFloorsText = '';
        
        // Process each row
        rows.forEach((row, index) => {
            const item = row.querySelector('td:nth-child(2) input').value;
            const area = row.querySelector('td:nth-child(3) input').value;
            const roof = row.querySelector('td:nth-child(4) input').value;
            
            // Concatenate values with comma separation
            if (item && area) {
                plinthAreaText += (plinthAreaText ? ', ' : '') + `${item} ${area} sq.ft.`;
            }
            
            if (item && roof) {
                numberFloorsText += (numberFloorsText ? ', ' : '') + `${item} ${roof}`;
            }
        });
        
        // Update the input fields
        document.getElementById('Plinth_area').value = plinthAreaText;
        document.getElementById('Number_floors').value = numberFloorsText;
    }
    
    // Set up event listeners for all relevant inputs
    const inputs = document.querySelectorAll('#valuationTable tbody input');
    inputs.forEach(input => {
        input.addEventListener('input', updateConcatenatedFields);
        input.addEventListener('change', updateConcatenatedFields);
    });
    
    // Initial update
    updateConcatenatedFields();
});
</script>
<script>
document.getElementById("EXTENT_SITE_2").addEventListener("input", function() {
    document.getElementById("Total_extent").value = this.value;
});
</script>
