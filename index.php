<?php
require_once 'templates/header.php';
?>

<?php
$db = new PDO("mysql:host=localhost;dbname=Practice_security", "root", "");
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['username']) && !empty($_POST['password'])) :
    $username = $_POST['username'];
    $password = $_POST['password'];
    // $query = "SELECT username, credit_card_number FROM userdata WHERE username='$username' and password='$password';";
    $query = "SELECT username, credit_card_number, password FROM userdata WHERE username=?";

    error_log("executed query: " . $query);
    // $statement = $db->query($query);
    $statement = $db->prepare($query);
    $statement->execute([$username]);
    $list_of_users = $statement->fetch();

    function checkPassword($list_of_users, $password)
    {
        if ($list_of_users) {
            if ($list_of_users['password'] === $password) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    if (!checkPassword($list_of_users, $password)) :
?>
        <div class="text-danger">Wrong username or password !</div>
    <?php
    else :
    ?>
        <div class="card m-3">
            <div class="card-header">
                <span><?php echo $list_of_users['username'] ?></span>
            </div>
            <div class="card-body">
                <p class="card-text">Your credit card number: <?php echo $list_of_users['credit_card_number']; ?></p>
            </div>
        </div>
        <hr>
<?php
    endif;
endif;
?>

<form action="" method="post" class="m-3">
    <div class="row mb-3 mt-3">
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter Username" name="username">
        </div>
        <div class="col">
            <input type="text" class="form-control" placeholder="Enter password" name="password">
        </div>
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">View your data</button>
    </div>
</form>

<?php
require_once 'templates/footer.php';
?>