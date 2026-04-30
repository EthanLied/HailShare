<?php header("Content-type: text/css"); ?>

/* Shrinks and slightly fades when hovering logo and navbar items*/
h3:hover, .navbarItem:not(:first-child):active{
    transition: 0.2s;
    opacity: 0.75;
    transform: scale(0.95);
}

/* Shrinks and slightly fades further on logo only*/
h3:active{
    opacity: 0.5;
    transform: scale(0.9);
}

/* Flex box column for navbar*/
#navbar{
    position: absolute; z-index: 5; /* Acts like an overlay */
    left: 0; top: 0; /* Moves to top left */
    display: flex; flex-direction: column;
    width: 55px; height: 100vh;
    border-right: solid 1px rgb(236, 236, 236); 
    box-sizing: border-box; /* Includes border size in width / height calculation */
    align-items:baseline; /* Center align*/
    background-color: rgb(255, 255, 255);
    transition: 
        background-color 0.3s cubic-bezier(.42, .94, .31, .99),
        width 0.3s cubic-bezier(.42, .94, .31, .99);
    user-select: none;
}

/* Vertically center text */
#navbar p {
    display: flex; align-items: center;
}

/* Sets vertical position of each icon*/
#navbar > a:nth-child(2) { position: absolute; top: 100px; }
#navbar > a:nth-child(3) { position: absolute; top: 150px; }
#navbar > a:nth-child(4) { position: absolute; top: 200px; }
#navbar > a:nth-child(5) { position: absolute; top: 250px; }
#navbar > a:nth-child(6) { position: absolute; bottom: 10px; }

/* When navbar is expanded*/
#navbar.expand{
    width: 280px;
    background-color: rgb(248, 248, 248);
}

/* Hamburger menu icon*/
#hamburgerMenuNavbarIcon{
    transition: background-color 0.2s;
    border-radius: 5px;
    padding: 10px;
}

/* Hamburger menu icon hovered*/
#hamburgerMenuNavbarIcon:hover{
    background-color: rgb(232, 232, 232);
    cursor: pointer;
}

/* Each item inside the navbar has an <a> containing a long horizontal div called navbarItem, containing both icon and text*/
.navbarItem{
    margin-top: 10px; margin-bottom: 10px; margin-left: 5px; 
    border-radius: 5px;
    transition: background-color 0.2s, width 0.2s, transform 0.2s;
    display: flex; flex-direction: row; /* Flexbox config */
    align-items: center;
    width: 33px; height: 40px;
}

/* All text in any navbar item */
.navbarItem > p,
.navbarItem > a > h3 /* Logo accessed differently*/{
    white-space: nowrap;
    transition: 0.15s ease-out;
    color: rgba(255, 255, 255, 0); /* Hides text color when not expanded */
    margin-left: 15px;
}

/* Hailshare Logo */
.navbarItem >  a > h3{
    margin-left: 5px; 
}

/* Every navbarItem div EXCEPT the Hailshare Logo */
a > .navbarItem{
    transition: 0.15s ease-out;
    padding-left: 10px;
}

/* Every navbarItem div EXCEPT the Hailshare Logo when hovered */
a > .navbarItem:hover {
    background-color: rgb(232, 232, 232) !important;
    cursor: pointer;
}

/* Decrease scale on click */
a > .navbarItem:active {
    scale: 0.95;
}

/* Increases the navbarItem div width to fit text */
.navbarItem.expand{
    width: 250px;
}

/* Reveal text in navbarItem when expanded */
.navbarItem.expand > p, 
.navbarItem.expand > a > h3 {
    color: rgb(0, 0, 0);
}

/* When menu is expanded, show a contrasting color on the current page loaded to indicate current page open (controlled by nth-child)*/
a:nth-of-type(1) > .navbarItem{
    background-color: rgb(216, 216, 216);
}

/* When the navbar is expanded, push content div to the right*/
#content.expand{
    margin-left: 280px;
}