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

// Fade sur les flashmessage
$(document).ready(function() {
    setTimeout(function () {
        $( "#flash-message" ).fadeOut( "slow", function() {
        });
    }, 5000)
});

// Traduction du badge Error
window.onload = function () {
    const badge = document.getElementsByClassName('badge');
    for (let i=0; i<badge.length; i++){
        badge[i].innerHTML = 'Erreur';
    }
}

// Récupération des villes via le code postal
$(function () {
    var zipCode = $('#advert_zipCode');
    var callBackGetSuccess = function (data){
        for (let i=0; i<data.records.length; i++){
            document.getElementById('advert_city').options[i] = new Option(data.records[i]['fields']['nom_de_la_commune'],data.records[i]['fields']['nom_de_la_commune']);
        }
    }
    zipCode.change(function () {
        if (zipCode.val().length === 5){
            var url = "https://datanova.legroupe.laposte.fr/api/records/1.0/search/?dataset=laposte_hexasmal&q=&refine.code_postal="+zipCode.val()+"&exclude.code_commune_insee="+zipCode.val();
            $.get(url, callBackGetSuccess).done(function () {
            })
                .fail(function () {
                    console.log('Erreur lors de la récupération des villes');
                })
                .always(function () {
                });
        }
    });
});