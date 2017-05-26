// Countries
var specialty_arr = new Array("MET", "IET", "EMS");

// States
var subspecialty_a = new Array();
subspecialty_a[0] = "";
subspecialty_a[1] = "Computer Science|Digital Media";
subspecialty_a[2] = "Communications|Networks|Electronics";
subspecialty_a[3] = "Mechatronics|Production|Civi";



function populateSubspecialties(specialtyElementId, subspecialtyId) {

    var selectedSpecialtyIndex = document.getElementById(specialtyElementId).selectedIndex;

    var stateElement = document.getElementById(subspecialtyId);

    stateElement.length = 0; // Fixed by Julian Woods
    // stateElement.options[0] = new Option('Select Subspecialty', '');
    stateElement.selectedIndex = 0;

    var state_arr = subspecialty_a[selectedSpecialtyIndex].split("|");

    for (var i = 0; i < state_arr.length; i++) {
        stateElement.options[stateElement.length] = new Option(state_arr[i], state_arr[i]);
    }
}

function populateSpecialties(specialtyElementId, subspecialtyId) {
    // given the id of the <select> tag as function argument, it inserts <option> tags
    var specialtyElement = document.getElementById(specialtyElementId);
    specialtyElement.length = 0;
    specialtyElement.options[0] = new Option('Select Specialty', '-1');
    specialtyElement.selectedIndex = 0;
    for (var i = 0; i < specialty_arr.length; i++) {
        specialtyElement.options[specialtyElement.length] = new Option(specialty_arr[i], specialty_arr[i]);
    }

    // Assigned all countries. Now assign event listener for the states.

    if (subspecialtyId) {
        specialtyElement.onchange = function () {
            populateSubspecialties(specialtyElementId, subspecialtyId);
        };
    }
}