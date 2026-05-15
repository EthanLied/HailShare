<?php header("Content-type: text/css"); ?>
/* mobile.css — touch overrides */

html,
body {
    overflow-x: hidden;
    max-width: 100%;
    width: 100%;
}

@media screen and (max-width: 768px) {

    .btnNormal:hover,
    .btnStrong:hover {
        cursor: auto;
        transform: scale(1);
    }

    .btnNormal:active,
    .btnStrong:active {
        transform: scale(0.9);
    }

    .btnNormal2:hover {
        background-color: rgb(255, 255, 255);
    }

    .btnStrong2:hover {
        border: solid 1px black;
        background-color: rgb(0, 0, 0);
    }

    .btnStrong2:active {
        transform: translateY(1px);
    }

    #hamburgerMenuNavbarIcon:hover {
        background-color: transparent;
    }

    #content,
    #content.expand {
        margin-left: 55px !important;
        width: calc(100vw - 55px) !important;
        max-width: calc(100vw - 55px) !important;
        padding: 20px 16px 40px 16px !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
        height: auto !important;
        min-height: 100vh;
    }

    .content-wrapper {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: hidden;
        box-sizing: border-box;
    }

    #content * {
        max-width: 100%;
        box-sizing: border-box;
    }

}