/*ACTIVE CONTINUE BUTTON*/
function checkCheckboxes() {
    const checkboxes  = document.querySelectorAll('.form-check-input');
    const continueBtn = document.getElementById('continue-btn');
    let checked       = false;

    checkboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            checked = true;
        }
    });

    if (checked) {
        continueBtn.classList.remove('disabled');
        continueBtn.disabled = false;
    } else {
        continueBtn.classList.add('disabled');
        continueBtn.disabled = true;
    }
}

/*MODAL SHOW HIDE CONTENT */
function handleSetModal(id) {
    const show       = document.getElementById(id);
    const allSection = document.querySelectorAll('#signin-modal>div');
    allSection.forEach((item) => {
        item.classList.add('d-none');
    })
    show.classList.remove('d-none');
    show.classList.add('d-block');
}

// ADD-LOCATION-CONTINUE-BUTTON
function handleChangeLocation() {

    const changeSection = document.getElementById('add-location-change-section');
    const changeInput   = document.getElementById('add-location-change-input');

    changeSection.classList.add('d-none');
    changeInput.classList.remove('d-none');
    changeInput.classList.add('d-flex')
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

function checkInputs(inputs, button, validators) {
    return function () {
        const areInputsValid = inputs.every((input, index) => {
            const value = input.value.trim();
            return validators[index] ? validators[index](value) : Boolean(value);
        });

        if (areInputsValid) {
            button.classList.remove('disabled');
        } else {
            button.classList.add('disabled');
        }
    };
}

// GIVE-EVENT-NAME-FORM
const eventInputs = [
    document.getElementById('event-name'),
    document.getElementById('greeting-guest')
];
const eventContinueBtn = document.getElementById('event-continue-btn');
const eventValidators = [null, null];

eventInputs.forEach(input => input.addEventListener('input', checkInputs(eventInputs, eventContinueBtn, eventValidators)));


// ABOUT-YOURSELF-FORM
const aboutInputs = [
    document.getElementById('first-name'),
    document.getElementById('last-name'),
    document.getElementById('email')
];
const aboutContinueBtn = document.getElementById('about-continue-btn');
const aboutValidators = [null, null, validateEmail];

aboutInputs.forEach(input => input.addEventListener('input', checkInputs(aboutInputs, aboutContinueBtn, aboutValidators)));

// ABOUT-ORGANIZATION-FORM
const orgInputs = [
    document.getElementById('org-name'),
    document.getElementById('tagline')
];
const orgContinueBtn = document.getElementById('org-continue-btn');
const orgValidators = [null, null];

orgInputs.forEach(input => input.addEventListener('input', checkInputs(orgInputs, orgContinueBtn, orgValidators)));


// CREATE-STORYZ-FORM
const storyzInputs = [
    document.getElementById('storyz-name'),
];
const storyzContinueBtn = document.getElementById('create-storyz-btn');
const storyzValidators = [null, null];

storyzInputs.forEach(input => input.addEventListener('input', checkInputs(storyzInputs, storyzContinueBtn, storyzValidators)));



//FUNCTION TO BOTH FILE INPUT AND IMAGE PREVIEW
function handleImagePreview(inputId, previewId) {
    const inputElement = document.getElementById(inputId);
    const previewElement = document.getElementById(previewId);

    inputElement.addEventListener('change', function () {
        const file = inputElement.files[0];

        if (file) {
            // Validate file type
            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validImageTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, GIF).');
                inputElement.value = '';
                return;
            }

            // Create a URL for the selected file and set it as the src of the image element
            previewElement.src = URL.createObjectURL(file);

            // Revoke the object URL to free memory when the image is loaded
            previewElement.onload = function () {
                URL.revokeObjectURL(previewElement.src);
            };
        }
    });
}

// APPLY THE FUNCTION TO BOTH FILE INPUT AND IMAGE PREVIEW PAIRS
handleImagePreview('avatarInput', 'avatarYourself');
handleImagePreview('eventAvatarInput', 'eventImage');
handleImagePreview('storyzAvatarInput', 'storyzImage');
handleImagePreview('brandLogoInput', 'brandLogo');
handleImagePreview('CreateStoryzInput', 'createStoryzImage');


//STORYZ-SEARCH-LIST
const storyzSearchInput = document.getElementById('storyz-search');
const storyzSearchList = document.getElementById('storyz-search-list');
const dropdownItems = Array.from(storyzSearchList.children);

storyzSearchInput.addEventListener('input', function () {
    const filter = storyzSearchInput.value.toLowerCase();
    storyzSearchList.classList.add('show');
    filterDropdownItems(filter);
});

storyzSearchInput.addEventListener('blur', function () {
    setTimeout(() => {
        storyzSearchList.classList.remove('show');
    }, 100);
});

dropdownItems.forEach(item => {
    item.addEventListener('mousedown', function () {
        const value = this.getAttribute('data-value');
        handleGetValue(value);
    });
});

function handleGetValue(value) {
    storyzSearchInput.value = value;
    storyzSearchList.classList.remove('show');
}

function filterDropdownItems(filter) {
    dropdownItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(filter)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// TAG-INPUT
document.addEventListener('DOMContentLoaded', () => {
    const emailInput = document.getElementById('email-input');
    const tagsInputWrapper = document.querySelector('.tags-input-wrapper');

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    function createTag(email) {
        const tag = document.createElement('span');
        tag.classList.add('tag');
        tag.innerHTML = `${email} <span class="remove-tag">&times;</span>`;
        tagsInputWrapper.insertBefore(tag, emailInput);

        tag.querySelector('.remove-tag').addEventListener('click', () => {
            tagsInputWrapper.removeChild(tag);
        });
    }

    emailInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ',') {
            event.preventDefault();
            const email = emailInput.value.trim();
            if (validateEmail(email)) {
                createTag(email);
                emailInput.value = '';
            } else {
                alert('Invalid email address');
            }
        }
    });
});


 
jQuery(document).ready(function($)
      {
          // Open the Modal
          $('#open-map-modal').click(function() {
              $('#map-modal').show();
              // Initialize map when the modal is shown
              initializeAutocomplete();
          });

          // Close the Modal
          $('#close-map-modal').click(function() {
              $('#map-modal').hide();
          });

          // Close the Modal when clicking outside of the modal content
          $(window).click(function(event) {
              if (event.target.id == 'map-modal') {
                  $('#map-modal').hide();
              }
          });

          function initializeAutocomplete() {
              var input = document.getElementById('location-input');
              var autocomplete = new google.maps.places.Autocomplete(input);

              var map = new google.maps.Map(document.getElementById('map'), {
                  zoom: 15,
                  mapTypeId: google.maps.MapTypeId.ROADMAP
              });

              var marker = new google.maps.Marker({
                  map: map
              });

              autocomplete.addListener('place_changed', function() {
                  var place = autocomplete.getPlace();

                  if (!place.geometry) {
                      console.log("Returned place contains no geometry");
                      return;
                  }

                  // If the place has a geometry, then present it on a map.
                  if (place.geometry.viewport) {
                      map.fitBounds(place.geometry.viewport);
                  } else {
                      map.setCenter(place.geometry.location);
                      map.setZoom(17); // Why 17? Because it looks good.
                  }

                  marker.setPosition(place.geometry.location);
                  marker.setVisible(true);
              });
          }
      });