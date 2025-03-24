<?php
include("api.php");
$config = new Configuration();
$token = $config->acquireToken();
// api.arubabusiness.it
$resourcePath = "/api/pricelist";
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
$pricelistItems = [];
if (isset($data['Object']['PricelistItems']) && is_array($data['Object']['PricelistItems'])) {
    $pricelistItems = $data['Object']['PricelistItems'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pricelist</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
      <a class="navbar-brand" href="#">Pricelist</a>
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

  <div class="container mt-4">
    <h1 class="mb-4">Pricelist: Items</h1>
    <p>Note: All prices are in <strong>EURO</strong>.</p>
    <div class="mb-3">
      <button id="exportBtn" class="btn btn-primary me-2">Export to CSV</button>
      <button id="updatePriceBtn" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#priceModal">
        Apply Price Increase
      </button>
    </div>
    <?php if (!empty($pricelistItems)): ?>
      <table id="pricelistTable" class="table table-striped table-bordered sortable">
        <thead class="table-dark">
          <tr>
            <th>Id</th>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Service Name</th>
            <th>Service Type</th>
            <th>Price</th>
            <th>Renewal Price</th>
            <th>Full Price</th>
            <th>Full Renewal Price</th>
            <th>Month Duration</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pricelistItems as $item): ?>
            <tr>
              <td><?php echo htmlspecialchars($item['Id']); ?></td>
              <td><?php echo htmlspecialchars($item['ProductId']); ?></td>
              <td><?php echo htmlspecialchars($item['ProductName']); ?></td>
              <td><?php echo htmlspecialchars($item['ServiceName'] == "*" ? "N/A" : $item['ServiceName']); ?></td>
              <td><?php echo htmlspecialchars($item['ServiceType']); ?></td>
              <td class="price" data-baseprice="<?php echo htmlspecialchars($item['Price']); ?>">
                <?php echo '€ ' . number_format($item['Price'], 2); ?>
              </td>
              <td class="renewal-price" data-baseprice="<?php echo htmlspecialchars($item['RenewalPrice']); ?>">
                <?php echo '€ ' . number_format($item['RenewalPrice'], 2); ?>
              </td>
              <td class="full-price" data-baseprice="<?php echo htmlspecialchars($item['FullPrice']); ?>">
                <?php echo '€ ' . number_format($item['FullPrice'], 2); ?>
              </td>
              <td class="full-renewal-price" data-baseprice="<?php echo htmlspecialchars($item['FullRenewalPrice']); ?>">
                <?php echo '€ ' . number_format($item['FullRenewalPrice'], 2); ?>
              </td>
              <td><?php echo htmlspecialchars($item['MonthDuration']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-warning" role="alert">
        No pricelist items found.
      </div>
    <?php endif; ?>
  </div>

  <div class="modal fade" id="priceModal" tabindex="-1" aria-labelledby="priceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="priceModalLabel">Apply Price Increase</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="priceForm">
            <div class="mb-3">
              <label for="percentageInput" class="form-label">Enter percentage to add:</label>
              <input type="number" class="form-control" id="percentageInput" placeholder="e.g. 10" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="applyPriceIncreaseBtn" class="btn btn-primary">Apply</button>
        </div>
      </div>
    </div>
  </div>

  <footer class="text-center">
    <div class="container">
      <p class="mb-0">&copy; <?php echo date("Y"); ?></p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sorttable/2.1.0/sorttable.min.js"></script>
  <script>
    function downloadCSV(csv, filename) {
      let csvFile = new Blob([csv], {type: "text/csv"});
      let downloadLink = document.createElement("a");

      downloadLink.download = filename;
      downloadLink.href = window.URL.createObjectURL(csvFile);
      downloadLink.style.display = "none";

      document.body.appendChild(downloadLink);
      downloadLink.click();
    }

    function exportTableToCSV(filename) {
      let csv = [];
      let rows = document.querySelectorAll("#pricelistTable tr");
      
      rows.forEach(row => {
        let cols = row.querySelectorAll("td, th");
        let rowData = [];
        
        cols.forEach(col => {
          let text = col.innerText.replace(/"/g, '""');
          rowData.push('"' + text + '"');
        });
        
        csv.push(rowData.join(","));
      });
      
      downloadCSV(csv.join("\n"), filename);
    }

    document.getElementById("exportBtn").addEventListener("click", function () {
      exportTableToCSV("pricelist.csv");
    });

    function applyPriceIncrease(percentage) {
      document.querySelectorAll(".price, .renewal-price, .full-price, .full-renewal-price").forEach(function(cell) {
        let basePrice = parseFloat(cell.getAttribute("data-baseprice"));
        if (!isNaN(basePrice)) {
          let newPrice = basePrice * (1 + percentage / 100);
          cell.textContent = '€ ' + newPrice.toFixed(2);
        }
      });
    }

    document.getElementById("applyPriceIncreaseBtn").addEventListener("click", function () {
      let percentage = parseFloat(document.getElementById("percentageInput").value);
      if (!isNaN(percentage)) {
        applyPriceIncrease(percentage);
        let priceModal = bootstrap.Modal.getInstance(document.getElementById('priceModal'));
        priceModal.hide();
      }
    });
  </script>
</body>
</html>
