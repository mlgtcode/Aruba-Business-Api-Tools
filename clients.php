<?php
include("api.php");
$config = new Configuration();
$token = $config->acquireToken();
$resourcePath = "/api/endusers";
$url = $config->getUrl($resourcePath, []);
$headers = $config->getHeader($token);

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

$clients = [];
if (isset($data['Object']) && is_array($data['Object'])) {
    $clients = $data['Object'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Clients List</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding-top: 70px;
    }
    footer {
      background: #343a40;
      color: #fff;
      padding: 20px 0;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Clients</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" href="#">Home</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container mt-5 pt-3">
    <h1 class="mb-4">Clients</h1>
    <?php if (!empty($clients)): ?>
      <div class="list-group">
        <?php foreach ($clients as $user): ?>
          <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
            <div class="d-flex w-100 justify-content-between">
              <h5 class="mb-1">
                <?php echo htmlspecialchars($user['Name'] . " " . $user['Surname']); ?>
              </h5>
              <small><?php echo htmlspecialchars($user['Username']); ?></small>
            </div>
            <p class="mb-1">
              <strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?>
            </p>
            <?php if (!empty($user['CompanyName'])): ?>
              <small><strong>Company:</strong> <?php echo htmlspecialchars($user['CompanyName']); ?></small><br>
            <?php endif; ?>
            <small>
              <strong>Address:</strong>
              <?php echo htmlspecialchars($user['Address']); ?>, <?php echo htmlspecialchars($user['City']); ?>
            </small>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-warning" role="alert">
        No clients found.
      </div>
    <?php endif; ?>
  </div>
  <!-- Footer -->
  <footer class="text-center">
    <div class="container">
      <p class="mb-0">&copy; <?php echo date("Y"); ?> Clients List. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
