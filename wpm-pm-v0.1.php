
<!-- script for specification venster -->
<script>
    function showDiv(showId, hideId) {
    var showDiv3 = document.getElementById(showId);
    var hideDiv3 = document.getElementById(hideId);

    showDiv3.style.display = 'block';
    hideDiv3.style.display = 'none';
}

function filterFunction() {
    const input = document.getElementById("Pname");
    const filter = input.value.toUpperCase();
    const div = document.getElementById("venster");
    const a = div.getElementsByTagName("div");

    for (let i = 0; i < a.length; i++) {
        const txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
            a[i].style.display = "none";
            }
    }
}
</script>
<?php
// wordpress must have for plugin
add_action('admin_menu', 'my_plugin_menu');
// wordpress must have for plugin. puts the plugin in tab underneath settings
function my_plugin_menu()
{
  add_options_page('product main', 'product main', 'manage_options', 'my-unique-identifier', 'my_plugin_options');
}
// wordpress must have for plugin. the code that will be used to show the cs(clientside) of the plugin
function my_plugin_options()
{
if (!current_user_can('manage_options')){
wp_die(_('You do not have sufficient permissions to access this page.'));
}

// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "potato";
// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$successMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve common data sent from the frontend
    $type = $conn->real_escape_string($_POST['type']);

    // Check the type of form submitted and insert data into the respective table
    if ($type === 'sweetpotato') {
        $name = $conn->real_escape_string($_POST['name']);
        $typo = $conn->real_escape_string($_POST['typo']);
        // Insert data into the sweetpotato table
        $sql = "INSERT INTO sweetpotato (name, typo) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error in prepare statement: " . $conn->error);
        }

        $stmt->bind_param("ss", $name, $typo);

        if ($stmt->execute()) {
            $successMessage = "Data saved to the database.";
            exit;
        } else {
            $successMessage = "Error: " . $sql . "<br>" . $stmt->error;
        }
    } elseif ($type === 'fingerlingpotato') {
        $name2 = $conn->real_escape_string($_POST['name2']);
        $price = $conn->real_escape_string($_POST['price']);
        $sale = $conn->real_escape_string($_POST['sale']);
        $stock = $conn->real_escape_string($_POST['stock']);
        $weight = $conn->real_escape_string($_POST['weight']);
        $dimensions = $conn->real_escape_string($_POST['dimensions']);
        $publishDate = $conn->real_escape_string($_POST['publish_date']);
        $categories = $conn->real_escape_string($_POST['categories']);
        $discription = $conn->real_escape_string($_POST['discription']);

        // Insert data into the fingerlingpotato table
        $sql = "INSERT INTO fingerlingpotato (name2, price, sale, stock, weight, dimensions, publish_date, categories, discription) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error in prepare statement: " . $conn->error);
        }

        $stmt->bind_param("sssssssss", $name2, $price, $sale, $stock, $weight, $dimensions, $publishDate, $categories, $discription);

        if ($stmt->execute()) {
            $successMessage = "Data saved to the database.";
            exit;
        } else {
            $successMessage = "Error: " . $sql . "<br>" . $stmt->error;
        }
    }
}

// Retrieve data from fingerlingpotato
$sqlFingerlingPotato = "SELECT name2, price, sale, stock, weight, dimensions, publish_date, categories, discription FROM fingerlingpotato";
$resultFingerlingPotato = $conn->query($sqlFingerlingPotato);

$dataTextFingerlingPotato = '';

if ($resultFingerlingPotato !== false && $resultFingerlingPotato->num_rows > 0) {
    while ($row = $resultFingerlingPotato->fetch_assoc()) {
        if (isset($row["name2"]) && isset($row["price"]) && isset($row["sale"]) && isset($row["stock"]) && isset($row["weight"]) && isset($row["dimensions"]) && isset($row["publish_date"]) && isset($row["categories"]) && isset($row["discription"])) {
            $name2 = $row["name2"];
            $price = $row["price"];
            $sale = $row["sale"];
            $stock = $row["stock"];
            $weight = $row["weight"];
            $dimensions = $row["dimensions"];
            $publishDate = $row["publish_date"];
            $categories = $row["categories"];
            $discription = $row["discription"];

            // Generate dataTextFingerlingPotato
            $dataTextFingerlingPotato .= "<div class='product'><img src='" . plugins_url('icon.png', __FILE__) . "' alt='product icon' style='position:absolute; width: auto; height: 123.982px; left: 10px;'>
            <p class='text' style='position:absolute; left: 170px;'>" . $name2 . 
            "</p> <p class='text' style='position:absolute; left: 172px; top: 20px;'>&euro; " . $price . 
            "</p> <p class='text' style='position:absolute; font-size: 12px; left: 180px; top: 80px;'>Sale: &euro; " . $sale . 
            "</p> <p class='text' style='position:absolute; font-size: 12px; left:180px; top: 63px;'>Stock: " . $stock . 
            "</p> <p class='text' style='position:absolute; font-size: 12px; left: 80%; top: 15px;'>Weight: " . $weight . 
            " G</p> <p class='text' style='position:absolute; font-size: 12px; left: 80%; top: 30px;'>Dimensions: " . $dimensions . 
            " CM</p> <p class='text' style='position:absolute; font-size: 12px; left: 80%;'>Publishdate: " . $publishDate . 
            "</p> <p class='text' style='position:absolute; font-size: 12px; left: 177px; top: 45px;'>Categorie: " . $categories . 
            "</p> <p class='text' style='position:absolute; font-size: 12px; left: 45%; width:30%; height:115px; overflow:auto;'>Discription: <br>" . $discription . 
            "</p></div><br>";
        }
    }
} else {
    $dataTextFingerlingPotato = "No data found in the fingerlingpotato table.";
}
// retrieve data from sweetpotato
$dataText = '';
$dataText2 = '';

$sql = "SELECT name, typo FROM sweetpotato";
$result = $conn->query($sql);

if ($result !== false && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = $row["name"];
        $typo = $row["typo"];
        $label = ucfirst($name); // Capitalize the name for the label

        $sql = "INSERT INTO sweetpotato (name, typo) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $typo);
        // Generate dataText
        $dataText .= "<div class='data-dis'><p class='data-dis-text-n'>" . $name . "</p> <p class='data-dis-text-t'>" . $typo . "</p></div><br>";

        // Generate dataText2
        $dataText2 .= "<div class='data-dis-2'>";
        $dataText2 .= "<label>" . $label . ":</label>";
        if ($typo === 'text') {
            $dataText2 .= "<input type='text' class='in-name-fill' name='data_$name' placeholder='Enter $label'>";
        } elseif ($typo === 'number') {
            $dataText2 .= "<input type='number' class='in-name-fill' name='data_$name' placeholder='Enter $label'>";
        }
        $dataText2 .= "</div><br>";
    }
} else {
    $dataText = "No data found in the sweetpotato table.";
    $dataText2 = "No data found in the sweetpotato table.";
}

$successMessage = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve common data sent from the frontend
    $type = $conn->real_escape_string($_POST['type']);

    // Check the type of form submitted and insert data into the respective table
    if ($type === 'categories') {
        $names = $conn->real_escape_string($_POST['names']);
        $types = $conn->real_escape_string($_POST['types']);
        // Insert data into the categories table
        $sql = "INSERT INTO categories (names, types) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            die("Error in prepare statement: " . $conn->error);
        }

        $stmt->bind_param("ss", $names, $types);

        if ($stmt->execute()) {
            $successMessage = "Data saved to the database.";
            exit;
        } else {
            $successMessage = "Error: " . $sql . "<br>" . $stmt->error;
        }
}}
$dataText3 = '';
$dataText4 = ''; 

$sql = "SELECT names, types FROM categories";
$result = $conn->query($sql);

if ($result !== false && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $names = $row["names"];
        $types = $row["types"];
        $label = ucfirst($names); // Capitalize the name for the label

        $sql = "INSERT INTO categories (names, types) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $names, $types);
        // Generate dataText
        $dataText3 .= "<br><br><div style='position:relative padding-bottom 20px; padding-left:10px;'><option class='text' style='position:absolute; width:80%; overflow:hidden;'>" . $names . "  (" . $types . ")</option></div>";
        $dataText4 .= "<br><div style='position:relative padding-bottom 20px; padding-left:10px;'><option class='text' style='position:absolute; width:80%; overflow:hidden;'>" . $names . "</option></div>";
    }
} else {
    $dataText3 = "No categories found.";
    $dataText4 = "No categories found.";
}

echo '<html>';

echo '<head>';
echo '<style>';
include "main-screen.css";
echo '</style>';
echo '</head>';

//start of cs code
echo '<body>';
echo '<button onclick="document.getElementById(\'add-pro\').style.display=\'block\'" class="text main-button" style="position: absolute; top: 11px; left: 1%;">add</button>';
echo '<div style="position: absolute; top: 11px; right: 3%;">';
  //die('Code executed');
  echo '<img src="' . plugins_url('image2.png', __FILE__) . '" alt="search icon" class="search-icon" style="position: absolute; top: 50%; left: 10px;">';
  echo '<input class="search-bar" type="text" id="mySearch" onkeyup="filterFunction()" placeholder="Search..">';//function should let you look trough products.(doesnt exist yet)
echo '</div>';
echo '<div class="venster" style="top: 50px; left: 1%; position: absolute; overflow:auto;" id="venster">';
  echo '<div style="top:1% ;margin:0; position:relative;">';
      echo $dataTextFingerlingPotato;
  echo '</div>';
echo '</div>';
echo '<div class="sidebar" style="top: 50px; right: 3%; position: absolute; display:flex;">';
echo '<div class="text side-tag">Categories</div>';
    echo '<div>';
        echo $dataText3;
    echo '</div>';
echo '<button onclick="document.getElementById(\'add-categorie\').style.display=\'block\'" class="categorie" style="">+</button>';
echo '</div>';

//start of adding products code
echo '<div id="add-pro" class="add-product" style="top:200%; left:10%; position:absolute; display:none;">';
  echo '<div style="left: 977px; top: 7.19px; position: absolute;"><button onclick="document.getElementById(\'add-pro\').style.display=\'none\'" class="close">x</button></div>';
    echo '<form method="post" action="">';
            echo '<input type="hidden" name="type" value="fingerlingpotato">';
        echo '<label for="name" class="text" style="position: relative; left: 12px; top: 13px; font-size: 20px;">Name:</label>';
        echo '<input type="text" class="in" name="name2" id="name2" style="width: 499px; left: 10px; top: 10px;">';
      echo '<br>';
        echo '<label for="price" class="text" style="position: relative; left: 12px; top: 20px; font-size: 20px;">Price:</label>';
        echo '<input type="text" class="in" name="price" id="price" style="width: 120px; left: 18px; top: 15px;">';
        echo '<label for="sale" class="text" style="position: relative; left: 30px; top: 20px; font-size: 20px;">Sale:</label>';
        echo '<input type="text" class="in" name="sale" id="sale" style="width: 120px; left: 30px; top: 15px;">';
      echo '<br>';
        echo '<label for="stock" class="text" style="position: relative; left: 12px; top: 23px; font-size: 20px;">Stock:</label>';
        echo '<input type="number" class="in" name="stock" id="stock" style="width: 96px; left: 13px; top: 21px;">';
        echo '<label for="weight" class="text" style="position: relative; left: 20px; top: 23px; font-size: 20px;">Weight:</label>';
        echo '<input type="number" class="in" name="weight" id="wieght" style="width: 96px; left: 20px; top: 21px;">';
        echo '<label for="dimensions" class="text" style="position: relative; left: 20px; top: 23px; font-size: 20px;">Dimensions:</label>';
        echo '<input type="text" class="in" name="dimensions" id="dimensions" style="width: 189px; left: 20px; top: 21px;">';
      echo '<br>';
        echo '<label for="date" class="text" style="position: relative; left: 12px; top: 28px; font-size: 20px;">Publish Date:</label>';
        echo '<input type="date" class="in" name="publish_date" id="date" style="width: 235px; left: 13px; top: 27px;">';
      echo '<br>';

        echo '<label for="categories" class="text" style="position: relative; left: 12px; top: 35px; font-size: 20px;">Categorie:</label>';
        echo '<select class="dropbtn text" name="categories" style="position:relative; width: 235px; left: 13px; top: 34px;">';
            echo $dataText4;
        echo '</select>';
      echo '<br>';
        echo '<label for="discription" class="text" style="position: relative; left: 12px; top: 45px; font-size: 20px;">Discription:</label>';
        echo '<input type="text" class="in" name="discription" id="discription" style="width: 451px; left: 13px; top: 45px;">';
      echo '<br>';
      echo '<div class="submit-close"><input class="add text" type="submit" value="Add" style="left: 877px; top: 322px;"></div>';
      echo '</form>';
      echo '<div style="left: 570px; bottom: 145px; position: absolute;"><button class="btn-spec text" onclick="document.getElementById(\'main-spaci\').style.display=\'block\'">specification</button></div>';
echo '</div>';
//end of adding products code

//start of adding specifications code
echo '<div id="main-spaci" style="position:absolute; width:610px; top:100%; left:50%; display:none;">';
    echo '<button onclick="showDiv(\'list-fill\', \'spec-venster\')" class="button text" id="list-fill-button">Fill In</button>';
    echo '<button onclick="showDiv(\'spec-venster\', \'list-fill\')" class="button text" id="spec-venster-button">List & New</button>';
        echo '<div id="spec-venster" class="s-venster">';
            echo '<div id="list-new">';
            echo '<p class="text" style="position: absolute; top: -10px; left: 12px; font-size: 20px;">New:</p>';
            echo '<div style="left: 578px; top: 7.19px; width: 15.409px; height: 15.409px; position: absolute;"><button onclick="document.getElementById(\'main-spaci\').style.display=\'none\'" class="close text">x</button></div>';
            echo '<form method="post" action="">';
                echo '<input type="hidden" name="type" value="sweetpotato">';
                echo '<label for="name" class="text" style="top: 48px; position: relative; left: 12px;">Name:</label>';
                echo '<input type="text" class="in" style="top: 48px; left: 10px; width: 230px;" id="name" name="name" required>';
                echo '<label for="typo" class="text" style="top: 48px; position: relative; left: 12px;">Type:</label>';
                echo '<select class="in" style="width: 193px; left: 10px; top: 48px;" id="typo" name="typo" required>';
                    echo '<option value="number">number</option>';
                    echo '<option value="text">text</option>';
                echo '</select>';
                echo '<button type="submit" class="btn-add text" style="top: 66px; left: 12px; position: relative;">Add</button>';
            echo '</form>';
            echo '<hr class="stroke">';
            echo '<div class="text" style="position: relative; top: 60px; left: 5px; font-size: 20px;">';
                echo '<p>Name: </p>';
                echo '<p style="position: absolute; left: 246px; top: -20px;">Type: </p>';
            echo '</div>';
            echo '<div class="flow" id="data-display">';
                echo $dataText;
            echo '</div>';
        echo '</div>';
    echo '</div>';
    echo '<div class="s-venster" id="list-fill" style="display: none">';
        echo '<div class="close-pose-2"><button onclick="document.getElementById(\'main-spaci\').style.display=\'none\'" class="close">x</button></div>';
        echo '<div style="white-space: nowrap; overflow: auto; height: 547px; width: 608px; top:25px; padding:0px; position:relative;">';
            echo $dataText2;
        echo '</div>';
        echo '<button type="submit" class="btn-add" style="position: relative; right: -560px; top:30px;">save</button>';
    echo '</div>';
echo '</div>';
// end of adding specifications code

// beginning of adding a categorie code
echo '<div id="add-categorie" class="add-cat text" style="position:absolute; left:50%; top:100% !important; display:none;">';
echo '<form method="post" action="">';
        echo '<input type="hidden" name="type" value="categories">';
    echo '<label for="names" style="top: 12px;position: relative;left: 4px;">Name:</label>';
    echo '<input type="text" class="in-name-cat text" style="top: 12px;position: relative;left: 4px;" name="names" id="names">';
    echo '<label for="types" style="top: 12px;position: relative;left: 4px;">Type:</label>';
    echo '<select class="in-name-t-cat text" style="top: 12px;position: relative;left: 4px;"name="types" id="types">';
        echo '<option value="sub">sub</option>';
        echo '<option value="head">head</option>';
    echo '</select>';
    echo '<input class="btn-add text" style="top: 25px; left: 59px; position: relative;" type="submit" value="Add">';
echo '</form>';
    echo '<button onclick="document.getElementById(\'add-categorie\').style.display=\'none\'" class="close" style="position:relative; left:496px; top:-40px;">x</button>';
echo '</div>';
echo '</body>';
echo '</html>';
// end of adding a categorie code

// Close the database connection
$conn->close();
};

?>
