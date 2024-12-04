"use strict";

function formatRupiahText(value) {
    value = value.toString().replace(/\D/g, ""); // Hanya angka
    return new Intl.NumberFormat("id-ID").format(value); // Format ke Rupiah
}

function formatRupiahElement(input) {
    input.value = formatRupiahText(input.value);
}

const toggleElements = (function () {
    let toggleElementsState = null;

    return function (state) {
        if (toggleElementsState === state) {
            // console.log(
            //     `Elements are already ${state ? "enabled" : "disabled"}`
            // );
            return;
        }

        toggleElementsState = state;
        // console.log(`Elements are now ${state ? "enabled" : "disabled"}`);

        if (state) {
            document.body.classList.remove("disable-pointer-events");
        } else {
            document.body.classList.add("disable-pointer-events");
        }

        // Elemen-elemen yang akan diaktifkan/dinonaktifkan
        const elements = document.querySelectorAll(
            "input, select, textarea, button, a"
        );

        elements.forEach((element) => {
            // Abaikan elemen dengan class 'ignore'
            if (element.classList.contains("ignore")) return;

            if (element.tagName === "A") {
                element.classList.toggle("disabled", !state);
            } else {
                if (state) {
                    element.removeAttribute("readonly");
                    element.removeAttribute("disabled");
                } else {
                    if (
                        !element.hasAttribute("readonly") &&
                        !element.hasAttribute("disabled")
                    ) {
                        element.setAttribute(
                            element.tagName === "INPUT" ||
                                element.tagName === "TEXTAREA"
                                ? "readonly"
                                : "disabled",
                            true
                        );
                    }
                }
            }
        });
    };
})();

$(function () {
    "use strict";

    class PaceManager {
        constructor() {
            this.alwaysRun = false;
            this.initPaceEvents();
        }

        initPaceEvents() {
            self = this;

            $(document).ajaxStart(function () {
                toggleElements(false);
                Pace.restart();
            });

            // // Menangani ketika semua permintaan AJAX selesai
            // $(document).ajaxComplete(function () {
            //     Pace.stop();
            //     $(".preloader").fadeOut(1000, function () {
            //         setEnable();
            //     });
            // });

            Pace.on("start", function () {
                toggleElements(false);
                $(".preloader").fadeIn();
            });

            Pace.on("done", function () {
                // console.log("Pace done fired");
                if (!self.alwaysRun)
                    $(".preloader").fadeOut(300, function () {
                        toggleElements(true);
                    });
            });

            $("form").on("submit", function (event) {
                if ($("body").attr("data-pace") === "true") {
                    if ($(this).attr("onSubmit") !== "return false") {
                        self.setAlwaysRun(true);
                        toggleElements(false);
                        Pace.restart();
                    }
                }
            });
        }

        setAlwaysRun(alwaysRun) {
            this.alwaysRun = alwaysRun;
        }
    }

    const paceManager = new PaceManager();
});
