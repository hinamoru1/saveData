<?php 
//include the formulare 
include_once 'index.html';

//declaring information to connect to database
//this is the default value for hot file it is reset if the file cant be put on the server (meaning it is already on it)
$topValue=1;

$user = "root"; 
$password = ""; 
$host = "localhost"; 
$dbase = "saveData"; 
$tableB = "bigFile";
$tableS = "smallFile";

//getting the current time to insert in database
date_default_timezone_set('Asia/Seoul');
$currentTime = date('Y-m-d h:i:s');
echo "time of last run : ".$currentTime;

$link = mysqli_connect($host, $user, $password, $dbase);

	
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

//putting the result into retractable html div
?>
<div class="panel-group">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#collapse1">click to toogle information about the update</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse show">
      <div class="panel-body"><?php
	  
$total = count($_FILES['fileToUpload']['name']);
echo $total." given files"?><br><?php
$target_dir = "uploads/";

// Loop through each file
for($i=0; $i<$total; $i++) {
	//clock start
	$milliseconds = round(microtime(true) * 1000);
	
	// retrieve information about the file
	$isSmallFile = 1;
	$description= $_POST['description_entered'];
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"][$i]);
	$name= basename( $_FILES["fileToUpload"]["name"][$i]);
	echo " --- Working on : ".$name;?><br><?php
	$uploadOk = 1;
	$updateDatabaseOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	
	//if umpty description
	if (empty($_POST['description_entered'])){
		$uploadOk = 0;
		?><b style="color:orange;"> info </b><?php
		echo "umpty description form";?><br><?php
		break;
	}
	
	//if no file is given
	if ($name == ""){
		$uploadOk = 0;
		?><b style="color:orange;"> info </b><?php
		echo "no given file";?><br><?php
		break;
	}

	// Check file size if <0.5MB then we consider it is a small file
	if ($_FILES["fileToUpload"]["size"][$i] < 0.5*1024*1024) {
		$isSmallFile = 0;
	}
	
	// set $table value for the query
	$table = $tableB;
	if ($isSmallFile == 0){
		$table = $tableS;
	}

	// Check file size if >2MB not going to upload
	if ($_FILES["fileToUpload"]["size"][$i] > 10*1024*1024) {
		?><b style="color:red;"> Warning </b><?php
		echo "your file is too large.";?><br><?php
		$uploadOk = 0;
	}
	
	// Check if file already exists
	if (file_exists($target_file)) {
		?><b style="color:red;"> Warning </b><?php
		echo "file already exists.";?><br><?php
		$uploadOk = 0;
		$updateDatabaseOk  = 0;

		// if file is already on the server that mean we have to increment its hot value
		//retrive the data

		$query= "SELECT filename, isHot, ID FROM $table ORDER BY ID asc";

		if ($result = mysqli_query($link, $query)) {

			$metBeffor= False;

			/* get an associatif table*/
			while ($row = mysqli_fetch_row($result)) {

				//encrement the compteur
				$files_field= $row['0'];
				$isHot= $row['1'];
				$ID= $row['2'];
				
				if ($files_field == $name){
					//if matching increment it
					if($metBeffor == False){
						++$isHot;
						$topValue = $isHot;
						$metBeffor = True;
					}
					
					// --- update new value in database ---
					$sql = "UPDATE $table set isHot='$topValue',lastUpdatetime='$currentTime',description='$description' WHERE ID='$ID'";
					if(mysqli_query($link, $sql)){
					} else{
						echo "ERROR: Could not execute $sql. " . mysqli_error($link);?><br><?php
					}
					break;
				}
			}
			/* free result */
			mysqli_free_result($result);
		}
		
		
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		?><b style="color:red;"> Warning </b><?php
		echo "your file was not uploaded.";?><br><?php
	// if everything is ok, try to upload file
	} else {
		$milliseconds0 = round(microtime(true) * 1000);
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file)) {
			?><b style="color:green;"> success </b><?php
			echo "The file ". basename( $_FILES["fileToUpload"]["name"][$i]). " has been uploaded.";?><br><?php
			$milliseconds1 = round(microtime(true) * 1000);
			echo " [INFO] needed time to proceed this file : ".($milliseconds1-$milliseconds)." ms";?><br><?php
			echo " [INFO] needed time to actually write it on the disk : ".($milliseconds1-$milliseconds0)." ms";?><br><?php
		} else {
			?><b style="color:red;"> ERROR </b><?php
			echo "Sorry, there was an unknown error uploading your file.";?><br><?php
		}
	}
	
	// --- inserting information in database ---
	if ($updateDatabaseOk == 0){
		echo "increment hotfile value";
	}else{
		$sql = "INSERT INTO $table (description, filename,  isHot, creationTime, lastUpdatetime) VALUES ('$description', '$name', '$topValue', '$currentTime', '$currentTime')";
		//	$sql = "INSERT INTO $table (description, filename) VALUES ('$description', '$name')";
		if(mysqli_query($link, $sql)){
		?><b style="color:green;"> success </b><?php
		echo "Records inserted successfully in database.";?><br><?php
		} else{
		echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);?><br><?php
		}
	}
}
mysqli_close($link);


//end of the retractable part for the details about the upload
//start of the retractable part for the list of all files on the server
?></div>
    </div>
  </div>
</div>

	<div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#collapse2">click to toogle the list of all files on the server</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse show">
      <div class="panel-body">
	  <?php

?>
<?php	
	// --- retriving information from database --- 
	
	$user = "root"; 
	$password = ""; 
	$host = "localhost"; 
	$dbase = "saveData"; 
	$tableB = "bigFile";
	$tableS = "smallFile";
	// Connection to DBase 
	$link = mysqli_connect($host, $user, $password, $dbase);
	
	if($link === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
	

	$queryB= "SELECT description, filename, isHot, creationTime, lastUpdateTime FROM $tableB ORDER BY ID desc";
	$queryS= "SELECT description, filename, isHot, creationTime, lastUpdateTime FROM $tableS ORDER BY ID desc";
//	$query= "SELECT description, filename FROM $table ORDER BY ID desc";

	if ($resultB = mysqli_query($link, $queryB) and $resultS = mysqli_query($link, $queryS)) {
		$resultNumber=0;
		$smallFilesNumber=0;
		$hotFilesNumber=0;
		?>
		<div class="container table-responsive">
			<table class="table table-striped">
				<thead>
					  <tr>
							<th>description</th>
							<th>file name</th>
							<th>size</th>
							<th>type</th>
							<th>creation date</th>
							<th>last update</th>
					  </tr>
				</thead>
			<tbody>
		<?php
		/* get an associatif table*/
		while ($row = mysqli_fetch_row($resultB)) {
			//encrement the compteur
			++$resultNumber;
			
			$files_field= $row['1'];
			$files_show= "Uploads/$files_field";
			$descriptionvalue= $row['0'];
			$isHot= $row['2'];
			$isHotString= "cold";
			if($isHot > 4){
				$isHotString= "HOT";
				++$hotFilesNumber;
			}
			$creationTime = $row['3'];
			$lastUpdateTime = $row['4'];

			print "<tr>\n"; 
			
			print "\t<td>\n"; 
			echo "$descriptionvalue";
			print "</td>\n";
			print "\t<td>\n"; 
			echo "<a href='$files_show'>$files_field</a>";
			print "</td>\n";
			print "\t<td>\n"; 
			echo "BIG";
			print "</td>\n";
			print "\t<td>\n"; 
			echo "$isHotString";
			print "</td>\n";
			print "\t<td>\n"; 
			echo "$creationTime";
			print "</td>\n";
			print "\t<td>\n";
			//if creation time and lastUpdateTime are the same dont display last update time
			if($creationTime == $lastUpdateTime){
				$lastUpdateTime = "";
			}
			echo "$lastUpdateTime";
			print "</td>\n";
			
			print "</tr>\n";			

		}
		while ($row = mysqli_fetch_row($resultS)) {
			//encrement the compteur
			++$resultNumber;
			++$smallFilesNumber;
			
			$files_field= $row['1'];
			$files_show= "Uploads/$files_field";
			$descriptionvalue= $row['0'];
			$isHot= $row['2'];
			$isHotString= "cold";
			if($isHot > 4){
				$isHotString= "HOT";
				++$hotFilesNumber;
			}
			$creationTime = $row['3'];
			$lastUpdateTime = $row['4'];

			print "<tr>\n"; 
			
			print "\t<td>\n"; 
			echo "$descriptionvalue";
			print "</td>\n";
			print "\t<td>\n"; 
			echo "<a href='$files_show'>$files_field</a>";
			print "</td>\n";
			print "\t<td>\n"; 
			echo "small";
			print "</td>\n";
			print "\t<td>\n"; 
			echo "$isHotString";
			print "</td>\n";
			print "\t<td>\n"; 
			echo "$creationTime";
			print "</td>\n";
			print "\t<td>\n";
			//if creation time and lastUpdateTime are the same dont display last update time
			if($creationTime == $lastUpdateTime){
				$lastUpdateTime = "";
			}
			echo "$lastUpdateTime";
			print "</td>\n";
			
			print "</tr>\n";			

		}
		
		?>
				</tbody>
			</table>
		</div>
		
		<!-- end of the toogle part-->
		</div>
    </div>
  </div>

	<?php 
	// displaying a global information about the results
	?>	
		<div class="container table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>number of result</td>
						<td><?php echo $resultNumber ?></td>
					</tr>
					<tr>
						<td>number of small files</td>
						<td><?php echo $smallFilesNumber ?></td>
					</tr>
					<tr>
						<td>number of big files</td>
						<td><?php echo $resultNumber-$smallFilesNumber ?></td>
					</tr>
					<tr>
						<td>number of hot files</td>
						<td><?php echo $hotFilesNumber ?></td>
					</tr>
					<tr>
						<td>number of cold files</td>
						<td><?php echo $resultNumber-$hotFilesNumber ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		</div>
		<?php
		/* free result */
		mysqli_free_result($resultS);
		mysqli_free_result($resultB);
	}

	/* close connection */
	mysqli_close($link);
	
?>