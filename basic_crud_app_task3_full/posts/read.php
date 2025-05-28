<?php
include("../includes/db.php");
session_start();
if (!isset($_SESSION["user"])) header("Location: ../auth/login.php");

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$where = $search ? "WHERE title LIKE '%$search%' OR content LIKE '%$search%'" : "";
$query = "SELECT * FROM posts $where LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
$total = $conn->query("SELECT COUNT(*) as total FROM posts $where")->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);
?>
<!DOCTYPE html>
<html><head><title>Posts</title><?= include('../assets/css/style.css'); ?></head><body class='container mt-5'>
<h2>Welcome <?= $_SESSION["user"] ?> | <a href='../auth/logout.php'>Logout</a></h2>
<form method="GET"><input name="search" class="form-control mb-2" placeholder="Search..." value="<?= htmlspecialchars($search) ?>"></form>
<a class='btn btn-primary mb-3' href='create.php'>+ New Post</a>
<?php while ($row = $result->fetch_assoc()): ?>
  <div class='card mb-2'><div class='card-body'>
    <h4><?= htmlspecialchars($row["title"]) ?></h4>
    <p><?= nl2br(htmlspecialchars($row["content"])) ?></p>
    <a class='btn btn-sm btn-warning' href='edit.php?id=<?= $row["id"] ?>'>Edit</a>
    <a class='btn btn-sm btn-danger' href='delete.php?id=<?= $row["id"] ?>'>Delete</a>
  </div></div>
<?php endwhile; ?>
<nav><ul class='pagination'>
<?php for ($i = 1; $i <= $total_pages; $i++): ?>
  <li class='page-item <?= $i == $page ? "active" : "" ?>'>
    <a class='page-link' href='?page=<?= $i ?>&search=<?= $search ?>'><?= $i ?></a>
  </li>
<?php endfor; ?>
</ul></nav>
</body></html>
