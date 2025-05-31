<?php
/*
UserSpice 4
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>

<?php
$idas = Input::get('idas');
$file = Input::get('file');
$fild = Input::get('fild');
$ville = Input::get('ville');
$Project = Input::get('Project');
$gh = Input::get('gh');
$idgh = Input::get('idgh');
// Include the database configuration file
//include 'dbConfig.php';
$statusMsg = '';

// File upload path
$targetDir = "uploads/";
$fileName1 = basename($_FILES["file"]["name"]);
$fileName = str_replace(' ', '_', $_FILES["file"]["name"]);
$targetFilePath = $targetDir .$fild ."_". $idas."_".$fileName ;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','docx','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){

            // Insert image file name into database
            //$insert = $db->query("INSERT into gh (oj) VALUES ('".$fileName."') WHERE id = 1");


            $insert = $db->update("ag", $idas, [$fild=>"$fileName"]);

            if($insert){
                $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
            }else{
                $statusMsg = "File upload failed, please try again.";
            }
        }else{
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    }else{
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
    }
}else{
    $statusMsg = 'Please select a file to upload.';
}

// Display status message
//print_r($Project);
echo $statusMsg;
Redirect::to("AG.php?ville=$ville&Project=$Project&gh=$gh&idgh=$idgh&msg=$statusMsg");

?>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
