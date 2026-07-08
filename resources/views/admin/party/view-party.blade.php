@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] . ' ' . $type }}
@endsection
@section('content')
    
        
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $type }} List</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary" onclick="create()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add {{ $type }}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <x-filter-bar route="{{ route('parties.view', $type) }}" searchPlaceholder="Search {{ $type }}..." :sortOptions="['id' => 'ID', 'name' => 'Name', 'email' => 'Email', 'contact' => 'Contact']" :defaultSort="'id'" :defaultDirection="'DESC'" :params="['type' => $type]" />
                    <div class="table-responsive">
                        <table class="table table-vcenter table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">SL#</th>
                                    <th width="25%">Party Info</th>
                                    <th width="25%">Address</th>
                                    <th width="23%">Contact</th>
                                    <th width="12%">Type</th>
                                    <th width="5%">Status</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($parties as $i => $party)
                                <tr>
                                    <td class="text-center">{{ $parties->firstItem() + $i }}</td>
                                    <td>
                                        <b>Name: </b>{{ $party->name }}<br>
                                        <b>Code:</b> #{{ $party->code }}<br>
                                        <b>Cr. Limit: </b>{{ Session::get('companySettings')[0]['currency'] ?? '' }} {{ $party->credit_limit }}<br>
                                        <b>Method: </b>{{ $party->customer_type }}
                                    </td>
                                    <td>
                                        <b>Address: </b>{{ $party->address }}<br>
                                        <b>District: </b>{{ $party->district }}<br>
                                        <b>Country: </b>{{ $party->country_name }}
                                    </td>
                                    <td>
                                        <b>Phone: </b>{{ $party->contact }}<br>
                                        <b>Alt: </b>{{ $party->alternate_contact }}<br>
                                        <b>Email:</b>{{ $party->email }}
                                    </td>
                                    <td>{{ $party->party_type }}</td>
                                    <td class="text-center">
                                        @if ($party->status == 'Active')
                                        <i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $party->status }}"></i>
                                        @else
                                        <i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $party->status }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                 <i class="fas fa-cog"></i>
                                             </button>
                                             <div class="dropdown-menu dropdown-menu-end">
                                                 <a class="dropdown-item" href="#" onclick="editParty({{ $party->id }})"><i class="fas fa-edit me-2"></i> Edit</a>
                                                 <a class="dropdown-item" href="#" onclick="updateDue({{ $party->id }})"><i class="fas fa-edit me-2"></i> Update Due</a>
                                                 <a class="dropdown-item" href="#" onclick="confirmDelete({{ $party->id }})"><i class="fas fa-trash-alt me-2"></i> Delete</a>
                                             </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No {{ $type }} found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $parties->links() }}
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    
    <div class="modal fade" id="editOpeningDueModal">
        <div class="modal-dialog" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Opening Due</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true"> x </button>
                </div>
                <div class="modal-body">

                    <form id="editOpeningDueForm" method="POST" enctype="multipart/form-data" action="#">
                        @csrf
                        <div class="row g-3">
                            
                            <div class="form-group mb-3 col-md-12">
                                <input type="hidden" name="editOpeningDueId" id="editOpeningDueId">
                                <label> Party Name <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editOpeningDuePartyName" type="text" name="editOpeningDuePartyName" disabled>
                                <span class="text-danger" id="editOpeningDuePartyNameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label> Opening Due </label>
                                <input class="form-control input-sm" id="editOpeningDueInsert" type="number" name="editOpeningDueInsert">
                                <span class="text-danger" id="editOpeningDueInsertError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label> Due Type </label>
                                <select id="editOpeningDueType" name="editOpeningDueType" class="form-control input-sm">
                                    <option value="due">Due</option>
                                    <option value="advance">Advance</option>
                                </select>
                                <span class="text-danger" id="editOpeningDueTypeError"></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">x Close</button>
                            <button type="submit" class="btn btn-primary " id="saveOpeningDue"><i class="fa fa-save me-1"></i>Update Opening Due</button>
                        </div>
                    </form>
                    <table>
                        <thead>
                            <tr>
                                <th>Party Name</th>
                                <th>Opening Due</th>
                                <th>Current Due</th>
                            </tr>
                        </thead>
                        <tbody id="initialPartyDue"></tbody>
                    </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>





    <!-- modal -->
    <div class="modal fade" id="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="partyForm" method="POST" enctype="multipart/form-data" action="#">
                    <div class="modal-header">
                        <h4 class="modal-title float-left"> Add {{ $type }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    </div>
                    <div class="modal-body">

                        <div class="row g-3">
                            @csrf

                            <input type="hidden" name="id">

                            <div class="form-group mb-3 col-md-6">
                                <label> {{ $type }} Name <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="name" type="text" name="name" placeholder=" Write {{ $type }} name"/>
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label> Email <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="email" type="text" name="email"  placeholder="Write {{ $type }} valid email" />
                                <span class="text-danger" id="emailError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Contact Person <span class="text-danger"> * </span> </label>
                                <input class="form-control input-sm" id="contact_person" type="text" name="contact_person" placeholder=" Write contact person" />
                                <span class="text-danger" id="contact_personError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label> Mobile No <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="contact" type="text" name="contact" placeholder=" Write {{ $type }} Mobile No" />
                                <span class="text-danger" id="contactError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label> Country <span class="text-danger"> * </span></label>
                                <select class="form-control input-sm" id="country_name" type="text" name="country_name">

                                    <option value="Afganistan">Afghanistan</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option selected value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire">Bonaire</option>
                                    <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                    <option value="Brunei">Brunei</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Canary Islands">Canary Islands</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Channel Islands">Channel Islands</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos Island">Cocos Island</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote DIvoire">Cote DIvoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curaco">Curacao</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="East Timor">East Timor</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands">Falkland Islands</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Ter">French Southern Ter</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Great Britain">Great Britain</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Hawaii">Hawaii</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="India">India</option>
                                    <option value="Iran">Iran</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea North">Korea North</option>
                                    <option value="Korea Sout">Korea South</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Laos">Laos</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libya">Libya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macau">Macau</option>
                                    <option value="Macedonia">Macedonia</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Midway Islands">Midway Islands</option>
                                    <option value="Moldova">Moldova</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Nambia">Nambia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherland Antilles">Netherland Antilles</option>
                                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                    <option value="Nevis">Nevis</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau Island">Palau Island</option>
                                    <option value="Palestine">Palestine</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Phillipines">Philippines</option>
                                    <option value="Pitcairn Island">Pitcairn Island</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                                    <option value="Republic of Serbia">Republic of Serbia</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russia">Russia</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="St Barthelemy">St Barthelemy</option>
                                    <option value="St Eustatius">St Eustatius</option>
                                    <option value="St Helena">St Helena</option>
                                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                    <option value="St Lucia">St Lucia</option>
                                    <option value="St Maarten">St Maarten</option>
                                    <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                    <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                    <option value="Saipan">Saipan</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="Samoa American">Samoa American</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syria">Syria</option>
                                    <option value="Tahiti">Tahiti</option>
                                    <option value="Taiwan">Taiwan</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Erimates">United Arab Emirates</option>
                                    <option value="United States of America">United States of America</option>
                                    <option value="Uraguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Vatican City State">Vatican City State</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                    <option value="Wake Island">Wake Island</option>
                                    <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zaire">Zaire</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                                <span class="text-danger" id="country_nameError"></span>
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label> Alternate Contact No</label>
                                <input class="form-control input-sm" id="alternate_contact" type="text" name="alternate_contact" placeholder=" Write Alter Mobile No"/>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label> District <span class="text-danger"> * </span></label>
                                <select class="form-control input-sm" id="district" type="text" name="district">
                                    <option value="" selected disabled>Choose District</option>
                                    <option value="Bagerhat">Bagerhat</option>
                                    <option value="Bandarban">Bandarban</option>
                                    <option value="Barguna">Barguna</option>
                                    <option value="Barisal">Barisal</option>
                                    <option value="Bhola">Bhola</option>
                                    <option value="Bogra">Bogra</option>
                                    <option value="Brahmanbaria">Brahmanbaria</option>
                                    <option value="Chandpur">Chandpur</option>
                                    <option selected value="Chittagong">Chittagong</option>
                                    <option value="Chuadanga">Chuadanga</option>
                                    <option value="Comilla">Comilla</option>
                                    <option value="Cox'sBazar">Cox'sBazar</option>
                                    <option value="Dhaka">Dhaka</option>
                                    <option value="Dinajpur">Dinajpur</option>
                                    <option value="Faridpur">Faridpur</option>
                                    <option value="Feni">Feni</option>
                                    <option value="Gaibandha">Gaibandha</option>
                                    <option value="Gazipur">Gazipur</option>
                                    <option value="Gopalganj">Gopalganj</option>
                                    <option value="Habiganj">Habiganj</option>
                                    <option value="Jaipurhat">Jaipurhat</option>
                                    <option value="Jamalpur">Jamalpur</option>
                                    <option value="Jessore">Jessore</option>
                                    <option value="Jhalokati">Jhalokati</option>
                                    <option value="Jhenaidah">Jhenaidah</option>
                                    <option value="Khagrachari">Khagrachari</option>
                                    <option value="Khulna">Khulna</option>
                                    <option value="Kishoreganj">Kishoreganj</option>
                                    <option value="Kurigram">Kurigram</option>
                                    <option value="Kushtia">Kushtia</option>
                                    <option value="Lakshmipur">Lakshmipur</option>
                                    <option value="Lalmonirhat">Lalmonirhat</option>
                                    <option value="Madaripur">Madaripur</option>
                                    <option value="Magura">Magura</option>
                                    <option value="Manikganj">Manikganj</option>
                                    <option value="Maulvibazar">Maulvibazar</option>
                                    <option value="Meherpur">Meherpur</option>
                                    <option value="Munshiganj">Munshiganj</option>
                                    <option value="Mymensingh">Mymensingh</option>
                                    <option value="Naogaon">Naogaon</option>
                                    <option value="Narail">Narail</option>
                                    <option value="Narayanganj">Narayanganj</option>
                                    <option value="Narsingdi">Narsingdi</option>
                                    <option value="Natore">Natore</option>
                                    <option value="Nawabganj">Nawabganj</option>
                                    <option value="Netrokona">Netrokona</option>
                                    <option value="Nilphamari">Nilphamari</option>
                                    <option value="Noakhali">Noakhali</option>
                                    <option value="Pabna">Pabna</option>
                                    <option value="Panchagarh">Panchagarh</option>
                                    <option value="Patuakhali">Patuakhali</option>
                                    <option value="Pirojpur">Pirojpur</option>
                                    <option value="Rajbari">Rajbari</option>
                                    <option value="Rajshahi">Rajshahi</option>
                                    <option value="Rangamati">Rangamati</option>
                                    <option value="Rangpur">Rangpur</option>
                                    <option value="Satkhira">Satkhira</option>
                                    <option value="Shariatpur">Shariatpur</option>
                                    <option value="Sherpur">Sherpur</option>
                                    <option value="Sirajganj">Sirajganj</option>
                                    <option value="Sunamganj">Sunamganj</option>
                                    <option value="Sylhet">Sylhet</option>
                                    <option value="Tangail">Tangail</option>
                                    <option value="Thakurgaon">Thakurgaon</option>
                                </select>
                                 <span class="text-danger" id="districtError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label> Credit limit <span class="text-danger"> * </span></label>
                                <input id="credit_limit" type="text" name="credit_limit" class="form-control input-sm" value="0" placeholder=" Write Credit Limit"/>
                                 <span class="text-danger" id="credit_limitError"></span>
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label>Customer Type <span class="text-danger"> * </span></label>
                                <select id="party_variety" name="party_variety" class="form-control input-sm">
                                    <option value="">Choose party type</option>
                                    <option value="Cash"> Cash</option>
                                    <option value="Regular">Regular</option>
                                </select>
                                <span class="text-danger" id="party_varietyError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label>Address <span class="text-danger"> * </span></label>
                                <textarea class="form-control input-sm" id="address" type="text" name="address" placeholder="Write {{ $type }} address">  </textarea>
                                <span class="text-danger" id="addressError"></span>
                            </div>

                            <input type="hidden" name="party_type" id="party_type" value="{{ $type }}" />

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">x Close</button>
                        <button type="submit" class="btn btn-primary btnSave" id="saveCategory">Save</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- edit modal -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editPartyForm" method="POST" enctype="multipart/form-data" action="#">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit {{ $type }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row g-3">
                            @csrf
                            <input type="hidden" name="editId" id="editId">
                            @if($type == 'Walkin_Customer')
                                <input type="hidden" name="editParty" id="editParty" value="{{ $type }}">
                            @endif
                            <div class="form-group mb-3 col-md-6">
                                <label>{{ $type }} Name <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editName" type="text" name="editName" required="">
                                <span class="text-danger" id="editNameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label> Email <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editEmail" type="text" name="editEmail">
                                <span class="text-danger" id="editEmailError"></span>
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label>Contact Person <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editContact_person" type="text"  name="editContact_person">
                                <span class="text-danger" id="editContact_personError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Contact Number <span class="text-danger"> * </span> </label>
                                <input class="form-control input-sm" id="editContact" type="text" name="editContact">
                                <span class="text-danger" id="editContactError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Country</label>
                                <select class="form-control input-sm" id="editCountry_name" type="text"
                                    name="editCountry_name">

                                    <option value="Afganistan">Afghanistan</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option selected value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire">Bonaire</option>
                                    <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                    <option value="Brunei">Brunei</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Canary Islands">Canary Islands</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Channel Islands">Channel Islands</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos Island">Cocos Island</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote DIvoire">Cote DIvoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curaco">Curacao</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="East Timor">East Timor</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands">Falkland Islands</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Ter">French Southern Ter</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Great Britain">Great Britain</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Hawaii">Hawaii</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="India">India</option>
                                    <option value="Iran">Iran</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea North">Korea North</option>
                                    <option value="Korea Sout">Korea South</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Laos">Laos</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libya">Libya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macau">Macau</option>
                                    <option value="Macedonia">Macedonia</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Midway Islands">Midway Islands</option>
                                    <option value="Moldova">Moldova</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Nambia">Nambia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherland Antilles">Netherland Antilles</option>
                                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                    <option value="Nevis">Nevis</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau Island">Palau Island</option>
                                    <option value="Palestine">Palestine</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Phillipines">Philippines</option>
                                    <option value="Pitcairn Island">Pitcairn Island</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                                    <option value="Republic of Serbia">Republic of Serbia</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russia">Russia</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="St Barthelemy">St Barthelemy</option>
                                    <option value="St Eustatius">St Eustatius</option>
                                    <option value="St Helena">St Helena</option>
                                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                    <option value="St Lucia">St Lucia</option>
                                    <option value="St Maarten">St Maarten</option>
                                    <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                    <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                    <option value="Saipan">Saipan</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="Samoa American">Samoa American</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syria">Syria</option>
                                    <option value="Tahiti">Tahiti</option>
                                    <option value="Taiwan">Taiwan</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Erimates">United Arab Emirates</option>
                                    <option value="United States of America">United States of America</option>
                                    <option value="Uraguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Vatican City State">Vatican City State</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                    <option value="Wake Island">Wake Island</option>
                                    <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zaire">Zaire</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                                <span class="text-danger" id="country_nameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label> Alternate Contact</label>
                                <input class="form-control input-sm" id="editAlternate" type="text" name="editAlternate">
                                <span class="text-danger" id="editAlternateError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label> District</label>
                                <select class="form-control input-sm" id="editDistrict" type="text" name="editDistrict">
                                    <option value="" selected disabled>Choose District</option>
                                    <option value="Bagerhat">Bagerhat</option>
                                    <option value="Bandarban">Bandarban</option>
                                    <option value="Barguna">Barguna</option>
                                    <option value="Barisal">Barisal</option>
                                    <option value="Bhola">Bhola</option>
                                    <option value="Bogra">Bogra</option>
                                    <option value="Brahmanbaria">Brahmanbaria</option>
                                    <option value="Chandpur">Chandpur</option>
                                    <option selected value="Chittagong">Chittagong</option>
                                    <option value="Chuadanga">Chuadanga</option>
                                    <option value="Comilla">Comilla</option>
                                    <option value="Cox'sBazar">Cox'sBazar</option>
                                    <option value="Dhaka">Dhaka</option>
                                    <option value="Dinajpur">Dinajpur</option>
                                    <option value="Faridpur">Faridpur</option>
                                    <option value="Feni">Feni</option>
                                    <option value="Gaibandha">Gaibandha</option>
                                    <option value="Gazipur">Gazipur</option>
                                    <option value="Gopalganj">Gopalganj</option>
                                    <option value="Habiganj">Habiganj</option>
                                    <option value="Jaipurhat">Jaipurhat</option>
                                    <option value="Jamalpur">Jamalpur</option>
                                    <option value="Jessore">Jessore</option>
                                    <option value="Jhalokati">Jhalokati</option>
                                    <option value="Jhenaidah">Jhenaidah</option>
                                    <option value="Khagrachari">Khagrachari</option>
                                    <option value="Khulna">Khulna</option>
                                    <option value="Kishoreganj">Kishoreganj</option>
                                    <option value="Kurigram">Kurigram</option>
                                    <option value="Kushtia">Kushtia</option>
                                    <option value="Lakshmipur">Lakshmipur</option>
                                    <option value="Lalmonirhat">Lalmonirhat</option>
                                    <option value="Madaripur">Madaripur</option>
                                    <option value="Magura">Magura</option>
                                    <option value="Manikganj">Manikganj</option>
                                    <option value="Maulvibazar">Maulvibazar</option>
                                    <option value="Meherpur">Meherpur</option>
                                    <option value="Munshiganj">Munshiganj</option>
                                    <option value="Mymensingh">Mymensingh</option>
                                    <option value="Naogaon">Naogaon</option>
                                    <option value="Narail">Narail</option>
                                    <option value="Narayanganj">Narayanganj</option>
                                    <option value="Narsingdi">Narsingdi</option>
                                    <option value="Natore">Natore</option>
                                    <option value="Nawabganj">Nawabganj</option>
                                    <option value="Netrokona">Netrokona</option>
                                    <option value="Nilphamari">Nilphamari</option>
                                    <option value="Noakhali">Noakhali</option>
                                    <option value="Pabna">Pabna</option>
                                    <option value="Panchagarh">Panchagarh</option>
                                    <option value="Patuakhali">Patuakhali</option>
                                    <option value="Pirojpur">Pirojpur</option>
                                    <option value="Rajbari">Rajbari</option>
                                    <option value="Rajshahi">Rajshahi</option>
                                    <option value="Rangamati">Rangamati</option>
                                    <option value="Rangpur">Rangpur</option>
                                    <option value="Satkhira">Satkhira</option>
                                    <option value="Shariatpur">Shariatpur</option>
                                    <option value="Sherpur">Sherpur</option>
                                    <option value="Sirajganj">Sirajganj</option>
                                    <option value="Sunamganj">Sunamganj</option>
                                    <option value="Sylhet">Sylhet</option>
                                    <option value="Tangail">Tangail</option>
                                    <option value="Thakurgaon">Thakurgaon</option>
                                </select>
                            </div>

                            <div class="form-group mb-3 col-md-6">
                                <label> Status</label>
                                <select id="editStatus" name="editStatus" class="form-control input-sm">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label> Credit limit</label>
                                <input id="editCredit_limit" name="editCredit_limit" class="form-control input-sm">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label>Customer Type</label>
                                <select id="editParty_variety" name="editParty_variety" class="form-control input-sm">
                                    <option value="">Choose party type</option>
                                    <option value="Cash"> Cash</option>
                                    <option value="Regular">Regular</option>
                                </select>
                            </div>
                            @if($type != 'Walkin_Customer')
                            <div class="form-group mb-3 col-md-6">
                                <label>Party Type</label>
                                <select id="editParty" name="editParty" class="form-control input-sm">
                                    <option value="Supplier">Supplier</option>
                                    <option value="Customer">Customer</option>
                                    <option value="Both">Both</option>
                                </select>
                            </div>
                            @endif
                            <div class="form-group mb-3 col-md-12">
                                <label>Address <span class="text-danger"> * </span></label>
                                <textarea class="form-control input-sm" id="editAddress" type="text" name="editAddress"></textarea>
                                <span class="text-danger" id="editaddressError"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">x Close</button>
                        <button type="submit" class="btn btn-primary btnUpate" id="editCategory">Update</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('javascript')
    <script>
        $(function() {
            $("#country_name").select2({
                width: '100%'
            });
            $("#district").select2({
                width: '100%'
            });
            $("#party_variety").select2({
                width: '100%'
            });

        });

        function create() {
            reset();
            $("#modal").modal('show');
        }
        $('#modal').on('shown.bs.modal', function() {
            $('#name').focus();
        })
        $('#editModal').on('shown.bs.modal', function() {
            $('#editName').focus();
        })
        $(document).ready(function() {
        });





        function updateDue(partyId){
            //editDueClearMessage();
            $.ajax({
                url: "{{ route('editParty') }}",
                method: "GET",
                data: {
                    "id": partyId
                },
                datatype: "json",
                success: function(result) {
                   
                    $("#editOpeningDueModal").modal('show');
                    $("#editOpeningDueId").val(result.id);
                    $("#editOpeningDuePartyName").val(result.name);
                    $("#editOpeningDueInsert").val(result.opening_due);
                    $("#initialPartyDue").html('<tr><td>'+result.name+'</td><td>'+result.opening_due+'</td><td>'+result.current_due+'</td></tr>');
                  
                },
                error: function(response) {
                    alert(JSON.stringify(response));

                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        }
      



        $("#partyForm").submit(function(e) {
            e.preventDefault();
            clearMessages();
            var name = $("#name").val();
            var code = $("#code").val();
            var email = $("#email").val();
            var contact_person = $("#contact_person").val();
            var country_name = $("#country_name").val();
            var district = $("#district").val();
            var party_variety = $("#party_variety").val();
            var address = $("#address").val();
            var contact = $("#contact").val();
            var alternate_contact = $("#alternate_contact").val();
            var credit_limit = $("#credit_limit").val();
            var party_type = $("#party_type").val();
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('name', name);
            fd.append('code', code);
            fd.append('contact_person', contact_person);
            fd.append('country_name', country_name);
            fd.append('district', district);
            fd.append('email', email);
            fd.append('address', address);
            fd.append('contact', contact);
            fd.append('alternate_contact', alternate_contact);
            fd.append('party_variety', party_variety);
            fd.append('credit_limit', credit_limit);
            fd.append('party_type', party_type);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ url('parties/store') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    //alert(JSON.stringify(result));
                    $("#modal").modal('hide');
                    Swal.fire("Saved!", result.success, "success");
                    location.reload();
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                    $('#nameError').text(response.responseJSON.errors.name);
                    $('#emailError').text(response.responseJSON.errors.email);
                    $('#contact_personError').text(response.responseJSON.errors.contact_person);
                    $('#contactError').text(response.responseJSON.errors.contact);
                    $('#country_nameError').text(response.responseJSON.errors.country_name);
                    $('#districtError').text(response.responseJSON.errors.district);
                    $('#credit_limitError').text(response.responseJSON.errors.credit_limit);
                    $('#party_varietyError').text(response.responseJSON.errors.party_variety);
                    $('#addressError').text(response.responseJSON.errors.address);
                    $('#partyError').text(response.responseJSON.errors.party_type);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }

            })
        })

        function clearMessages() {
            $('#nameError').text("");
            $('#emailError').text("");
            $('#contact_personError').text("");
            $('#addressError').text("");
            $('#country_nameError').text("");
            $('#districtError').text("");
            $('#contactError').text("");
            $('#credit_limitError').text("");
            $('#party_varietyError').text("");
            $('#creditError').text("");
            $('#partyError').text("");
        }

        function editClearMessages() {
            $('#editNameError').text("");
            $('#editcodeError').text("");
            $('#editaddressError').text("");
            $('#editContactError').text("");
            $('#editAlternateError').text("");
            $('#editCreditError').text("");
            $('#editPartyError').text("");
        }

        function reset() {
            $("#name").val("");
            $("#code").val("");
            $("#address").val("");
            $("#contact").val("");
            $("#alternate_contact").val("");
            // $("#credit_limt").val("");
        }

        function editReset() {
            $("#editName").val("");
            $("#editCode").val("");
            $("#editAddress").val("");
            $("#editContact").val("");
            $("#editAlternate").val("");
            $("#editCredit").val("");
            $("#editStatus").val("");
        }

        function editParty(id) {
            editReset();
            editClearMessages();
            $.ajax({
                url: "{{ route('editParty') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    $("#editModal").modal('show');
                    $("#editName").val(result.name);
                    $("#editContact_person").val(result.contact_person);
                    $("#editEmail").val(result.email);
                    $("#editCountry_name").val(result.country_name);
                    $("#editDistrict").val(result.district);
                    $("#editParty_variety").val(result.customer_type).trigger("change");
                    if(result.party_type != 'Walkin_Cusstomer'){
                        $("#editParty").val(result.party_type).trigger("change");
                    }
                    else{
                        $("#editParty").val(result.party_type);
                    }
                    $("#editStatus").val(result.status).trigger("change");
                    $("#editCode").val(result.code);
                    $("#editCredit_limit").val(result.credit_limit);
                    $("#editAddress").val(result.address);
                    $("#editContact").val(result.contact);
                    $("#editAlternate").val(result.alternate_contact);
                    
                    $("#editId").val(result.id);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        }

        $("#editPartyForm").submit(function(e) {
            e.preventDefault();
            editClearMessages();
            var name = $("#editName").val();
            var contact_person = $("#editContact_person").val();
            var email = $("#editEmail").val();
            var country_name = $("#editCountry_name").val();
            var district = $("#editDistrict").val();
            var party_variety = $("#editParty_variety").val();
            var party_type = $("#editParty").val();
            var address = $("#editAddress").val();
            var contact = $("#editContact").val();
            var alternate_contact = $("#editAlternate").val();
            //var party_type = $("#editParty").val();
            var credit_limit = $("#editCredit_limit").val();
            var status = $("#editStatus").val();
            var _token = $('input[name="_token"]').val();
            var id = $("#editId").val();
            var fd = new FormData();
            fd.append('name', name);
            fd.append('email', email);
            fd.append('contact_person', contact_person);
            fd.append('country_name', country_name);
            fd.append('district', district);
            fd.append('party_variety', party_variety);

            fd.append('address', address);
            fd.append('contact', contact);
            fd.append('alternate_contact', alternate_contact);
            fd.append('credit_limit', credit_limit);
            fd.append('party_type', party_type);
            fd.append('status', status);
            fd.append('id', id);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('updateParty') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    //alert(JSON.stringify(result));
                    $("#editModal").modal('hide');
                    Swal.fire("Updated Party!", result.success, "success");
                    location.reload();
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                    $('#editNameError').text(response.responseJSON.errors.name);
                    $('#editcodeError').text(response.responseJSON.errors.code);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            })
        });




        $("#editOpeningDueForm").submit(function(e) {
            e.preventDefault();
            var partyId = $("#editOpeningDueId").val();
            var openingDue = $("#editOpeningDueInsert").val();
            var dueType = $("#editOpeningDueType").val();
            var _token = $('input[name="_token"]').val();
            var id = $("#editId").val();
            var fd = new FormData();
            fd.append('partyId', partyId);
            fd.append('openingDue', openingDue);
            fd.append('dueType', dueType);
           
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('updatePartyOpeningDue') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#editOpeningDueForm").modal('hide');
                    Swal.fire("Updated Stock!", result.success, "success");
                    location.reload();
                },
                error: function(response) {
                    Swal.fire("Updated Stock Error!", JSON.stringify(response), "error");
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            })
        })
        function confirmDelete(id) {
            confirmDeleteSwal({
                url      : "{{ route('partyDelete') }}",
                id       : id,
                itemName : 'Party',
                onSuccess: function(result) {
                    Swal.fire("Done!", result.success, "success");
                    location.reload();
                },
            });
        }

        Mousetrap.bind('ctrl+shift+n', function(e) {
            e.preventDefault();
            if ($('#modal.in, #modal.show').length) {

            } else {
                create();
            }
        });

        function reloadDt() {
            if ($('#modal.in, #modal.show').length) {

            } else if ($('#editModal.in, #editModal.show').length) {

            } else {
                location.reload();
            }
        }
        Mousetrap.bind('ctrl+shift+r', function(e) {
            e.preventDefault();
            reloadDt();
        });
        Mousetrap.bind('ctrl+shift+s', function(e) {
            e.preventDefault();
            if ($('#modal.in, #modal.show').length) {
                $("#partyForm").trigger('submit');
            } else {
                alert("Not Calling");
            }
        });
        Mousetrap.bind('ctrl+shift+u', function(e) {
            e.preventDefault();
            if ($('#editModal.in, #editModal.show').length) {
                $("#editPartyForm").trigger('submit');
            } else {
                alert("Not Calling");
            }
        });
        Mousetrap.bind('esc', function(e) {
            e.preventDefault();
            if ($('#editModal.in, #editModal.show').length) {
                $("#editModal").modal('hide');
            } else if ($('#modal.in, #modal.show').length) {
                $('#modal').modal('hide');
            }
        });
    </script>
@endsection
