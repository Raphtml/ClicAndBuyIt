/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

import { Tooltip, Toast, Popover} from "bootstrap";

// start the Stimulus application
import './bootstrap';

import { Loader } from "@googlemaps/js-api-loader";

const axios = require('axios').default;

// Fade sur les flashmessage
$(document).ready(function() {
    setTimeout(function () {
        $( "#flash-message" ).fadeOut( "slow", function() {
        });
    }, 5000);
});


// Traduction du badge Error
const badge = document.getElementsByClassName('badge');
for (let i=0; i<badge.length; i++){
    badge[i].innerHTML = 'Erreur';
}

//Récupération Latitude et Longitude pour map
const latitude = document.getElementById('mapLat').value;
const longitude = document.getElementById('mapLong').value;

//Map Google API
const loader = new Loader({
    apiKey: "AIzaSyChDtqFD104DaO6jVhw7337uW4m6V6FJrY",
    version: "weekly",
});
loader.load().then(() => {
    let map = new google.maps.Map(document.getElementById("map"), {
        center: {lat: parseFloat(latitude), lng: parseFloat(longitude)},
        zoom: 12,
    });
    new google.maps.Marker({
        position: {lat: parseFloat(latitude), lng: parseFloat(longitude)},
        map: map,
    })
});

//Apparition num tel sur annonce
document.getElementById('buyBtn').addEventListener('click', function () {
    this.classList.add('d-none');
    document.getElementById('telephone').classList.remove('d-none');
});


//Gestion sauvegarde annonce
function onClickBtnLike(event){
    event.preventDefault();

    const url = this.href;
    axios.post(url).then(function (response) {
        const icone = document.querySelector('i');
        console.log(icone);
        if(icone.classList.contains('fa-heart-o')){
            icone.classList.replace('fa-heart-o', 'fa-heart')
        } else {
            icone.classList.replace('fa-heart', 'fa-heart-o')
        }
    }).catch(function (error) {
       if (error.response.status === 403){
           window.alert('Merci de vous connecter pour sauvegarder une annonce');
       }
    });
}
document.getElementById('js-like').addEventListener('click', onClickBtnLike);

