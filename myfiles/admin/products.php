
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/myfiles/core/init.php';

include 'includes/head.php';
include 'includes/navigation.php';

//Delete Product
if(isset($_GET['delete'])){
  $id = sanitize($_GET['delete']);
  $db->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
  header('Location: products.php');
}

$dbpath = '';
if (isset($_GET['add']) || isset($_GET['edit'])){
$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
$saved_image = '';

if(isset($_GET['edit'])){
  $edit_id = (int)$_GET['edit'];
  $productResults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
  $product = mysqli_fetch_assoc($productResults);
  if(isset($_GET['delete_image'])){
    $imgi = (int)$_GET['imgi'] - 1;
    $images = explode(',',$product['image']);
    $image_url = $_SERVER['DOCUMENT_ROOT'].$images[$imgi];
    unlink($image_url);
    unset($images[$imgi]);
    $imageString = implode(',',$images);
    $db->query("UPDATE products SET image = '{$imageString}' WHERE id = '$edit_id'");
    header('Location: products.php?edit='.$edit_id);
  }
  $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$product['title']);
  $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$product['price']);
  $description = ((isset($_POST['description']))?sanitize($_POST['description']):$product['description']);
  $saved_image = (($product['image'] != '')?$product['image']:'');
  $dbpath = $saved_image;
}
#UPLOAD PHOTO
if ($_POST) {
  $errors= array();
  $required = array('title', 'price');
  $allowed = array('png','jpg','jpeg','gif');
  $photoName = array();
  $tmpLoc = array();
  $uploadPath = array();
  $uploadName = array();
  foreach($required as $field){
    if($_POST[$field] == ''){
      $errors[] = 'All Fields With and Astrisk are required.';
      break;
    }
  }
  $photoCount = count($_FILES['photo']['name']);
   if ($photoCount > 0) {
     for($i=0;$i<$photoCount;$i++){
      $name = $_FILES['photo']['name'][$i];
      $nameArray = explode('.',$name);
      $fileName = $nameArray[0];
      $fileExt = $nameArray[1];
      $mime = explode('/',$_FILES['photo']['type'][$i]);
      $mimeType = $mime[0];
      $mimeExt = $mime[1];
      $tmpLoc[] = $_FILES['photo']['tmp_name'][$i];
      $fileSize = $_FILES['photo']['size'][$i];
      $uploadName = md5(microtime()).'.'.$fileExt;
      $uploadPath[] = BASEURL.'Images/'.$uploadName;
      if($i != 0 && $i < $photoCount){
        $dbpath .= ',';
      }
      $dbpath .= '/myfiles/images/'.$uploadName;
      if ($mimeType != 'image') {
        $errors[] = 'The file must be an image.';
      }
      if (!in_array($fileExt, $allowed)) {
        $errors[] = 'The file extension must be a png, jpg, jpeg, or gif.';
      }
      if ($fileSize > 15000000) {
        $errors[] = 'The files size must be under 15MB.';
      }
      if ($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')) {
        $errors[] = 'File extension does not match the file.';
      }
    }
   }
  if(!empty($errors)){
    echo display_errors($errors);
  }else{
    if($photoCount > 0){
    //upload file and insert into database
      for($i=0;$i<$photoCount;$i++){
        move_uploaded_file($tmpLoc[$i],$uploadPath[$i]);
      }
    }
    $insertSql = "INSERT INTO products (`title`,`price`,`image`,`description`)
     VALUES ('$title','$price', '$dbpath','$description')";
     if(isset($_GET['edit'])){
       $insertSql = "UPDATE products SET `title` = '$title', `price` = '$price',  `image` = '$dbpath', `description` = '$description'
       WHERE id ='$edit_id'";
     }

     $db->query($insertSql);
     header('Location: products.php');
  }
}

?>




  <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A New');?> Product</h2><hr>
  <form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="POST" enctype="multipart/form-data">
    <div class="form-group col-md-3">
      <label for="title">Title*:</label>
      <input type="text" name="title" class="form-control" id="title" value="<?=$title;?>">
    </div>



    <div class="form-group col-md-3">
      <label for="price">Price*:</label>
      <input type="text" id="price" name="price" class="form-control" value="<?=$price;?>">
    </div>



    <div class="form-group col-md-6">
      <?php if($saved_image != ''): ?>
        <?php
        $imgi = 1;
        $images = explode(',',$saved_image); ?>
          <?php foreach($images as $image):?>
            <div class="saved-image col-md-4">
              <img src="<?=$image;?>" alt="saved image"/><br>
              <a href="products.php?delete_image=1&edit=<?=$edit_id;?>&imgi=<?=$imgi;?>" class="text-danger">Delete Image</a>
            </div>
          <?php $imgi++; endforeach;?>
      <?php else: ?>
        <label for="photo">Product Photo:</label>
        <input type="file" name="photo[]" id="photo" class="form-control" multiple>
      <?php endif; ?>
    </div>
    <div class="form-group col-md-6">
      <label for="description">Description:</label>
      <textarea id="description" name="description" class="form-control" rows="6"><?=$description;?></textarea>
    </div>
    <div class="form-group pull-right">
      <a href="products.php" class="btn btn-default">Cancel</a>
      <input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Product" class="btn btn-success">
    </div><div class="clearfix"></div>
  </form>

<?php }else{
$sql = "SELECT * FROM products WHERE deleted = 0" ;
$presults = $db->query($sql);
if (isset($_GET['featured'])) {
  $id = (int)$_GET['id'];
  $featured = (int)$_GET['featured'];
  $featuredSql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
  $db->query($featuredSql);
  header('Location: products.php');
}
 ?>
<h2 class="text-center">Products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a><div class="clearfix"></div>
<hr>
<table class="table table-bordered table-condensed table-striped">
  <thead><th></th><th>Product</th><th>Price</th><th><th>Featured</th><th>Sold</th></thead>
  <tbody>
<?php while($product = mysqli_fetch_assoc($presults)): ?>
      <tr>
        <td>
          <a href="products.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
          <a href="products.php?delete=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>

        </td>
        <td><?= $product['title'];?></td>
        <td><?=money($product['price']);?></td>
        <td><a href="products.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default">
          <span class="glyphicon glyphicon-<?=(($product['featured']==1)?'minus':'plus');?>"></span>
          </a>&nbsp <?=(($product['featured'] == 1)?'Featured Product':'');?></td>
        <td>0</td>
      </tr>
      <?php endwhile; ?>

  </tbody>
</table>

<?php } include 'includes/footer.php'; ?>
<script>
  jQuery('document').ready(function(){
    get_child_options('<?=$category;?>');
  });
</script>
