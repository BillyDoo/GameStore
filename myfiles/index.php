<?php
      require_once 'core/init.php';
      include 'includes/head.php';
      include 'includes/navigation.php';
      include 'includes/header.php';


      $sql="SELECT * FROM products WHERE featured = 1";
      $featured = $db->query($sql);

?>

          <div class="col-md-8"></div>
            <div class="row">
              <h2 class="text-center"><b> ΠΡΟΣΦΟΡΕΣ</b></h2>
              <?php while($product = mysqli_fetch_assoc($featured)) : ?>

              <div class="col-sm-3 text-center">
                <h4><?= $product['title']; ?> </h4>
                <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>"class="img-thumb" />
                <p class="price">Προσφορά:$<?= $product['price']; ?></p>
                <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?= $product['id'];?>)">
                  Details </button>
            </div>
          <?php endwhile; ?>


      </div>

                <!-- Right BAR -->
            <div class="col-md-2"></div>

            <?php
                
                  include 'includes/footer.php';
             ?>



<!-- DETAILS MODEL -->






<!-- <script>

  jQuery(window).scroll(function(){
    var vscroll=jQuery(this).scrollTop();
    jQuery('#Unknown').css({
      "transform":"translate(0px, "+vscroll/5+"px)"

    });
  });
  </script>
-->


  </body>
  </html>
