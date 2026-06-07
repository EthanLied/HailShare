<?php header("Content-type: text/css"); ?>

html, body {
    overflow-x: hidden;
    max-width: 100vw;
}

#navbar,
#navbar.expand,
#navbar > a,
#content,
#navbar .navbarItem {
    transition: 0.3s cubic-bezier(.42, .94, .31, .99);
}

#navbar {
    position: fixed;
    z-index: 5;
    left: 0;
    top: 0;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    width: 55px;
    height: 100vh;
    background-color: #ffffff;
    border-right: solid 1px #ececec;
    user-select: none;
    padding-top: 10px;
    outline: none;                
}

#navbar.expand {
    width: 280px;
    background-color: #f8f8f8;
}

#navbar > a {
    display: block;
    width: 100%;
}

#hamburgerMenuNavbarIcon {
    transition: background-color 0.2s;
    border-radius: 5px;
    width: 40px;
    height: 40px;
    padding: 0;
    cursor: pointer;
    background: transparent;
    border: none;
    outline: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    line-height: 1;
    text-align: center;
}

#hamburgerMenuNavbarIcon:hover,
a > .navbarItem:hover {
    background-color: #e8e8e8 !important;
}

.navbarItem {
    margin: 5px 0;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 100%;
    height: 44px;
    padding-left: 7px;
    overflow: hidden;
    box-sizing: border-box;
}

.navbarItem.expand {
    width: 100%;
    justify-content: flex-start;
    padding-left: 7px;
}

.navbarItem > .material-symbols-outlined {
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    line-height: 1;
    text-align: center;
}

.navbarHeader > a {
    display: none;
}

.navbarHeader.expand > a {
    display: block;
}

.navbarSpacer {
    flex: 0 0 18px;
}

.navbarItem > p,
.navbarItem > a > h3 {
    width: 0;
    overflow: hidden;
    white-space: nowrap;
    transition: 0.15s ease-out;
    color: transparent;
    margin-left: 0;
}

.navbarItem.expand > p,
.navbarItem.expand > a > h3 {
    width: auto;
    overflow: visible;
    color: #000000;
    margin-left: 10px;
}

.navbarHeader:not(.expand) {
    justify-content: flex-start;
    padding-left: 7px;
}

#content {
    margin-left: 55px;
    padding: 40px;
    height: 100vh;
    overflow-y: auto;
    overflow-x: hidden;
    transition: margin-left 0.3s cubic-bezier(.42,.94,.31,.99);
    box-sizing: border-box;
    max-width: calc(100vw - 55px);   
}

#content.expand {
    margin-left: 280px;
    max-width: calc(100vw - 280px);
}
@media (max-width: 768px) {
    #navbar {
        background-color: transparent;
        border-color: transparent;
    }
    #navbar.expand {
        background-color: white;
        border-right: solid 1px #ececec;
    }
    #content, #content.expand {
        margin-left: 0;
        max-width: 100vw;
    }
    #navbar > a {
        transform: translateX(-350%);
    }
    #navbar.expand > a {
        transform: translateX(0);
    }
    #hamburgerMenuNavbarIcon:hover {
        background-color: transparent;
    }
}

label {
    display: block;
    font-weight: 500;
    margin-bottom: 8px;
    color: #333;
}

input[type="text"],
input[type="email"],
input[type="password"],
input[type="tel"],
input[type="number"] {
    width: 100%;
    box-sizing: border-box;
}

select {
    width: 100%;
    box-sizing: border-box;
}

#navbar a,
#navbar a:visited {
    text-decoration: none;
    color: inherit;
    outline: none !important;
}

#navbar a #hamburgerMenuNavbarIcon,
#navbar a #hamburgerMenuNavbarIcon:focus {
    outline: none !important;
}

#content a {
    text-decoration: none;
    color: inherit;
}
