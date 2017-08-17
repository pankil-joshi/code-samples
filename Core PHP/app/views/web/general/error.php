<?php include_once $this->getPart('/web/general/common/header.php'); 
$errorMessages = array(
    '403' => array(
        'title' => 'Access Denied', 
        'description' => $exception->getMessage()
        ), 
    '500' => array(
        'title' => 'Internal Server error',
        'description' => 'An internal server error occured while processing your request. Error has been notified to the administrater. Please try again after some time.'
        ))
?>
<style>
.wrapper {
  height: 100% !important; 
}
.vertical_align_table {
  height: 78% !important;
}
</style>
    <div class="vertical_align_table">
        <div class="vertical_align_table_inner">
            <div class="error_page">
                <h1><?= $exception->getCode(); ?></h1>
                <h6><?= $errorMessages[$exception->getCode()]['title']; ?></h6>
                <p><?= $errorMessages[$exception->getCode()]['description']; ?></p>
            </div>
        </div>
    </div>

<?php include_once $this->getPart('/web/general/common/footer.php'); ?>