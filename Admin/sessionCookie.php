<?php
function syncUserCookieToSession() {
    if (!isset($_COOKIE['user_id'])) {
        return;
    }

    $userId = filter_var($_COOKIE['user_id'], FILTER_VALIDATE_INT);

    if ($userId === false || $userId <= 0) {
        return;
    }

    $_SESSION['user_id'] = $userId;
    $_SESSION['admin_user_id'] = $userId;
}
?>