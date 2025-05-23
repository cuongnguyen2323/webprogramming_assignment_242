<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <header class="mb-3 d-flex justify-content-between">
        <div class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3" role="button"></i>
        </div>
        <div>
            <a onclick="confirmLogout()" class="btn btn-primary">Log out</a>
        </div>
    </header>
</body>

</html>

<script>
function confirmLogout() {
    if (confirm("Log out?")) {
        window.location.href = '/webprogramming_assignment_242/logout';
    }
}
</script>