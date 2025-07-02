
<?php
if (!isset($_SESSION['auth']) || $_SESSION['auth'] != 1) {
    header('Location: /login');
    die;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="icon" href="/favicon.png">
    <title>COSC 4806 - Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/home">COSC 4806</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav me-auto">
                <a class="nav-link text-light" href="/home">Dashboard</a>
                <a class="nav-link text-light" href="/notes">My Reminders</a>
                <a class="nav-link text-light" href="/secret">Secret</a>
            </div>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Welcome, <?= $_SESSION['username'] ?? 'User' ?>!</span>
                <a class="nav-link text-light" href="/logout">Logout</a>
            </div>
        </div>
    </div>
</nav>
<div class="container mt-4">
