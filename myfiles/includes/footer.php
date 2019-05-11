<html>
<body>



<footer class="text-center" id="footer">&copy;Copyright 2016 BillyDoo</footer>


<script>

function detailsmodal(id){
  var data = {"id" : id};
  jQuery.ajax({
    url :   '/myfiles/includes/detailsmodal.php',
    method : "post",
    data : data,
    success: function(data){
      jQuery('body').append(data);
      jQuery('#details-modal').modal('toggle');

    },
    error: function(){
      alert("Something Went Wrong");
    }
  });
}
</script>
</body>
</html>
