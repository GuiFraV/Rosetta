<html>
<head>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsEtLFAR_7CkTeUiXCYUK-lgz44Ix2Xjs&libraries=places&language=en"></script>
    <script>
        function initialize() {
            var input = document.getElementById('searchTextField');
            var id_place = "";
            var from_options = {
                componentRestrictions: { country: "DK" },
                strictBounds: true,
                types: ["(regions)"],
            };
            var autocomplete = new google.maps.places.Autocomplete(input,from_options);
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    var place = autocomplete.getPlace();
                    var place_array = place.name.split(',');
                    input.value = place_array[0];
                    document.getElementById('id').value = place.place_id;
                    
                    
                });
            
       
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
</head>
<body>
    <input id="searchTextField" type="text" size="50" placeholder="Enter a location" autocomplete="on" runat="server" />  
    <input type="text" id="id" name="id" />
</body>
</html>