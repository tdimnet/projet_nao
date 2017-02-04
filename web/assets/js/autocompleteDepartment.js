// Variables
$addressInput = $('#address');
$departmentInput = $('#observation_form_departement');
lattitude = $('#observation_form_latitude').val();
longitude = $('#observation_form_longitude').val();
$localizeBtn = $('#localize');


// Functions
$addressInput.focusout(function() {
    var addressDataGouvAPI = 'http://api-adresse.data.gouv.fr/search/?';
    var address = $addressInput.val();
    var APIOptions = {
        q: address,
    };

    function returnDepartement(data) {
        var postCode = data.features[0].properties.postcode;
        var postCode = postCode.slice(0, 2);
        console.log(postCode);
        $departmentInput.val(postCode);
    }
    $.getJSON(addressDataGouvAPI, APIOptions, returnDepartement);

});



$localizeBtn.click(function () {
    setTimeout(function() {
        var reverseAddressDataGouvAPI = 'http://api-adresse.data.gouv.fr/reverse/?';
        lattitude = $('#observation_form_latitude').val();
        longitude = $('#observation_form_longitude').val();
        var APIOptions = {
            lon: longitude,
            lat: lattitude,
        };

        function returnReverseDepartement(data) {
            var postCode = data.features[0].properties.postcode;
            var postCode = postCode.slice(0, 2);
            $departmentInput.val(postCode);
        }
        $.getJSON(reverseAddressDataGouvAPI, APIOptions, returnReverseDepartement);

    }, 500);

});