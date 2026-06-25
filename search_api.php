<?php
include("config.php");

if(isset($_GET['query'])) {
    $query = mysqli_real_escape_string($conn, $_GET['query']);

    $sql = "SELECT * FROM products WHERE name LIKE '%$query%' LIMIT 10";
    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($result)) {
?>
        <div class="live-item">
            <img src="<?php echo $row['image']; ?>">
            <span><?php echo $row['name']; ?></span>
        </div>
<?php
    }
}
?>