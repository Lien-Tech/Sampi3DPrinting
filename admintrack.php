<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3d sampi printing service</title>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="admin.js"></script>

</head>
<body>
    <nav>
        <div class="navbarr">
            <a href="#" class="navbar-brand">
                <img src="photos/sampi.png" alt="Your Logo" class="logo" />
                3D Sampi Printing Service
            </a>
            <div class="navbar-toggler">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <ul class="navbar">
            <li><a href="adsampi.html">Home</a></li>
            <li><a href="adabout.html">About Us</a></li>
            <li><a href="adcontacts.html">Contact</a></li>
            <li><a href="adgallery.html">Gallery</a></li>
            <li><a href="adhelpcenter.html">FAQs</a></li>
            <li><button class="login-btn"><a href="sign_up.html">Log Out</a></button></li>
        </div>
    </nav>
    <br>

    <body>
      <h2 id="tracktitle">Customer Orders Tracking</h2>
      <div class="d-flex justify-content-center mt-5 px-1">
      <div class="table-responsive" style="max-width: 95%;">
      <table class="table table-bordered table-striped w-100" id="orderTable">
      <thead class="table-dark">
        <tr>
          <th>Order ID</th>
          <th>Customer Name</th>
          <th>Email</th>
          <th>Contact</th>
          <th>Service Category</th>
          <th>Service Item</th>
          <th>Custom Name</th>
          <th>Color</th>
          <th>Size (LxWxH)</th>
          <th>Quantity</th>
          <th>Estimated Time</th>
          <th>Order Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Database connection
        $host = 'localhost';
        $db = 'sampi_order_db';
        $user = 'root';
        $pass = '031924';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            $stmt = $pdo->query("
                SELECT o.order_id, c.first_name, c.last_name, c.email, c.contact_number,
                       o.service_category, o.service_item, o.custom_name, o.color,
                       o.size_length_cm, o.size_width_cm, o.size_height_cm,
                       o.quantity, o.estimated_time, o.order_date, o.status, o.completed_at
                FROM orders o
                JOIN customers c ON o.customer_id = c.customer_id
                ORDER BY o.order_date DESC
            ");

            foreach ($stmt as $row) {
             if ($row['status'] === 'Complete' && $row['completed_at']) {
    $formattedDate = date('M d, Y', strtotime($row['completed_at']));
    $status = "Complete ($formattedDate)";
    $buttonClass = 'btn-success';
    $disabled = 'disabled';
} else {
    $status = 'Pending';
    $buttonClass = 'btn-warning';
    $disabled = '';
}
              echo "<tr>
                        <td>{$row['order_id']}</td>
                        <td>{$row['first_name']} {$row['last_name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['contact_number']}</td>
                        <td>{$row['service_category']}</td>
                        <td>{$row['service_item']}</td>
                        <td>{$row['custom_name']}</td>
                        <td>{$row['color']}</td>
                        <td>{$row['size_length_cm']}×{$row['size_width_cm']}×{$row['size_height_cm']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['estimated_time']}</td>
                        <td>{$row['order_date']}</td>
                        <td>
<button class='btn btn-sm $buttonClass toggle-status' data-id='{$row['order_id']}' $disabled>$status</button>
                        </td>
                      </tr>";
            }

        } catch (PDOException $e) {
            echo "<tr><td colspan='12'>Error: " . $e->getMessage() . "</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('orderTable');

    table.addEventListener('click', function (e) {
        if (e.target.classList.contains('toggle-status')) {
            const button = e.target;
            const currentStatus = button.textContent.trim();

            if (currentStatus.startsWith('Complete')) return; // prevent toggle

            const now = new Date();
            const displayDate = now.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });

            button.textContent = `Complete (${displayDate})`;
            button.classList.remove('btn-warning');
            button.classList.add('btn-success');
            button.disabled = true;

            const orderId = button.dataset.id;

            fetch('status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, status: 'Complete' })
            });
        }
    });
});

</script>


</body>
</html>