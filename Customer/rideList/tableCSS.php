<?php header("Content-type: text/css"); ?>

table{
    font-size: 12px;
}

/* Hover effect on table rows and icons inside tables */
tr{
    transition: 0.3s cubic-bezier(.42,.94,.31,.99);
}

tr:hover{
    background-color: rgb(247, 247, 247);
}

/* Set all columns to be centered, except 1 and 2 */
tr > :nth-child(1),
tr > :nth-child(2) {
    text-align: left;
}

tr > td:nth-child(1), tr > td:nth-child(2){
    font-size: 12px;
    vertical-align: top;
}

tr > :nth-child(n+3) {
  text-align: center;
}

/* Makes header sticky*/
th{
    position: sticky;
    top: 0;
    background-color: white;
    z-index: 1;
    font-size: 15px;
}

/* Define width allocation for each column of the table */
th:nth-child(1),th:nth-child(2)  {
  width: 25%;
}

th:nth-child(3), th:nth-child(4) {
  width: 12%;
}

th:nth-child(5) {
  width: 11%;
}

th:nth-child(6), th:nth-child(7) {
  width: 7%;
}

th:nth-child(8), th:nth-child(9){
  width: 1%;
}

td{
    font-size: 15px;
    vertical-align: middle !Important;
}

/* Defines message and join button behavior*/
td > a > span{
    transition: 0.3s cubic-bezier(.42,.94,.31,.99);
    padding: 10px;
    line-height: 0;
    border-radius: 10px;
}

td > a > span:hover{
    scale: 0.95;
    background-color: rgb(230, 230, 230);
    cursor: pointer;
}

td > a > span:active{
    scale: 0.9;
    background-color: rgb(236, 236, 236);
}

td > p{
    text-align: justify;
}

/* Empty Table */
#emptyState{
    height: 60vh;
}

#emptyState td {
    text-align: center;
    vertical-align: middle;
    height: 200%;
    color: grey;
    font-size: 20px;
}

