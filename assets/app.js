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
        toggleUserFields(true);
    } else {
        toggleDiv.classList.add('hidden');
        toggleUserFields(false);
    }

    toggleSwitch.addEventListener('change', function () {
        const isChecked = this.checked;
        toggleDiv.classList.toggle('hidden', !isChecked);
        toggleUserFields(isChecked);
    });


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


