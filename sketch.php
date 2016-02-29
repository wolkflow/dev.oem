<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script>
        var ENV = "production"; // В каком окружении работает сайт [development|production]
        var CONFIG = {"baseurl": "http://.............", "staticurl": ".", "apiurl": "."}; // Настройки сайта для данного окружения

        var DATA = {}; // Данные сайта

        var BASEURL = "http://............."; // Адрес по которому доступна главная страница
        var APIURL = "."; // Адрес по которому доступен php
        var STATICURL = "."; // Адрес или префикс где лежит статика

        var V = "1447001122"; // Версия скомпилированного сайта
    </script>
    <script src="/local/templates/.default/build/js/vendor.js?v=1447001122"></script>
    <link href="/local/templates/.default/build/css/vendor.css?v=1447001122" rel="stylesheet">
    <script src="/local/templates/.default/javascripts/application.js?v=1447001122"></script>
    <script src="/local/templates/.default/javascripts/designer.js?v=1447001122"></script>
    <link href="/local/templates/.default/build/css/main.css?v=1447001122" rel="stylesheet">
    <script>
        window.addEventListener ("touchmove", function (event) { event.preventDefault (); }, false);
        if (typeof window.devicePixelRatio != 'undefined' && window.devicePixelRatio > 2) {
            var meta = document.getElementById ("viewport");
            meta.setAttribute ('content', 'width=device-width, initial-scale=' + (2 / window.devicePixelRatio) + ', user-scalable=no');
        }
    </script>
</head>
<body>
<div class="headersection">
    <div class="layout">
        <div class="headersection__button">language
            <div class="headersection__languagedropdown"><a href=""
                                                            class="active headersection__languagedropdownbutton">English</a><a
                    href="" class="headersection__languagedropdownbutton">русский</a></div>
        </div>
        <div class="headersection__button">login
            <form class="headersection__loginform">
                <div class="headersection__loginformtitle">If you have an account, please type-in your credentials and
                    press “Login”.
                </div>
                <div class="headersection__loginforminputtitle">E-mail</div>
                <input type="text" class="headersection__loginforminput"/>

                <div class="headersection__loginforminputtitle">Password</div>
                <input type="password" class="headersection__loginforminput"/>

                <div class="headersection__loginformbutton">login<input type="submit"></div>
            </form>
        </div>
        <div class="headersection__logocontainer"><a href="/" class="headersection__logo"></a></div>
    </div>
</div>
<div class="pagecontainer">
    <div class="layout">
        <div class="breadcrumbs">
            <div class="breadcrumbs__container"><a href="" class="breadcrumbs__button"> <span
                        class="breadcrumbs__buttoncontainer">1. stand type</span></a><a href="" class="breadcrumbs__button">
                    <span class="breadcrumbs__buttoncontainer">2. standard equipment</span></a><a href=""
                                                                                                  class="breadcrumbs__button">
                    <span class="breadcrumbs__buttoncontainer">3. services</span></a><a href="" class="breadcrumbs__button">
                    <span class="breadcrumbs__buttoncontainer">4. equipment</span></a><a href=""
                                                                                         class="active breadcrumbs__button">
                    <span class="breadcrumbs__buttoncontainer">5. sketch</span></a><a href="" class="breadcrumbs__button">
                    <span class="breadcrumbs__buttoncontainer">6. order</span></a></div>
        </div>
        <div class="sketchpage">
            <div class="pagetitle">Sketch
                <div class="active pagetitle__button">help</div>
            </div>
            <div class="pagedescription">Draft your personal fair stand: lace your modules in the white diamond field.
                Remember, you can’t place an order before you put all equipment on a draft. If you have any
                difficulties, click “help”.
            </div>
            <div id="designer" style="margin-top:40px; width: 940px; height:680px" onmouseout="ru.octasoft.oem.designer.Main.stopDragging()" onwheel="event.preventDefault()" onmousewheel="event.preventDefault()"></div>

            <script type="text/javascript">
                lime.embed ("designer", 0, 0);
                window.setupEditor = function() {
                    ru.octasoft.oem.designer.Main.init({
                        w: 20,
                        h: 1,
                        // row corner head island
                        type: "row",
                        items: [
                            {
                                title: "Table",
                                quantity: 5,
                                type: "droppable",
                                w: 0.48,
                                h: 0.3,
                                id: "table1",
                                imagePath: "/assets/figures/table.png"
                            },
                            {
                                title: "Large shelf",
                                quantity: 4,
                                type: "shelf",
                                w: 0.48,
                                h: 0.3,
                                id: "table1",
                                imagePath: "/assets//figures/table.png"
                            },
                            {
                                title: "Halogen Light",
                                quantity: 3,
                                type: "light",
                                w: 0.44,
                                h: 0.29,
                                id: "light1",
                                imagePath: "/assets//figures/light.png"
                            },
                            {
                                title: "Torsher",
                                quantity: 8,
                                type: "light",
                                w: 0.44,
                                h: 0.29,
                                id: "light2",
                                imagePath: "/assets//figures/light.png"
                            },
                            {
                                title: "Stul",
                                quantity: 2,
                                type: "droppable",
                                w: 0.48,
                                h: 0.3,
                                id: "table2",
                                imagePath: "/assets/figures/table.png"
                            },
                            {
                                title: "Kolbasa",
                                quantity: 2,
                                type: "droppable",
                                w: 0.48,
                                h: 0.3,
                                id: "table3",
                                imagePath: "/assets/figures/table.png"
                            },
                            {
                                title: "Uho",
                                quantity: 2,
                                type: "droppable",
                                w: 0.48,
                                h: 0.3,
                                id: "table4",
                                imagePath: "/assets/figures/table.png"
                            }
                        ]
                    });
                };
            </script>
        </div>
        <div class="footersection"><a href="" class="footersection__contact">Contact Us</a><a href=""
                                                                                              class="footersection__terms">Terms
                & Conditions</a><a href="" class="footersection__information">General Information</a></div>
    </div>
</div>
</body>
</html>
