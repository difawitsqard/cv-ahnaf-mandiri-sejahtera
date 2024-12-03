"use strict";

function setEnable() {
    document.body.classList.remove("disable-pointer-events");

    // Enable all input elements except those with class 'not-disabled'
    document
        .querySelectorAll("input:not(.not-disabled)")
        .forEach(function (element) {
            element.removeAttribute("readonly");
        });

    // Enable all select elements except those with class 'not-disabled'
    document
        .querySelectorAll("select:not(.not-disabled)")
        .forEach(function (element) {
            element.removeAttribute("disabled");
        });

    // Enable all textarea elements except those with class 'not-disabled'
    document
        .querySelectorAll("textarea:not(.not-disabled)")
        .forEach(function (element) {
            element.removeAttribute("readonly");
        });

    // Enable all button elements except those with class 'not-disabled'
    document
        .querySelectorAll("button:not(.not-disabled)")
        .forEach(function (element) {
            element.removeAttribute("disabled");
        });

    // Remove 'disabled' class from all anchor elements except those with class 'not-disabled'
    document
        .querySelectorAll("a:not(.not-disabled)")
        .forEach(function (element) {
            element.classList.remove("disabled");
        });
}

function setDisable() {
    document.body.classList.add("disable-pointer-events");

    // Disable all input elements except those with class 'not-disabled' and already disabled
    document
        .querySelectorAll("input:not(.not-disabled)")
        .forEach(function (element) {
            if (
                element.hasAttribute("readonly") ||
                element.hasAttribute("disabled")
            ) {
                element.classList.add("not-disabled");
            } else {
                element.setAttribute("readonly", true);
            }
        });

    // Disable all select elements except those with class 'not-disabled' and already disabled
    document
        .querySelectorAll("select:not(.not-disabled)")
        .forEach(function (element) {
            if (element.hasAttribute("disabled")) {
                element.classList.add("not-disabled");
            } else {
                element.setAttribute("disabled", true);
            }
        });

    // Disable all textarea elements except those with class 'not-disabled' and already disabled
    document
        .querySelectorAll("textarea:not(.not-disabled)")
        .forEach(function (element) {
            if (
                element.hasAttribute("readonly") ||
                element.hasAttribute("disabled")
            ) {
                element.classList.add("not-disabled");
            } else {
                element.setAttribute("readonly", true);
            }
        });

    // Disable all button elements except those with class 'not-disabled' and already disabled
    document
        .querySelectorAll("button:not(.not-disabled)")
        .forEach(function (element) {
            if (element.hasAttribute("disabled")) {
                element.classList.add("not-disabled");
            } else {
                element.setAttribute("disabled", true);
            }
        });

    // Add 'disabled' class to all anchor elements except those with class 'not-disabled'
    document
        .querySelectorAll("a:not(.not-disabled)")
        .forEach(function (element) {
            if (element.classList.contains("disabled")) {
                element.classList.add("not-disabled");
            } else {
                element.classList.add("disabled");
            }
        });
}

function formatRupiahText(value) {
    value = value.toString().replace(/\D/g, ""); // Hanya angka
    return new Intl.NumberFormat("id-ID").format(value); // Format ke Rupiah
}

function formatRupiahElement(input) {
    input.value = formatRupiahText(input.value);
}

$(function () {
    "use strict";

    // pace costum
    $(document).ajaxStart(function () {
        Pace.restart();
    });

    Pace.on("start", function () {
        setDisable();
        $(".preloader").fadeIn();
    });

    Pace.on("done", function () {
        $(".preloader").fadeOut(1000);
        setEnable();
    });

    $("form").on("submit", function (event) {
        if ($("body").attr("data-pace") === "true") {
            if ($(this).attr("onSubmit") !== "return false") {
                Pace.restart();
            }
        }
    });
});
