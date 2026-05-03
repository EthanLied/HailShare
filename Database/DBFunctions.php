<?php
header("Content-type: application/javascript");
?>

async function saveDB(){

    const tables = ["users", "rides", "ride_participants", "ratings", "ride_chat_rooms", "ride_chat_messages", "support_chat_rooms", "support_chat_messages", "notifications"];

    let insertStatements = [];

    // For each table
    for (const table of tables){
        
        const DBData = await readDB(table)

        // Grabs insert statement
        const sqlText = generateInserts(table, DBData);

        insertStatements.push(sqlText)

    }

    insertStatements = insertStatements.flat()
    
    // Sent to PHP
    const saveResponse = await fetch('/hailshare/Database/DBsave.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(insertStatements)
    });

    // Captures result
    const result = await saveResponse.json();

    result.success ? console.log('Saved:', result.file) : console.error('Failed:', result.error);

}

async function readDB(table){

    // Obtains DB Data
    const response = await fetch(`/hailshare/Database/DBread.php?table=${table}`);
    const DBData = await response.json();

    return DBData

    // DB Data is structured like this for each table:

    // Array Layer - Level 0, each element in array represents each record
    // Each element itself is a dict (key-value pair)
    // 0: {}
    // 1: {}

    // Dict Layer - Level 1, each element is a key-value pair that represents a column and it's value
    // 'first_name': 'John'
    // 'last_name' : 'Doe'
    
}

// JSON data into SQL strings
function generateInserts(tableName, data) {
    if (!data || data.length === 0) {
        return `-- No data found for table: ${tableName}`;
    }

    // Get the column names from the first row
    const columns = Object.keys(data[0]).join('`, `');

    // For each record
    const sqlStatements = data.map(row => {

        // For each attribute 
        // Object.values used as row is a dict
        const values = Object.values(row).map(val => {
            if (val === null) return 'NULL';

            // Convert to string and escape single quotes by doubling them
            return `'${String(val).replace(/'/g, "''")}'`;
        }).join(', ');

        return `INSERT INTO \`${tableName}\` (\`${columns}\`) VALUES (${values});`;
    });

    // Join all statements with a line break
    return sqlStatements.join('\n');
}

// Send SQL queries here
async function queryDB(query) {

    // Provides PHP with query
    const response = await fetch('/hailshare/Database/queryDB.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query })
    });

    // Grab result
    const result = await response.json();

    // If SQL query error
    if (!result.success) {
        console.error("SQL Error:", result.error);
        return null;
    }

    // IF it's a SELECT query (has data)
    if (result.data !== undefined) {
        console.log("SQL Query Result:", result.data);     
        console.log("Record count:", result.count);
        return result.data;
    }

    // INSERT/UPDATE/DELETE return affected_rows
    console.log("Rows affected:", result.affected_rows);

    // Save changed data 
    saveDB()
}



