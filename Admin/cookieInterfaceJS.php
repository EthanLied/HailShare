<?php header("Content-type: application/javascript"); ?>

async function grabCookie(key) {
    const response = await fetch(`/hailshare/Admin/cookieInterface.php?key=${encodeURIComponent(key)}&mode=read`);
    const data = await response.json();
    return data.value ?? null;
}

async function setCookie(key, value) {
    const response = await fetch(`/hailshare/Admin/cookieInterface.php?key=${encodeURIComponent(key)}&mode=write&value=${encodeURIComponent(value)}`);
    const data = await response.json();
    return data.success ?? false;
}

async function clearCookies() {
    const response = await fetch('/hailshare/Admin/cookieInterface.php?mode=clear');
    const data = await response.json();
    return data.success ?? false;
}
