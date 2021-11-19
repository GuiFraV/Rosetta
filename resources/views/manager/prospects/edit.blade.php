@extends('manager.navbar')

@section('content')
    @if (Auth::user()->id_manager != $prospect->creator && Auth::user()->isAdmin === 0) 
        {{ app()->call('App\Http\Controllers\ProspectController@show',  ["prospect" => $prospect]); }}
    @endif
    <div class="container col-6">
        <h2>Edit an existing prospect</h2><br>
        @if (session()->has('message'))
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">
                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                </svg>
                {{ session('message') }}
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
        <form action="{{ route('manager.prospect.update', $prospect->id) }}" method="post">
            @csrf
            @method('put')
            <div class="row">
                <div class="col">
                    <label for="name" class="form-label">Company name</label>
                    <input type="text" class="form-control" name="name" value="{{$prospect->name}}" required>
                </div>
                <div class="col">
                    <label for="country" class="form-label">Country</label>
                    <select class="form-select" aria-label="Select" name="country">
                        <option value="AX" @if ($prospect->country === "AX") selected @endif>🇦🇽 Åland Islands</option>
                        <option value="AL" @if ($prospect->country === "AL") selected @endif>🇦🇱 Albania</option>
                        <option value="AD" @if ($prospect->country === "AD") selected @endif>🇦🇩 Andorra</option>
                        <option value="AT" @if ($prospect->country === "AT") selected @endif>🇦🇹 Austria</option>
                        <option value="BY" @if ($prospect->country === "BY") selected @endif>🇧🇾 Belarus</option>
                        <option value="BE" @if ($prospect->country === "BE") selected @endif>🇧🇪 Belgium</option>
                        <option value="BA" @if ($prospect->country === "BA") selected @endif>🇧🇦 Bosnia and Herzegovina</option>
                        <option value="BG" @if ($prospect->country === "BG") selected @endif>🇧🇬 Bulgaria</option>
                        <option value="HR" @if ($prospect->country === "HR") selected @endif>🇭🇷 Croatia</option>
                        <option value="CY" @if ($prospect->country === "CY") selected @endif>🇨🇾 Cyprus</option>
                        <option value="CZ" @if ($prospect->country === "CZ") selected @endif>🇨🇿 Czech Republic</option>
                        <option value="DK" @if ($prospect->country === "DK") selected @endif>🇩🇰 Denmark</option>
                        <option value="EE" @if ($prospect->country === "EE") selected @endif>🇪🇪 Estonia</option>
                        <option value="FO" @if ($prospect->country === "FO") selected @endif>🇫🇴 Faroe Islands</option>
                        <option value="FI" @if ($prospect->country === "FI") selected @endif>🇫🇮 Finland</option>
                        <option value="FR" @if ($prospect->country === "FR") selected @endif>🇫🇷 France</option>
                        <option value="DE" @if ($prospect->country === "DE") selected @endif>🇩🇪 Germany</option>
                        <option value="GI" @if ($prospect->country === "GI") selected @endif>🇬🇮 Gibraltar</option>
                        <option value="GR" @if ($prospect->country === "GR") selected @endif>🇬🇷 Greece</option>
                        <option value="GG" @if ($prospect->country === "GG") selected @endif>🇬🇬 Guernsey</option>
                        <option value="VA" @if ($prospect->country === "VA") selected @endif>🇻🇦 Holy See (Vatican City State)</option>
                        <option value="HU" @if ($prospect->country === "HU") selected @endif>🇭🇺 Hungary</option>
                        <option value="IS" @if ($prospect->country === "IS") selected @endif>🇮🇸 Iceland</option>
                        <option value="IE" @if ($prospect->country === "IE") selected @endif>🇮🇪 Ireland</option>
                        <option value="IM" @if ($prospect->country === "IM") selected @endif>🇮🇲 Isle of Man</option>
                        <option value="IT" @if ($prospect->country === "IT") selected @endif>🇮🇹 Italy</option>
                        <option value="JE" @if ($prospect->country === "JE") selected @endif>🇯🇪 Jersey</option>
                        <option value="LV" @if ($prospect->country === "LV") selected @endif>🇱🇻 Latvia</option>
                        <option value="LI" @if ($prospect->country === "LI") selected @endif>🇱🇮 Liechtenstein</option>
                        <option value="LT" @if ($prospect->country === "LT") selected @endif>🇱🇹 Lithuania</option>
                        <option value="LU" @if ($prospect->country === "LU") selected @endif>🇱🇺 Luxembourg</option>
                        <option value="MK" @if ($prospect->country === "MK") selected @endif>🇲🇰 Macedonia, the former Yugoslav Republic of</option>
                        <option value="MT" @if ($prospect->country === "MT") selected @endif>🇲🇹 Malta</option>
                        <option value="MD" @if ($prospect->country === "MD") selected @endif>🇲🇩 Moldova, Republic of</option>
                        <option value="MC" @if ($prospect->country === "MC") selected @endif>🇲🇨 Monaco</option>
                        <option value="ME" @if ($prospect->country === "ME") selected @endif>🇲🇪 Montenegro</option>
                        <option value="NL" @if ($prospect->country === "NL") selected @endif>🇳🇱 Netherlands</option>
                        <option value="NO" @if ($prospect->country === "NO") selected @endif>🇳🇴 Norway</option>
                        <option value="PL" @if ($prospect->country === "PL") selected @endif>🇵🇱 Poland</option>
                        <option value="PT" @if ($prospect->country === "PT") selected @endif>🇵🇹 Portugal</option>
                        <option value="RO" @if ($prospect->country === "RO") selected @endif>🇷🇴 Romania</option>
                        <option value="RU" @if ($prospect->country === "RU") selected @endif>🇷🇺 Russian Federation</option>
                        <option value="RS" @if ($prospect->country === "RS") selected @endif>🇷🇸 Serbia</option>
                        <option value="SK" @if ($prospect->country === "SK") selected @endif>🇸🇰 Slovakia</option>
                        <option value="SI" @if ($prospect->country === "SI") selected @endif>🇸🇮 Slovenia</option>
                        <option value="ES" @if ($prospect->country === "ES") selected @endif>🇪🇸 Spain</option>
                        <option value="SJ" @if ($prospect->country === "SJ") selected @endif>🇸🇯 Svalbard and Jan Mayen</option>
                        <option value="SE" @if ($prospect->country === "SE") selected @endif>🇸🇪 Sweden</option>
                        <option value="CH" @if ($prospect->country === "CH") selected @endif>🇨🇭 Switzerland</option>
                        <option value="UA" @if ($prospect->country === "UA") selected @endif>🇺🇦 Ukraine</option>
                        <option value="GB" @if ($prospect->country === "GB") selected @endif>🇬🇧 United Kingdom</option>
                    </select>
                </div>
            </div><br>
            <div class="row">
                <div class="col">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="{{$prospect->email}}">
                </div>
                <div class="col">
                    <label for="phone" class="form-label">Phone number</label>
                    <input type="text" class="form-control" name="phone" value="{{$prospect->phone}}">
                </div>
            </div><br>
            <div class="row">
                <div class="col">
                    <label for="type" class="form-label">Type :&nbsp;&nbsp;&nbsp;</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" name="typeProspectClient" value="Client" @if ($prospect->type === "Client") checked @endif>
                        <label class="form-check-label" for="typeProspectClient">Client</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" name="typeProspectCarrier" value="Carrier" @if ($prospect->type === "Carrier") checked @endif>
                        <label class="form-check-label" for="typeProspectCarrier">Carrier</label>
                    </div>
                </div>
            </div><br>
            <div class="float-end">
                <a href="{{ route('manager.prospect.show', $prospect->id) }}" class="btn btn-danger">Return</a>
                <button type="submit" class="btn btn-primary">Update prospect</button>
            </div>
        </form>
    </div>
@endsection