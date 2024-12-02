"use strict";

function setEnable() {
    // Enable all input elements except those with class 'ignore'
    document.querySelectorAll("input:not(.ignore)").forEach(function (element) {
        element.removeAttribute("disabled");
    });

    // Enable all select elements except those with class 'ignore'
    document
        .querySelectorAll("select:not(.ignore)")
        .forEach(function (element) {
            element.removeAttribute("disabled");
        });

    // Enable all textarea elements except those with class 'ignore'
    document
        .querySelectorAll("textarea:not(.ignore)")
        .forEach(function (element) {
            element.removeAttribute("disabled");
        });

    // Enable all button elements except those with class 'ignore'
    document
        .querySelectorAll("button:not(.ignore)")
        .forEach(function (element) {
            element.removeAttribute("disabled");
        });

    // Remove 'disabled' class from all anchor elements except those with class 'ignore'
    document.querySelectorAll("a:not(.ignore)").forEach(function (element) {
        element.classList.remove("disabled");
    });

    // Remove 'btn-progress' class from all elements with class 'btn' except those with class 'ignore'
    document.querySelectorAll(".btn:not(.ignore)").forEach(function (element) {
        element.classList.remove("btn-progress");
    });
}

function setDisable() {
    // Disable all input elements except those with class 'ignore'
    document.querySelectorAll("input:not(.ignore)").forEach(function (element) {
        element.setAttribute("disabled", true);
    });

    // Disable all select elements except those with class 'ignore'
    document
        .querySelectorAll("select:not(.ignore)")
        .forEach(function (element) {
            element.setAttribute("disabled", true);
        });

    // Disable all textarea elements except those with class 'ignore'
    document
        .querySelectorAll("textarea:not(.ignore)")
        .forEach(function (element) {
            element.setAttribute("disabled", true);
        });

    // Disable all button elements except those with class 'ignore'
    document
        .querySelectorAll("button:not(.ignore)")
        .forEach(function (element) {
            element.setAttribute("disabled", true);
        });

    // Add 'disabled' class to all anchor elements except those with class 'ignore'
    document.querySelectorAll("a:not(.ignore)").forEach(function (element) {
        element.classList.add("disabled");
    });

    // Add 'btn-progress' class to all elements with class 'btn' except those with class 'ignore'
    document.querySelectorAll(".btn:not(.ignore)").forEach(function (element) {
        element.classList.add("btn-progress");
    });
}

function formatRupiahText(value) {
    value = value.toString().replace(/\D/g, ""); // Hanya angka
    return new Intl.NumberFormat("id-ID").format(value); // Format ke Rupiah
}

function formatRupiahElement(input) {
    input.value = formatRupiahText(input.value);
}
