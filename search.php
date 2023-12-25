<?php

require('database.php');
$database = new Database();
$pdo = $database->pdo;

if (isset($_GET['search'])) {
    // Get the search term from the URL
    $searchTerm = $_GET['search'];

    // Perform a database query based on the search term across multiple columns
    $query = "SELECT * FROM cars WHERE 
              car_brand LIKE :searchTerm OR
              car_model LIKE :searchTerm OR
              car_year = :searchYear OR
              car_licenseplate LIKE :searchTerm OR
              car_dailyprice = :searchPrice";

    // Execute the query using prepared statements to prevent SQL injection
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
    // Assuming the search term can also be a year or price
    $stmt->bindValue(':searchYear', $searchTerm, PDO::PARAM_INT);
    $stmt->bindValue(':searchPrice', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the search results
    if ($results) {
        foreach ($results as $result) {
            // Display each matching car
            echo '<h3>' . $result['car_brand'] . ' ' . $result['car_model'] . '</h3>';
            // Display other car details as needed
        }
    } else {
        echo 'No results found.';
    }
}
?>