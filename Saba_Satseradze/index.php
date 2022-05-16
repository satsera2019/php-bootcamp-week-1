<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>challenge 1</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>
<body>
    <?php 
        $errors = array("first_name" => "", "last_name"  => "", "profile_picture"  => ""); 
        $target_dir = "uploads/"; 
        $uploadOk = 1;
    ?>

    <?php 
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // first_name validation 
            if ($_POST["first_name"] === '') { 
                $errors['first_name'] = 'Enter First Name';
            } else {
                if (ctype_alpha(str_replace(' ', '', $_POST["first_name"])) === false) {
                    $errors['first_name'] = 'First Name must contain letters and spaces only';
                }
            }
            // last_name validation
            if ($_POST["last_name"] === '') {
                $errors['last_name'] = 'Enter last Name';
            } else {
                if (ctype_alpha(str_replace(' ', '', $_POST["last_name"])) === false) {
                    $errors['last_name'] = 'Last Name must contain letters and spaces only';
                }
            }
    
            
            
            // Check if image file is a actual image or fake image
            if ($_FILES['profile_picture']['size'] == 0)
            {
                $errors['profile_picture'] = 'Upload a profile photo';
            } else {
                $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
                $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                if($check !== false) {
                    //$errors['profile_picture'] = 'File is an image' . $check["mime"];
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                    $errors['profile_picture'] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed.';
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $errors['profile_picture'] = 'Sorry, your file was not uploaded.';
                    // return;
                } else {
                    $temp = explode(".", $_FILES["profile_picture"]["name"]);
                    $newfilename = round(microtime(true)) . '.' . end($temp);
                    // print_r($newfilename);
                    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                        $errors['profile_picture'] = 'File uploaded successfully';
                    } else {
                        $errors['profile_picture'] = 'Sorry, there was an error uploading your file.';
                    }
                }
            }
        }
    ?>

    <?php

        if(isset($_GET["user_name"]) && $_GET["user_name"] !== ""){
            if($_GET["type_id"] == 1){ // repos
                $type = "repos";
            } else if($_GET["type_id"] == 2){ // followers
                $type = "followers";
            } else {
                $type = "repos";
            }
            $opts = array(
                'http'=>array(
                'method'=>"GET",
                'header'=>'user-agent: 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'
                )
            );
            $context = stream_context_create($opts);
            $repos = file_get_contents('https://api.github.com/users/'.$_GET["user_name"]. '/' . $type, false, $context);
            $result = json_decode($repos, true);
            // var_dump($result);
        } 
    ?>

    <div class="container">
        <div class="row">
            <div class="col-6">
                <h3>Challenge #1</h3>
                <form class="" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name">  
                        <div class="text-danger"> <?php if(isset($errors['first_name'])) { echo $errors['first_name'];} else { echo "";} ?> </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last Name">
                        <div class="text-danger"> <?php if(isset($errors['last_name'])) { echo $errors['last_name'];} else { echo "";} ?> </div>
                    </div>
                    <div class="form-group">
                        <label for="profile_picture">Profile Picture</label>
                        <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
                        <div class="text-danger"> <?php if(isset($errors['profile_picture'])) { echo $errors['profile_picture'];} else { echo "";} ?> </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

                <div class="submited_form">
                    <?php if(!empty($_POST)){ echo "<h3>submited form</h1>"; } ?>

                    <?php if(isset($_POST['first_name'])) { echo "First Name: " . $_POST['first_name'] . "<br/>";} else { echo " <br/>";} 
                    if(isset($_POST['last_name'])) { echo "last Name: " . $_POST['last_name'];} else { echo " <br/>";} ?>
                    
                    <?php if($errors['profile_picture'] === "File uploaded successfully")
                    { echo "<div class='row'><img width='100' src='$target_file'/> </div>";} else { echo "";} ?>

                </div>

            </div>

            <hr/>

            <div class="col-6">
                <h3>Challenge #2</h3>
                <form class="" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="get">
                    <div class="form-group">
                        <label for="user_name">User Name</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter User Name">  
                        <div class="text-danger"> <?php if(isset($errors['user_name'])) { echo $errors['user_name'];} else { echo "";} ?> </div>
                    </div>
                    <div class="form-group">
                        <select class="form-select" aria-label="Default select example" name="type_id">
                            <option selected>select type</option>
                            <option value="1">Repositories</option>
                            <option value="2">followers</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

            <div class="submited">
                
                <?php
                    if(isset($result)){ 
                        if($_GET["type_id"] == 2){ // followers
                            foreach ($result as $key => $value) {
                                echo "<li class='list-group-item'> follower name: "."" . $value['login'] ."
                                <img width='100' src='".$value["avatar_url"]."'>
                                </li>";
                            } 
                        } else {
                            echo '<ul class="list-group">';
                                foreach ($result as $key => $value) {
                                    echo "<li class='list-group-item'> Repositories name: ".
                                            "<a href='".$value['html_url']."'>" . $value['name'] ."</a> 
                                    </li>";
                                }
                            echo '</ul>';
                        }
                    }
                ?>
            </div>

        </div>
    </div>

    





    


</body>

<script src="js/script.js"></script>

</html>
