<?php

if(isset($_POST['submit-post'])){
    session_start();
    include_once './dbh.inc.php';
    include_once './functions.inc.php';

    $id = $_SESSION['userId'];
    $inputFilename = $_POST['filename'];

    //input validation
    if(empty($inputFilename)){//if the file name is empty...
        $inputFilename = "gallery";
    } else {
        //if not, replace all spaces with something else
        $inputFilename = strtolower(str_replace(" ", "-", $inputFilename));
    }
    $inputFiletitle = $_POST['filetitle'];
    $inputFiledesc = $_POST['filedesc'];

    $inputFile = $_FILES['file'];
    $fileName = $inputFile["name"];
    $fileType = $inputFile["type"];
    $fileTempName = $inputFile["tmp_name"];
    $fileError = $inputFile["error"];
    $fileSize = $inputFile["size"]; //size is in KB

    if($fileError === 0){
        $fileNameInfo = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameInfo));
        $allowedExtensions = array("jpg", "jpeg", "png");
    
        if(in_array($fileExtension, $allowedExtensions)){
            if($fileSize <= 20971520){ //if less than 20MB
                //all file validation checks are done! add post data to database, add file to filesystem.
                $fullFileName = $inputFilename.".".uniqid("", true).".".$fileExtension;
                $fileDestination = "../image/gallery/".$fullFileName;
                  //always try to save data to a database before saving additional data to a server.

                //now that file validations are done, do form text input validation
                if(empty($inputFiletitle) || empty($inputFiledesc)){
                    header("Location: ../discover.php?error=emptyInput&filename=".$inputFilename."&filetitle=".$inputFiletitle."&filedesc=".$inputFiledesc);
                    exit();
                } else {
                    $sql = "select * from galleryitem;"; //we need to check data using all rows.
                    //we're using user input, so we need to use a prepared statement.
                    $stmt = mysqli_stmt_init($conn);
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("Location: ../discover.php?error=stmtError&filename=".$inputFilename."&filetitle=".$inputFiletitle."&filedesc=".$inputFiledesc);
                        exit();
                    }else {
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $rowCount = mysqli_num_rows($result);
                        $setImageOrder = $rowCount + 1;

                        $sql = "insert into galleryitem (title, description, imageFullName, orderNumber, userId) values (?, ?, ?, ?, ? );";
                        //you can use an initialized statement with a different SQL input
                        if(!mysqli_stmt_prepare($stmt, $sql)) {
                            header("Location: ../discover.php?error=secondStmtError&filename=".$inputFilename."&filetitle=".$inputFiletitle."&filedesc=".$inputFiledesc);
                            exit();
                        }else {
                            mysqli_stmt_bind_param($stmt, "sssss", $inputFiletitle, $inputFiledesc, $fullFileName, $setImageOrder, $id);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close();

                            //now we can upload the file: the database was updated
                            move_uploaded_file($fileTempName, $fileDestination);

                            header("Location: ../discover.php?success");
                            exit();
                        }
                    }
                }
            } else {
                header("Location: ../discover.php?error=exceedsFileSize&filename=".$inputFilename."&filetitle=".$inputFiletitle."&filedesc=".$inputFiledesc);
                exit();
            }
        } else {
            header("Location: ../discover.php?error=improperExtension&filename=".$inputFilename."&filetitle=".$inputFiletitle."&filedesc=".$inputFiledesc);
        }
    } else {
        header("Location: ../discover.php?error=errorUploading&filename=".$inputFilename."&filetitle=".$inputFiletitle."&filedesc=".$inputFiledesc);
        exit();
    }
    
}