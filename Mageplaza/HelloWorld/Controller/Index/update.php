<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$titulo = $autor = $editorial = "";
$titulo_err = $autor_err = $editorial_err = "" $publicacion_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate titulo
    $input_titulo = trim($_POST["titulo"]);
    if(empty($input_titulo)){
        $titulo_err = "Please enter a titulo.";
    } elseif(!filter_var($input_titulo, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $titulo_err = "Please enter a valid titulo.";
    } else{
        $titulo = $input_titulo;
    }
    
    // Validate autor autor
    $input_autor = trim($_POST["autor"]);
    if(empty($input_autor)){
        $autor_err = "Please enter an autor.";     
    } else{
        $autor = $input_autor;
    }
    
    // Validate editorial
    $input_editorial = trim($_POST["editorial"]);
    if(empty($input_editorial)){
        $editorial_err = "Please enter the editorial amount.";     
    } elseif(!ctype_digit($input_editorial)){
        $editorial_err = "Please enter a positive integer value.";
    } else{
        $editorial = $input_editorial;
    }
     // Validate publicacion
    $input_publicacion = trim($_POST["publicacion"]);
    if(empty($input_publicacion)){
        $publicacion_err = "Please enter the publicacion amount.";     
    } elseif(!ctype_digit($input_publicacion)){
        $publicacion_err = "Please enter a positive integer value.";
    } else{
        $publicacion = $input_publicacion;
    }
    
    // Check input errors before inserting in database
    if(empty($titulo_err) && empty($autor_err) && empty($editorial_err) && empty($publicacion_err)){
        // Prepare an update statement
        $sql = "UPDATE biblio SET titulo=?, autor=?, editorial=?, publicacion=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_titulo, $param_autor, $param_editorial, $param_publicacion, $param_id);
            
            // Set parameters
            $param_titulo = $titulo;
            $param_autor = $autor;
            $param_editorial = $editorial;
            $param_publicacion = $publicacion;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM biblio WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $titulo = $row["titulo"];
                    $autor = $row["autor"];
                    $editorial = $row["editorial"];
                    $publicacion = $row["publicacion"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basetitulo($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($titulo_err)) ? 'has-error' : ''; ?>">
                            <label>titulo</label>
                            <input type="text" titulo="titulo" class="form-control" value="<?php echo $titulo; ?>">
                            <span class="help-block"><?php echo $titulo_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($autor_err)) ? 'has-error' : ''; ?>">
                            <label>autor</label>
                            <textarea titulo="autor" class="form-control"><?php echo $autor; ?></textarea>
                            <span class="help-block"><?php echo $autor_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($editorial_err)) ? 'has-error' : ''; ?>">
                            <label>editorial</label>
                            <input type="text" titulo="editorial" class="form-control" value="<?php echo $editorial; ?>">
                            <span class="help-block"><?php echo $editorial_err;?></span>
                        </div>
                            <div class="form-group <?php echo (!empty($publicacion_err)) ? 'has-error' : ''; ?>">
                            <label>publicacion</label>
                            <input type="text" titulo="publicacion" class="form-control" value="<?php echo $publicacion; ?>">
                            <span class="help-block"><?php echo $publicacion_err;?></span>
                        </div>
                        <input type="hidden" titulo="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
