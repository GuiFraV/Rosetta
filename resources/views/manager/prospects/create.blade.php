@extends('manager.navbar')

@section('content')
    <div class="container col-6">
        <h2>Add a new prospect</h2><br>
        @if (session()->has('message'))
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                </svg>
                <div>
                    {{ session('message') }} 
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                <p>Whooooops! Something went wrong.</p><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="post" action="{{ route('manager.prospect.store') }}">
            @csrf    
            <div class="row">
                <div class="col">
                    <label for="name" class="form-label">Company name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="col">
                    <label for="country" class="form-label">Country</label>
                    <select class="form-select" aria-label="Select" name="country">
                        <option disabled selected style="display:none">Select a country</option>
                        <option value="AX">🇦🇽 Åland Islands</option>
                        <option value="AL">🇦🇱 Albania</option>
                        <option value="AD">🇦🇩 Andorra</option>
                        <option value="AT">🇦🇹 Austria</option>
                        <option value="BY">🇧🇾 Belarus</option>
                        <option value="BE">🇧🇪 Belgium</option>
                        <option value="BA">🇧🇦 Bosnia and Herzegovina</option>
                        <option value="BG">🇧🇬 Bulgaria</option>
                        <option value="HR">🇭🇷 Croatia</option>
                        <option value="CY">🇨🇾 Cyprus</option>
                        <option value="CZ">🇨🇿 Czech Republic</option>
                        <option value="DK">🇩🇰 Denmark</option>
                        <option value="EE">🇪🇪 Estonia</option>
                        <option value="FO">🇫🇴 Faroe Islands</option>
                        <option value="FI">🇫🇮 Finland</option>
                        <option value="FR">🇫🇷 France</option>
                        <option value="DE">🇩🇪 Germany</option>
                        <option value="GI">🇬🇮 Gibraltar</option>
                        <option value="GR">🇬🇷 Greece</option>
                        <option value="GG">🇬🇬 Guernsey</option>
                        <option value="VA">🇻🇦 Holy See (Vatican City State)</option>
                        <option value="HU">🇭🇺 Hungary</option>
                        <option value="IS">🇮🇸 Iceland</option>
                        <option value="IE">🇮🇪 Ireland</option>
                        <option value="IM">🇮🇲 Isle of Man</option>
                        <option value="IT">🇮🇹 Italy</option>
                        <option value="JE">🇯🇪 Jersey</option>
                        <option value="LV">🇱🇻 Latvia</option>
                        <option value="LI">🇱🇮 Liechtenstein</option>
                        <option value="LT">🇱🇹 Lithuania</option>
                        <option value="LU">🇱🇺 Luxembourg</option>
                        <option value="MK">🇲🇰 Macedonia, the former Yugoslav Republic of</option>
                        <option value="MT">🇲🇹 Malta</option>
                        <option value="MD">🇲🇩 Moldova, Republic of</option>
                        <option value="MC">🇲🇨 Monaco</option>
                        <option value="ME">🇲🇪 Montenegro</option>
                        <option value="NL">🇳🇱 Netherlands</option>
                        <option value="NO">🇳🇴 Norway</option>
                        <option value="PL">🇵🇱 Poland</option>
                        <option value="PT">🇵🇹 Portugal</option>
                        <option value="RO">🇷🇴 Romania</option>
                        <option value="RU">🇷🇺 Russian Federation</option>
                        <option value="RS">🇷🇸 Serbia</option>
                        <option value="SK">🇸🇰 Slovakia</option>
                        <option value="SI">🇸🇮 Slovenia</option>
                        <option value="ES">🇪🇸 Spain</option>
                        <option value="SJ">🇸🇯 Svalbard and Jan Mayen</option>
                        <option value="SE">🇸🇪 Sweden</option>
                        <option value="CH">🇨🇭 Switzerland</option>
                        <option value="UA">🇺🇦 Ukraine</option>
                        <option value="GB">🇬🇧 United Kingdom</option>
                    </select>
                </div>
            </div><br>
        
            <div class="row">
                <div class="col">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="col">
                    <label for="phone" class="form-label">Phone number</label>
                    <input type="text" class="form-control" name="phone">
                </div>
            </div><br>

            <div class="row">
                <div class="col">
                    <label for="type" class="form-label">Type :&nbsp;&nbsp;&nbsp;</label>
                    @if (\App\Models\Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["type"] === "TM")
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" name="typeProspectClient" value="Client" checked>
                            <label class="form-check-label" for="typeProspectClient">Client</label>
                        </div>
                    @endif
                    @if (App\Models\Manager::with('user')->where("user_id","=",Auth::user()->id)->get()[0]["type"] === "LM")
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" name="typeProspectCarrier" value="Carrier" checked>
                            <label class="form-check-label" for="typeProspectCarrier">Carrier</label>
                        </div>
                    @endif
                </div>
            </div><br>

            <div class="row">
                <div class="col">
                    <label for="actor" class="form-label">Is a manager already prospecting this company?</label>            
                    <div class="w-50">
                        <select name="actor" class="form-select" aria-label="Select">
                            <option value="No" selected>No</option>
                            @foreach(App\Models\Manager::all() as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->first_name . " " . $manager->last_name . " (" . $manager->type . ")" }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div><br>

            <div class="float-end">
                <a href="{{ route('manager.prospect.index') }}" class="btn btn-danger">Return</a>
                <button type="submit" class="btn btn-primary">Add prospect</button>
            </div>
        </form>
    </div>
@endsection