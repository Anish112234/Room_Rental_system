
<?php
session_start();
include("dbconnection.php");

if (!isset($_SESSION['user']['id'])) {
    header("Location: /Room_Rental_System/auth/login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $_SESSION['user']['id'];

/* Check room id */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid room ID");
}

$room_id = intval($_GET['id']);

/* Fetch room details */
$room_query = mysqli_query($conn, "SELECT * FROM rooms WHERE id = '$room_id' AND status='available'");
if (!$room_query || mysqli_num_rows($room_query) == 0) {
    die("Room not found or unavailable");
}
$room = mysqli_fetch_assoc($room_query);

/* Default values for form   (book garna ko laghi)*/
$check_in = "";
$check_out = "";
$guests = 1;
$phone = "";
$payment_method = "";
$transaction_id = "";
$special_request = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $check_in = mysqli_real_escape_string($conn, trim($_POST['check_in']));
    $check_out = mysqli_real_escape_string($conn, trim($_POST['check_out']));
    $guests = intval($_POST['guests']);
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $payment_method = mysqli_real_escape_string($conn, trim($_POST['payment_method']));
    $transaction_id = mysqli_real_escape_string($conn, trim($_POST['transaction_id']));
    $special_request = mysqli_real_escape_string($conn, trim($_POST['special_request']));

    $booking_date = date("Y-m-d");
    $status = "pending";

    /* Payment status */
    if ($payment_method == "Cash on Arrival") {
        $payment_status = "Pending";
    } else {
        $payment_status = "Verification Pending";
    }

    /*for correct information ko laghi (VALIDATION)  */

    if (empty($check_in) || empty($check_out) || empty($phone) || empty($payment_method)) {
        echo "<script>alert('Please fill all required fields!');</script>";
    } 
    elseif ($check_in < date("Y-m-d")) {
        echo "<script>alert('Check-in date cannot be in the past!');</script>";
    }
    elseif ($check_out <= $check_in) {
        echo "<script>alert('Check-out date must be after check-in date!');</script>";
    }
    elseif ($guests < 1) {
        echo "<script>alert('Guests must be at least 1!');</script>";
    }
    elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        echo "<script>alert('Please enter a valid 10-digit phone number!');</script>";
    }
    elseif ($payment_method != "Cash on Arrival" && empty($transaction_id)) {
        echo "<script>alert('Transaction ID is required for online payment methods!');</script>";
    }
    else {

        /*  CHECK ROOM BOOKING CONFLICT 
           Overlap rule:
           Existing booking overlaps if:
           existing.check_in < new_check_out
           AND existing.check_out > new_check_in
        */
        $conflict_sql = "
            SELECT * FROM bookings
            WHERE room_id = '$room_id'
            AND status != 'cancelled'
            AND check_in < '$check_out'
            AND check_out > '$check_in'
        ";

        $check_conflict = mysqli_query($conn, $conflict_sql);

        if (!$check_conflict) {
            die("Booking check failed: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($check_conflict) > 0) {
            echo "<script>
                alert('Sorry! This room is already booked for the selected dates.');
                window.location='customer.php';
            </script>";
            exit();
        }

        /*...........  INSERT BOOKING  */
        $sql = "INSERT INTO bookings
            (user_id, room_id, booking_date, check_in, check_out, guests, phone, payment_method, payment_status, transaction_id, special_request, status)
            VALUES
            ('$user_id', '$room_id', '$booking_date', '$check_in', '$check_out', '$guests', '$phone', '$payment_method', '$payment_status', '$transaction_id', '$special_request', '$status')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                alert('Room booked successfully!');
                window.location='my_bookings.php';
            </script>";
            exit();
        } else {
            echo "<script>alert('Booking failed: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>

    <link rel="stylesheet" href="bookrooom.css">
</head>
<body>

<div class="booking-container">

    <!-- LEFT SIDE ROOM DETAILS -->
    <div class="room-box">
        <?php if(!empty($room['image'])) { ?>
            <img src="/Room_Rental_System/uploads/<?php echo htmlspecialchars($room['image']); ?>" alt="Room Image" class="room-image">
        <?php } else { ?>
            <img src="/Room_Rental_System/uploads/default.png" alt="Default Room Image" class="room-image">
        <?php } ?>

        <div class="room-details">
            <h2><?php echo htmlspecialchars($room['title']); ?></h2>

            <div class="price-tag">
                Rs. <?php echo htmlspecialchars($room['price']); ?> / month
            </div>

            <p><strong>Location:</strong> <?php echo htmlspecialchars($room['location']); ?></p>
            <p class="room-desc"><strong>Description:</strong> <?php echo htmlspecialchars($room['description']); ?></p>

            <a href="customer.php" class="back-btn">← Back to Rooms</a>
        </div>
    </div>

    <!-- RIGHT SIDE BOOKING FORM -->
    <div class="form-box">
        <h2>Book This Room</h2>

        <form method="POST">

            <div class="form-row">
                <div class="form-group">
                    <label for="check_in">Check-in Date</label>
                    <input 
                        type="date" 
                        id="check_in"
                        name="check_in" 
                        min="<?php echo date('Y-m-d'); ?>" 
                        value="<?php echo htmlspecialchars($check_in); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="check_out">Check-out Date</label>
                    <input 
                        type="date" 
                        id="check_out"
                        name="check_out" 
                        min="<?php echo date('Y-m-d'); ?>" 
                        value="<?php echo htmlspecialchars($check_out); ?>"
                        required
                    >
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="guests">Number of Guests</label>
                    <input 
                        type="number" 
                        id="guests"
                        name="guests" 
                        min="1" 
                        value="<?php echo htmlspecialchars($guests); ?>" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input 
                        type="text" 
                        id="phone"
                        name="phone" 
                        placeholder="98XXXXXXXX"
                        value="<?php echo htmlspecialchars($phone); ?>"
                        required
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="">-- Select Payment Method --</option>
                    <option value="Cash on Arrival" <?php if($payment_method == "Cash on Arrival") echo "selected"; ?>>Cash on Arrival</option>
                    <option value="eSewa" <?php if($payment_method == "eSewa") echo "selected"; ?>>eSewa</option>
                    <option value="Khalti" <?php if($payment_method == "Khalti") echo "selected"; ?>>Khalti</option>
                    <option value="Bank Transfer" <?php if($payment_method == "Bank Transfer") echo "selected"; ?>>Bank Transfer</option>
                </select>
                <p class="payment-note">If you choose online payment, enter the transaction ID below.</p>
            </div>

            <div class="form-group">
                <label for="transaction_id">Transaction ID</label>
                <input 
                    type="text" 
                    id="transaction_id"
                    name="transaction_id" 
                    placeholder="Enter transaction ID if paid online"
                    value="<?php echo htmlspecialchars($transaction_id); ?>"
                >
            </div>

            <div class="form-group">
                <label for="special_request">Special Request</label>
                <textarea 
                    name="special_request" 
                    id="special_request"
                    placeholder="Any special request..."
                ><?php echo htmlspecialchars($special_request); ?></textarea>
            </div>

            <button type="submit" class="btn-book">Confirm Booking</button>
            <p class="note">Your booking request will be submitted for confirmation.</p>
        </form>
    </div>

</div>

</body>
</html>

