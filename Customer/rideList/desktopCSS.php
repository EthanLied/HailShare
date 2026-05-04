<?php header("Content-type: text/css"); ?>


/* Removes default spacing*/
*{
    padding: 0;
    margin: 0;
}

body{
    overflow-y:hidden;
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

/* Div for the main contents of the page*/
#content{
    margin-left: 55px; padding: 50px;
    box-sizing: border-box;
    display: flex; flex-direction: column;
    transition: 0.3s cubic-bezier(.42,.94,.31,.99);
    overflow-y: auto;
    height: 100vh;
}

/* Define input box dimensions */
#addressInputs > input{
    width: 100%;
    height: 30px;
}

/* Spacing for input labels */
#addressInputs > p{
    margin-top: 15px; margin-bottom: 5px;
}

/* Spacing for title */
#ridesTitle{
    margin-top: 30px; margin-bottom: 10px;
}

/* Spacing and sizing for search btn */
#searchBtn{
    margin-top: 10px;
    height: 40px; width: 80px;
    padding: 7px;
    font-size: 15px; font-weight: 535;
}

/* Mini scrollbar for table*/
#tableContainer{
    border-radius: 10px;
    border: solid 0.05rem rgb(208, 208, 208);
    overflow-y: auto;
    min-height: 80vh;
}

.negativePrice{
    color: rgb(180, 0, 0);
}

.positivePrice{
    color: rgb(3, 104, 0);
}

#pagination{
    display: flex; flex-direction: row;
    align-items: center;
    margin-top: 20px;
}

#pagination > input{
    max-width: 20px;
    text-align: center;
}

#paginationLabel{
    font-weight: 500;
    margin-bottom: 1px;
}

.paginationBtn{
    display: flex;
    border: none;  
    font-size: 15px; 
    padding:5px;
    align-items: center; justify-content: center; /* Centering */        
    cursor: pointer;
}

.paginationBtn p{
    margin: 0;              
    line-height: 0;
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

#sortByDropdown{
    margin-bottom: 20px;
}

#alert{
    color: red;
    text-align: center;
    margin-top: 30px;
}





