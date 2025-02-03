"use strict";

function formatRupiahText(value) {
    value = value.toString().replace(/\D/g, ""); // Hanya angka
    return new Intl.NumberFormat("id-ID").format(value); // Format ke Rupiah
}

function formatRupiahElement(input) {
    input.value = formatRupiahText(input.value);
}

document.addEventListener("DOMContentLoaded", function () {
    const qtyControls = document.querySelectorAll(".qty-control");

    qtyControls.forEach(function (qtyControl) {
        const decrementButton = qtyControl.querySelector(".decrement-button");
        const incrementButton = qtyControl.querySelector(".increment-button");
        const quantityInput = qtyControl.querySelector(".quantity-input");

        quantityInput.addEventListener("input", function () {
            let value = parseInt(this.value.replace(/[^0-9]/g, ""));
            let minValue = parseInt(this.getAttribute("min")) || 1;
            let maxValue = parseInt(this.getAttribute("max")) || Infinity;

            if (isNaN(value) || value < minValue) {
                value = minValue;
            } else if (value > maxValue) {
                value = maxValue;
            }

            this.value = value;
        });

        decrementButton.addEventListener("click", function (event) {
            event.preventDefault();
            let currentValue = parseInt(quantityInput.value);
            let minValue = parseInt(quantityInput.getAttribute("min")) || 1;
            if (currentValue > minValue) {
                quantityInput.value = currentValue - 1;
                quantityInput.dispatchEvent(new Event("input"));
            }
        });

        incrementButton.addEventListener("click", function (event) {
            event.preventDefault();
            let currentValue = parseInt(quantityInput.value);
            let maxValue =
                parseInt(quantityInput.getAttribute("max")) || Infinity;
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
                quantityInput.dispatchEvent(new Event("input"));
            }
        });
    });
});

// Alert Message
(function ($) {
    $.fn.alertError = function (message) {
        // Hapus elemen lama dengan ID 'msg-error' dalam konteks elemen yang dipilih
        this.find("#msg-error").remove();

        // Buat elemen baru
        let element = $(`
            <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show" id="msg-error" role="alert">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-white">
                        <span class="material-icons-outlined fs-2">report_gmailerrorred</span>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0 text-white"> Uups!</h5>
                        <div class="text-white">${message}</div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);

        element.hide();
        this.prepend(element);
        element.fadeIn();

        return this; // Memungkinkan chaining
    };
})(jQuery);

function clearAlert() {
    $("#msg-error").remove();
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
                                element.tagName === "TEXTAREA" ||
                                element.tagName === "SELECT"
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

            // Event listener untuk mendeteksi ketika tab sedang memuat atau berpindah halaman
            window.addEventListener("beforeunload", () => {
                this.startPace();
            });

            window.addEventListener("pageshow", (event) => {
                if (event.persisted) {
                    //console.log("Page was restored from cache");
                    this.stopPace();
                }
            });

            // Event listener untuk mendeteksi ketika halaman selesai dimuat
            window.addEventListener("load", () => {
                //console.log("Window load event fired");
                this.stopPace();
            });

            // Event listener untuk mendeteksi ketika DOM selesai diurai
            document.addEventListener("DOMContentLoaded", () => {
                //console.log("DOMContentLoaded event fired");
                this.stopPace();
            });

            $("form").on("submit", function (event) {
                if ($("body").attr("data-pace") === "true") {
                    if ($(this).attr("download") !== "true") {
                        // if ($(this).attr("onSubmit") !== "return false") {
                        if (
                            !$(this).attr("onSubmit").includes("return false")
                        ) {
                            self.setAlwaysRun(true);
                            toggleElements(false);
                            Pace.restart();
                        }
                    } else {
                        toggleElements(false);
                        Pace.restart();
                    }
                }
            });
        }

        startPace() {
            self.setAlwaysRun(true);
            toggleElements(false);
            Pace.restart();
        }

        stopPace() {
            Pace.stop();
            $(".preloader").fadeOut(300, function () {
                toggleElements(true);
            });
        }

        setAlwaysRun(alwaysRun) {
            this.alwaysRun = alwaysRun;
        }
    }

    // window.paceManager = new PaceManager();
    const paceManager = new PaceManager();
});
