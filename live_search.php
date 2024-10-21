<?php
require_once('ajax/database.php');


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['search'])) {
        $searchterm = $_POST['search']; 
        $con = new database();

        try {
            $connection = $con->opencon();
            
            // Check if the connection is successful
            if ($connection) {
                // SQL query with JOIN
                $query = $connection->prepare("SELECT users.user_id, users.user_firstname, users.user_lastname, users.user_birthday, users.user_sex, users.user_email, users.user_name, users.user_profile_picture, CONCAT(user_address.city,', ', user_address.province) AS address FROM users INNER JOIN user_address ON users.user_id = user_address.user_id WHERE users.user_firstname LIKE ? OR users.user_lastname LIKE ? OR users.user_name LIKE ? OR users.user_email LIKE ? OR users.user_id LIKE ? OR CONCAT(user_address.city,', ', user_address.province) LIKE ?");
                $query->execute(["%$searchterm%", "%$searchterm%", "%$searchterm%", "%$searchterm%", "%$searchterm%", "%$searchterm%"]);


                $users = $query->fetchAll(PDO::FETCH_ASSOC);

                // Generate HTML for table rows
                $html = '';
                foreach ($users as $user) {
                    $html .= '<tr>';
                    $html .= '<td>' . $user['user_id'] . '</td>';
                    $html .= '<td>' . $user['user_lastname'] . '</td>';
                    $html .= '<td>' . $user['user_firstname'] . '</td>';
                    $html .= '<td>' . $user['user_sex'] . '</td>';
                    $html .= '<td>' . $user['user_email'] . '</td>';
                    $html .= '<td>'; // Action column
                    $html .= '<form action="update.php" method="post" style="display: inline;">';
                    $html .= '<input type="hidden" name="id" value="' . $user['user_id'] . '">';
                    $html .= '<button type="submit" class="btn btn-primary btn-sm">Edit</button>';
                    $html .= '</form>';
                    $html .= '</td>';
                    $html .= '</tr>';
                }
                echo $html;
            } else {
                echo json_encode(['error' => 'Database connection failed.']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'No search query provided.']);
    }
}