<?php header("Content-type: text/css"); ?>

/* Removes default spacing*/
*{
    padding: 0;
    margin: 0;
}

/* Removes blue text and underline from hyperlinks*/
a{
    text-decoration: none;
    color: inherit;
}

/* Hides mobile components in desktop mode */
.mobileComponent{
    display: none;
}

#goBackBtn{
    display: flex; flex-direction: row;
    justify-content: center; align-items: center;
    padding: 5px; padding-right: 10px;
    margin-top: 10px;
}

/* Define input box dimensions */
#addressInputs > input{
    width: 100%;
    height: 30px;
}

/* Spacing for input labels */
#addressInputs > p{
    margin-top: 15px;; margin-bottom: 5px;
}

/* Rest of attributes will be stored in a grid container */
#attributeDropdownsContainer{
    margin-top: 15px; margin-right: 35px;
    display: grid;
    grid-template-columns: 30% 30% 20% 20%;
    gap: 5px 15px;
}

/* Time selection will use an additional div */
#timeSelector {
    display: flex; align-items: center; gap: 5px; 
}

#timeSelector select {
    width: 100%;
    text-align: center;
}

#createRideBtn{
    min-height: 50px; width: 300px;
    margin-top: 25px; margin-bottom: 25px;
    font-size: 15px; font-weight: 700;
    align-self: center;
    cursor: pointer;
}

#notice{
    align-self: center; 
    background-color: #fff1f2; color: #7f1d1d;  
    margin-top: 20px; margin-bottom: 20px; padding: 15px;
    text-align: center; font-weight: 450; font-size: 10px;
    font-size: 20px;
    border: 1.5px solid #fecaca; border-radius: 10px;
}

.addressDropdownWrapper{
    width: 100%;
    height: 40px;
}

.addressDropdownWrapper > input{
    width: 100%; height: 100%;
    box-sizing: border-box;
}

.addressDropdown{
    display: flex; flex-direction: column;
    position: relative;
    width: 100%; height: 500%;
    justify-self: center; align-self: center;
}

.addressDropdownItem{
    height: 20%;
    border: solid 0.7px rgb(208, 208, 208);
    border-radius: 6px;
    padding: 4px 2px 4px 8px;
    background-color: white;
    display: flex; 
    align-items: center;
    z-index: 1;

    /* Hides Overflow*/
    white-space: nowrap;     
    overflow: hidden;      
    text-overflow: ellipsis; 
}

.addressDropdownItem:hover{
    background-color: #f0f0f0;
    cursor: pointer;
}

#alert{
    text-align: center;
    margin-top: 20px;
    font-weight: 500; font-size: 20px; color: white;
}




