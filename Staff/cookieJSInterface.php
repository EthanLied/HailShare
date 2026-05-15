<?php header("Content-type: application/javascript");?>

async function grabCookie(key) {
    const response = await fetch(`/hailshare/Staff/cookieInterface.php?key=${encodeURIComponent(key)}&mode=read`);
    const data = await response.json();
    return data.value ?? null;
}

async function setCookie(key, value) {
    const res = await fetch(`/hailshare/Staff/cookieInterface.php?key=${encodeURIComponent(key)}&mode=write&value=${encodeURIComponent(value)}`);
    const data = await res.json();
    return data.success ?? false;
}