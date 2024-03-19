<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student</title>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid #ccc;
        text-align: left;
        padding: 8px;
    }
    th {
        background-color: #f2f2f2;
    }
    .highest-average {
        background-color: #c1f0c1;
    }
    .lowest-average {
        background-color: #f0c1c1;
    }
</style>
</head>
<body>

<h2>Student Data</h2>

<table id="studentTable">
  <tr>
    <th>Student ID</th>
    <th>Student Name</th>
    <th>Birth Day</th>
    <th>Average Mark</th>
  </tr>
  <?php
  $path = "StudentData.txt";
  $myfile = fopen($path, "r") or die("Unable to open file!");

  $max_avg = -1;
  $min_avg = 101;
  $highlightedRows = [];

  while (!feof($myfile)) {
      $line = fgets($myfile);
      $data = explode("|", $line);

      // Highlight highest and lowest average rows
      $avg = floatval($data[3]);
      if ($avg > $max_avg) {
          $max_avg = $avg;
          $highlightedRows['highest'] = $data[0]; // Lưu ID của sinh viên có điểm cao nhất
      }
      if ($avg < $min_avg) {
          $min_avg = $avg;
          $highlightedRows['lowest'] = $data[0]; // Lưu ID của sinh viên có điểm thấp nhất
      }

      echo "<tr>";
      foreach ($data as $value) {
          echo "<td>$value</td>";
      }
      echo "</tr>";
  }
  fclose($myfile);
  ?>
</table>

<h2>Search Results</h2>

<table id="searchResults">
  <tr>
    <th>Student ID</th>
    <th>Student Name</th>
    <th>Birth Day</th>
    <th>Average Mark</th>
  </tr>
  <?php
  	//search id or name
	$searchId = $_GET['Id'] ?? null;
	$searchName = $_GET['Name'] ?? null;
	$searchItemsId = $searchId ? explode(",", $searchId) : []; // Tách chuỗi search thành mảng
	$searchItemsName = $searchName ? explode(",", $searchName) : []; // Tách chuỗi search thành mảng

	$searchItems = array_merge($searchItemsId, $searchItemsName);

	if (!empty($searchItems)) {
    	echo "<table border='1'>";
    	foreach ($searchItems as $item) {
        
        	$myfile = fopen("StudentData.txt", "r") or die("Unable to open file!");
        	$found = false;
        	while (!feof($myfile)) {
            	$line = fgets($myfile);
            	$data = explode("|", $line);
            	if ($data[0] == $item || $data[1] == $item) {
                	$found = true;
                	echo "<tr>
                			<td>$data[0]</td>
                			<td>$data[1]</td>
                			<td>$data[2]</td>
                			<td>$data[3]</td>
                		</tr>";
                	break;
            	}
        	}
        	fclose($myfile);
        	if (!$found) {
            	echo "<tr><td colspan='4'>Not Found for: $item</td></tr>";
        	}
        
    	}
    	echo "</table>";
	}
  ?>
</table>

<script>
  const tableRows = document.querySelectorAll("#studentTable tr");
  tableRows.forEach(row => {
      const averageMark = parseFloat(row.cells[3].innerText);
      if (averageMark === <?= $max_avg ?>) {
          row.classList.add('highest-average');
      } else if (averageMark === <?= $min_avg ?>) {
          row.classList.add('lowest-average');
      }
  });
</script>

</body>
</html>
