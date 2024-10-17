// import './bootstrap.js';
// /*
//  * Welcome to your app's main JavaScript file!
//  *
//  * This file will be included onto the page via the importmap() Twig function,
//  * which should already be in your base.html.twig.
//  */
// import './styles/app.css';

// import 'flowbite';


// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
document.addEventListener('DOMContentLoaded', function () {
    const toggleSwitch = document.getElementById('toggleSwitch');
    const toggleDiv = document.getElementById('toggleDiv');
    const userFields = toggleDiv.querySelectorAll('input');





    // Function to enable or disable user fields based on the toggle
    const toggleUserFields = (isVisible) => {
        userFields.forEach(input => {
            if (isVisible) {
                input.removeAttribute('disabled');
            } else {
                input.setAttribute('disabled', 'disabled');
                input.value = ''; // Optionally clear the input values
            }
        });
    };


    // Initial setup
    if (toggleSwitch.checked) {
        toggleDiv.classList.remove('hidden');
        console.log('Checked');
        toggleUserFields(true);
    } else {
        console.log('Checked');
        toggleDiv.classList.add('hidden');
        toggleUserFields(false);
    }

    toggleSwitch.addEventListener('change', function () {
        const isChecked = this.checked;
        toggleDiv.classList.toggle('hidden', !isChecked);
        toggleUserFields(isChecked);
    });



    // POUR LE FILTRE DE LA DÉTTE
    const formDette = document.getElementById('formulaireDette');
    const form = document.getElementById('form');

    formDette.addEventListener('onchange', function () {
        form.submit();
    })

    // POUR LE FILTRE DU CLIENT
    const formClient = document.getElementById('form-account');
    const form2 = document.getElementById('form-client-account');

    formClient.addEventListener('onchange', function () {
        form2.submit();
    })



});

const toggleUserFields = (isVisible) => {
    userFields.forEach(input => {
        if (isVisible) {
            input.removeAttribute('disabled');
            input.setAttribute('required', 'required'); // Make required if visible
        } else {
            input.setAttribute('disabled', 'disabled');
            input.removeAttribute('required'); // Remove required if not visible
            input.value = ''; // Optionally clear the input values
        }
    });
};


