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