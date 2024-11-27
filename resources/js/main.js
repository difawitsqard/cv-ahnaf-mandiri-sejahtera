$(function () {
    "use strict";

    /* scrollar */

    if ($(".notify-list").length) {
        new PerfectScrollbar(".notify-list");
    }

    if ($(".search-content").length) {
        new PerfectScrollbar(".search-content");
    }

    // new PerfectScrollbar(".mega-menu-widgets")

    /* toggle button */

    $(".btn-toggle").click(function () {
        $("body").hasClass("toggled")
            ? ($("body").removeClass("toggled"),
              $(".sidebar-wrapper").unbind("hover"))
            : ($("body").addClass("toggled"),
              $(".sidebar-wrapper").hover(
                  function () {
                      $("body").addClass("sidebar-hovered");
                  },
                  function () {
                      $("body").removeClass("sidebar-hovered");
                  }
              ));
    });

    /* menu */

    $(function () {
        $("#sidenav").metisMenu();
    });

    $(".sidebar-close").on("click", function () {
        $("body").removeClass("toggled");
    });

    /* dark mode button */

    $(".dark-mode i").click(function () {
        $(this).text(function (i, v) {
            return v === "dark_mode" ? "light_mode" : "dark_mode";
        });
    });

    $(".dark-mode").click(function () {
        $("html").attr("data-bs-theme", function (i, v) {
            return v === "dark" ? "light" : "dark";
        });
    });

    $(document).ready(function () {
        // Muat tema dari localStorage saat halaman dimuat
        const savedTheme = localStorage.getItem("theme");
        if (savedTheme) {
            $("html").attr("data-bs-theme", savedTheme);
            $(`#${savedTheme}`).prop("checked", true);
        }

        // Fungsi untuk mengubah tema dan menyimpannya ke localStorage
        function setTheme(theme) {
            $("html").attr("data-bs-theme", theme);
            localStorage.setItem("theme", theme);
            $(`#${theme}`).prop("checked", true);
        }

        $("#blue-theme").on("click", function () {
            setTheme("blue-theme");
        });

        $("#light").on("click", function () {
            setTheme("light");
        });

        $("#dark").on("click", function () {
            setTheme("dark");
        });

        $("#semi-dark").on("click", function () {
            setTheme("semi-dark");
        });

        $("#bodered-theme").on("click", function () {
            setTheme("bodered-theme");
        });
    });

    /* sticky header */
    $(document).ready(function () {
        $(window).on("scroll", function () {
            if ($(this).scrollTop() > 60) {
                $(".top-header .navbar").addClass("sticky-header");
            } else {
                $(".top-header .navbar").removeClass("sticky-header");
            }
        });
    });

    /* email */

    $(".email-toggle-btn").on("click", function () {
        $(".email-wrapper").toggleClass("email-toggled");
    }),
        $(".email-toggle-btn-mobile").on("click", function () {
            $(".email-wrapper").removeClass("email-toggled");
        }),
        $(".compose-mail-btn").on("click", function () {
            $(".compose-mail-popup").show();
        }),
        $(".compose-mail-close").on("click", function () {
            $(".compose-mail-popup").hide();
        }),
        /* chat */

        $(".chat-toggle-btn").on("click", function () {
            $(".chat-wrapper").toggleClass("chat-toggled");
        }),
        $(".chat-toggle-btn-mobile").on("click", function () {
            $(".chat-wrapper").removeClass("chat-toggled");
        }),
        /* switcher */

        /* search control */

        $(".search-control").click(function () {
            $(".search-popup").addClass("d-block");
            $(".search-close").addClass("d-block");
        });

    $(".search-close").click(function () {
        $(".search-popup").removeClass("d-block");
        $(".search-close").removeClass("d-block");
    });

    $(".mobile-search-btn").click(function () {
        $(".search-popup").addClass("d-block");
    });

    $(".mobile-search-close").click(function () {
        $(".search-popup").removeClass("d-block");
    });

    /* menu active */

    $(function () {
        var currentUrl = window.location.href;

        var matchedLink = $(".metismenu li a")
            .filter(function () {
                return currentUrl.startsWith(this.href);
            })
            .sort(function (a, b) {
                return b.href.length - a.href.length;
            })
            .first();

        if (matchedLink.length) {
            matchedLink
                .addClass("mm-active")
                .parents("li")
                .addClass("mm-active")
                .parents("ul")
                .addClass("mm-show")
                .parents("li")
                .addClass("mm-active");
        }
    });
});
