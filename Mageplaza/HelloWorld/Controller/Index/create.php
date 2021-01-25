<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$titulo = $autor = $editorial = "" $publicacion = "";
$titulo_err = $autor_err = $editorial_err = "" $publicacion_err = "";;
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate titulo
    $input_titulo = trim($_POST["titulo"]);
    if(empty($input_titulo)){
        $titulo_err = "Please enter a titulo.";
    } elseif(!filter_var($input_titulo, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $titulo_err = "Please enter a valid titulo.";
    } else{
        $titulo = $input_titulo;
    }
    
    // Validate autor
    $input_autor = trim($_POST["autor"]);
    if(empty($input_autor)){
        $autor_err = "Please enter an autor.";     
    } else{
        $autor = $input_autor;
    }
    
    // Validate editorial
    $input_editorial = trim($_POST["editorial"]);
    if(empty($input_editorial)){
        $editorial_err = "Please enter the editorial.";     
    } elseif(!ctype_digit($input_editorial)){
        $editorial_err = "Please enter a positive integer value.";
    } else{
        $editorial = $input_editorial;
    }

    // Validate publicacion
    $input_editorial = trim($_POST["editorial"]);
    if(empty($input_editorial)){
        $editorial_err = "Please enter the editorial.";     
    } elseif(!ctype_digit($input_editorial)){
        $editorial_err = "Please enter a positive integer value.";
    } else{
        $editorial = $input_editorial;
    }
    
    // Check input errors before inserting in database
    if(empty($titulo_err) && empty($autor_err) && empty($editorial_err) && empty($publicacion_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO biblio (titulo, autor, editorial, publicacion) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_titulo, $param_autor, $param_editorial, $param_publicacion);
            
            // Set parameters
            $param_titulo = $titulo;
            $param_autor = $autor;
            $param_editorial = $editorial;
            $param_publicacion = $publicacion;
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
