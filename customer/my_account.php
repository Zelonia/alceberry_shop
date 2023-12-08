<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

session_start();

if(!isset($_SESSION['customer_email'])){

echo "<script>window.open('../checkout.php','_self')</script>";


}else {

include("includes/db.php");
include("../includes/header.php");
include("functions/functions.php");
include("includes/main.php");


?>
  <main>
    <!-- HERO -->
    <div class="nero" style="background-image: url('images/banner.jpg');">
      <div class="nero__heading">
        <span class="nero__bold">My </span>Account
      </div>
      <p class="nero__text">
      </p>
    </div>
  </main>

<div id="content" ><!-- content Starts -->
<div class="container" ><!-- container Starts -->



<div class="col-md-12"><!-- col-md-12 Starts -->

<?php

$c_email = $_SESSION['customer_email'];

$get_customer = "select * from customers where customer_email='$c_email'";

$run_customer = mysqli_query($con,$get_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_confirm_code = $row_customer['customer_confirm_code'];

$c_name = $row_customer['customer_name'];

if(!empty($customer_confirm_code)){

?>

<div class="alert alert-danger"><!-- alert alert-danger Starts -->

<strong> Warning! </strong> Please Confirm Your Email and if you have not received your confirmation email

<a href="my_account.php?send_email" class="alert-link">

Send Email Again

</a>

</div><!-- alert alert-danger Ends -->

<?php } ?>

</div><!-- col-md-12 Ends -->

<div class="col-md-3"><!-- col-md-3 Starts -->

<?php include("includes/sidebar.php"); ?>

</div><!-- col-md-3 Ends -->

<div class="col-md-9" ><!--- col-md-9 Starts -->

<div class="box" ><!-- box Starts -->

<?php


// Cek apakah customer_email sudah di-set (sesi login)
if (!isset($_SESSION['customer_email'])) {
  echo "<script>window.open('../checkout.php','_self')</script>";
} else {
  // Ambil informasi pelanggan dari database
  $c_email = $_SESSION['customer_email'];
  $get_customer = "SELECT * FROM customers WHERE customer_email=?";
  $run_customer = mysqli_prepare($con, $get_customer);
  mysqli_stmt_bind_param($run_customer, "s", $c_email);
  mysqli_stmt_execute($run_customer);
  $result_customer = mysqli_stmt_get_result($run_customer);
  $row_customer = mysqli_fetch_array($result_customer);

  $customer_confirm_code = $row_customer['customer_confirm_code'];
  $c_name = $row_customer['customer_name'];

  // Periksa apakah customer_confirm_code tidak kosong
  if (!empty($customer_confirm_code)) {
      // Tampilkan peringatan jika belum dikonfirmasi
      echo "<div class='alert alert-danger'>
              <strong> Warning! </strong> Please confirm your email. If you haven't received the confirmation email,
              <a href='my_account.php?send_email' class='alert-link'>send email again</a>.
          </div>";
  }

  // Handle konfirmasi email
  if (isset($_GET['customer_confirm_code'])) {
      $customer_confirm_code_from_url = $_GET['customer_confirm_code'];

      // Periksa apakah customer_confirm_code valid di database
      $check_code_query = "SELECT * FROM customers WHERE customer_confirm_code = ?";
      $check_code_stmt = mysqli_prepare($con, $check_code_query);
      mysqli_stmt_bind_param($check_code_stmt, "s", $customer_confirm_code_from_url);
      mysqli_stmt_execute($check_code_stmt);
      $result = mysqli_stmt_get_result($check_code_stmt);

      if (mysqli_num_rows($result) > 0) {
          // Validasi sukses, perbarui data di database
          $update_customer = "UPDATE customers SET customer_confirm_code = '' WHERE customer_confirm_code = ?";
          $run_confirm = mysqli_prepare($con, $update_customer);
          mysqli_stmt_bind_param($run_confirm, "s", $customer_confirm_code_from_url);
          mysqli_stmt_execute($run_confirm);

          echo "<script>alert('Your Email Has Been Confirmed')</script>";
          echo "<script>window.open('my_account.php?my_orders','_self')</script>";
      } else {
          // Invalid customer_confirm_code, berikan pesan kesalahan atau arahkan pengguna ke halaman tertentu
          echo "<script>alert('Invalid Confirmation Code')</script>";
          echo "<script>window.open('my_account.php?my_orders','_self')</script>";
      }
  }

  // Handle pengiriman email konfirmasi
  if (isset($_GET['send_email'])) {
      $subject = "Email Confirmation Message";
      $from = "arvissie@gmail.com";

      // Konstruksi tautan konfirmasi email dengan customer_confirm_code
      $confirmation_link = 'http://localhost/ecommerceNetLCB/ecommerce-website-php/customer/my_account.php?customer_confirm_code=' . $customer_confirm_code . '&verify=true';


      // Pesan email dengan tautan konfirmasi
      $message = "
          <h2>Email Confirmation By Computerfever.com $c_name</h2>
          <a href='$confirmation_link' style='display:inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-align: center; text-decoration: none; font-size: 16px;'>Confirm Email</a>
      ";

      // Pengaturan header email
      $headers = "From: $from \r\n";
      $headers .= "Content-type: text/html\r\n";

      // Kirim email menggunakan PHPMailer
      $mail = new PHPMailer(true);
      try {
          // Konfigurasi Mailer
          $mail->SMTPDebug = 2;
          $mail->isSMTP();
          $mail->Host = 'localhost'; // MailHog berjalan di localhost
          $mail->SMTPAuth = false; // MailHog biasanya tidak memerlukan otentikasi
          $mail->Port = 1025; // Port default MailHog untuk SMTP

          // Alamat email pengirim dan penerima
          $mail->setFrom($from);
          $mail->addAddress($c_email);

          // Konten email
          $mail->isHTML(true);
          $mail->Subject = $subject;
          $mail->Body = $message;

          // Kirim email
          $mail->send();

          echo "<script>alert('Your Confirmation Email Has Been sent to you, check your inbox')</script>";
          echo "<script>window.open('my_account.php?my_orders','_self')</script>";
      } catch (Exception $e) {
          echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
  }
}


// HTML Form dalam PHP




// Jika parameter customer_confirm_code dan verify ada dalam URL, konfirmasikan email
if (isset($_GET['customer_confirm_code']) && isset($_GET['verify']) && $_GET['verify'] === 'true') {
  // Logika verifikasi email
  $customer_confirm_code_from_url = $_GET['customer_confirm_code'];

  // ...

  // Jika verifikasi berhasil, perbarui data di database
  if (verifikasiBerhasil($customer_confirm_code_from_url)) {
      // Lakukan pembaruan kolom customer_confirm_code menjadi kosong
      $update_customer_query = "UPDATE customers SET customer_confirm_code = '' WHERE customer_confirm_code = ?";
      $update_stmt = mysqli_prepare($con, $update_customer_query);
      mysqli_stmt_bind_param($update_stmt, "s", $customer_confirm_code_from_url);

      if ($update_stmt) {
          mysqli_stmt_execute($update_stmt);
          echo "<script>alert('Your Email Has Been Confirmed')</script>";
          echo "<script>window.open('my_account.php?my_orders','_self')</script>";
      } else {
          echo "Error updating data: " . mysqli_error($con);
      }
  } else {
      echo "<script>alert('Invalid Confirmation Code')</script>";
      echo "<script>window.open('my_account.php','_self')</script>";
  }
}


if(isset($_GET[$customer_confirm_code])){

$update_customer = "update customers set customer_confirm_code='' where customer_confirm_code='$customer_confirm_code'";

$run_confirm = mysqli_query($con,$update_customer);

echo "<script>alert('Your Email Has Been Confirmed')</script>";

echo "<script>window.open('my_account.php?my_orders','_self')</script>";

}

if(isset($_GET['send_email'])){

$subject = "Email Confirmation Message";

$from = "sad.ahmed22224@gmail.com";

$message = "

<h2>
Email Confirmation By Computerfever.com $c_name
</h2>

<a href='localhost/ecom_store/customer/my_account.php?$customer_confirm_code'>

Click Here To Confirm Email

</a>

";

$headers = "From: $from \r\n";

$headers .= "Content-type: text/html\r\n";

mail($c_email,$subject,$message,$headers);

echo "<script>alert('Your Confirmation Email Has Been sent to you, check your inbox')</script>";

echo "<script>window.open('my_account.php?my_orders','_self')</script>";

} 



if(isset($_GET['my_orders'])){

include("my_orders.php");

}

if(isset($_GET['pay_offline'])) {

include("pay_offline.php");

}

if(isset($_GET['edit_account'])) {

include("edit_account.php");

}

if(isset($_GET['change_pass'])){

include("change_pass.php");

}

if(isset($_GET['delete_account'])){

include("delete_account.php");

}

if(isset($_GET['my_wishlist'])){

include("my_wishlist.php");

}

if(isset($_GET['delete_wishlist'])){

include("delete_wishlist.php");

}

?>

</div><!-- box Ends -->


</div><!--- col-md-9 Ends -->

</div><!-- container Ends -->
</div><!-- content Ends -->



<?php

include("../includes/footer.php");

?>

<script src="js/jquery.min.js"> </script>

<script src="js/bootstrap.min.js"></script>

</body>
</html>
<?php } ?>
