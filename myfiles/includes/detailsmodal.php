<?php
require_once '../core/init.php';
$id = $_POST['id'];
$id=(int)$id;
$sql = "SELECT * FROM products WHERE id = '$id'";
$result = $db->query($sql);
$product = mysqli_fetch_assoc($result);

 ?>


<?php ob_start(); ?>


<div class="modal fade details-1" id="details-modal" tabindx="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
  <div class="model-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
        <button class="close"  type="button" onclick="closeModal()" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
          <h4 class"modal-title text-center"><?= $product['title']; ?></h4>
  </div>

<div class="modal-body">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6"></div>
          <div class="center-block">
              <img src="<?= $product['image']; ?>" alt=<?= $product['title']; ?> class="details img-responsive">
            </div>
          </div>
        <div class="col-sm-6>"></div>
          <h4>Χαρακτηριστικά</h4><br>

        <p>  <?= $product['description']; ?></p>
        <p>Τιμή :$<?= $product['price']; ?></p>

        <form action="add_cart.php" method="post">
          <div class="form-group"></div>

        </form>

        </div>
    </div>
  </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" onclick="closeModal()">Εξοδος</button>
    <button class="btn btn-warning" type="submit"><s class="glyphicon glyphicon-shopping-cart"></s>Προσθήκη στο καλάθι</button>
    </div>
  </div>
  </div>
</div>


<script>
function closeModal(){
  jQuery('#details-modal').modal('hide');
  setTimeout(function(){
    jQuery('#details-modal').remove();

  },500);
}


</script>

<?php echo ob_get_clean(); ?>
