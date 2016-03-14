<?php
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});

if (isset($_POST['upload'])) {
    include 'classes/ProcessFile.php';
    $uploadInfo = ProcessFile::uploadExcel();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Stock uploader</title>
    <link rel="stylesheet" href="external/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="external/datatables/datatables/media/css/jquery.dataTables.min.css">
</head>
<body>

<div class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Stock overview</a>
        </div>
    </div>
</div>

<div class="container">

    <div class="row">
        <div class="col-lg-12">
            <form class="well" action="index.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file">Select a file to upload</label>
                    <input type="file" name="file">
                    <p class="help-block">Please upload a valid excel file.</p>
                </div>
                <input type="submit" class="btn btn-lg btn-primary" name="upload" value="Upload">
            </form>
        </div>
    </div>

<?php if (isset($uploadInfo)): ?>
    <div class="alert alert-<?= $uploadInfo === true ? 'success' : 'danger' ?>" role="alert">
        <?php if ($uploadInfo === true): ?>
            <strong>Success!</strong> The file has been uploaded and the data successfully imported into the database.
        <?php else: ?>
            <strong>Error!</strong> <?= $uploadInfo ?>
        <?php endif ?>
    </div>
<?php endif ?>


    <table id="stockTable" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Stock ID</th>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Type</th>
            <th>Created</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>Stock ID</th>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Type</th>
            <th>Created</th>
        </tr>
        </tfoot>
    </table>

</div>

<script type="text/javascript" language="javascript" src="external/components/jquery/jquery.min.js"></script>
<script type="text/javascript" language="javascript"  src="external/datatables/datatables/media/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#stockTable').DataTable( {
            "ajax": '/view/StockList.php',
            "iDisplayLength": 50
        } );
    } );
</script>
</body>
</html>
