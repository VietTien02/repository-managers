<div class="account">
    <div class="user">
        <i class="fa-regular fa-user"></i>
    </div>
    <div class="name">
        <?php
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        echo '<h3>' . $_SESSION['username'] . '</h3>';
        ?>
    </div>
    <div class="logout">
        <a href="handle/logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
</div>