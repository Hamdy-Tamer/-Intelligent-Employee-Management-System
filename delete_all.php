<?php
include('connection.php');

$sql = "DELETE FROM users";
$query = mysqli_query($con, $sql);

if ($query) {
    $data = array(
        'status' => 'success',
        'deleted_count' => mysqli_affected_rows($con)
    );
    echo json_encode($data);
} else {
    $data = array(
        'status' => 'failed'
    );
    echo json_encode($data);
}
?>