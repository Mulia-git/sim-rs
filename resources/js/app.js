import './bootstrap';

import $ from 'jquery';
window.$ = $;
window.jQuery = $;

import 'datatables.net';
import 'datatables.net-bs4';


import './dashboard';
import './bpjs';

import Swal from 'sweetalert2';
window.Swal = Swal;
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

document.addEventListener("DOMContentLoaded", function () {
    flatpickr(".datepicker", {
        dateFormat: "d-m-Y"
    });
});