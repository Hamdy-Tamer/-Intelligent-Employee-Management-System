<?php include('connection.php');

$output = array();
$sql = "SELECT * FROM users ";

$totalQuery = mysqli_query($con, $sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
    0 => 'id',
    1 => 'username',
    2 => 'email',
    3 => 'mobile',
    4 => 'city',
);

// Search
$search_condition = "";
if (isset($_POST['search']['value']) && $_POST['search']['value'] != '') {
    $search_value = mysqli_real_escape_string($con, $_POST['search']['value']);
    $search_condition = " WHERE username LIKE '%" . $search_value . "%' 
                         OR email LIKE '%" . $search_value . "%' 
                         OR mobile LIKE '%" . $search_value . "%' 
                         OR city LIKE '%" . $search_value . "%'";
}

$sql .= $search_condition;

// Get filtered count
$filtered_sql = "SELECT COUNT(*) as total FROM users " . $search_condition;
$filtered_query = mysqli_query($con, $filtered_sql);
$filtered_row = mysqli_fetch_assoc($filtered_query);
$total_filtered_rows = $filtered_row['total'];

// Order
if (isset($_POST['order'])) {
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $columns[$column_name] . " " . $order;
} else {
    $sql .= " ORDER BY id DESC";
}

// Pagination
if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT " . $start . ", " . $length;
}

$query = mysqli_query($con, $sql);
$data = array();
while ($row = mysqli_fetch_assoc($query)) {
    $sub_array = array();
    $sub_array[] = $row['id'];
    $sub_array[] = $row['username'];
    $sub_array[] = $row['email'];
    $sub_array[] = $row['mobile'];
    $sub_array[] = $row['city'];
    $sub_array[] = '<a href="javascript:void(0);" data-id="' . $row['id'] . '" class="btn btn-info btn-sm editbtn"><i class="fas fa-edit"></i> Edit</a> ' .
                   '<a href="javascript:void(0);" data-id="' . $row['id'] . '" class="btn btn-danger btn-sm deleteBtn"><i class="fas fa-trash-alt"></i> Delete</a>';
    $data[] = $sub_array;
}

$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $total_all_rows,
    'recordsFiltered' => $total_filtered_rows,
    'data' => $data,
);
echo json_encode($output);
?>