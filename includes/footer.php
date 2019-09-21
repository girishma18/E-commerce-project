</div> 
</div>
<hr>
<!-- Modal -->

<script> function detailsmodal(id)
{ var data={ "id" : id };
 jQuery.ajax(
 {
  url : '/svdjm/includes/detailsmodal.php', 
  method : "post",
  data : data,
  success: function(data){
      if (jQuery('#details-modal').length)
      {
       jQuery('#details-modal').remove();  <!--to load modal details everytime of different products--> 
      }
    jQuery('body').append(data);
    jQuery('#details-modal').modal('toggle');
  },
  error: function(){
    alert("something went wrong");
  }
 });   
}
function update_cart(mode,edit_id,edit_size) {
  var data = {"mode" : mode , "edit_id": edit_id , "edit_size" : edit_size};
  jQuery.ajax({
    url : '/svdjm/admin/parsers/update_cart.php',
    method : 'post',
    data : data,
    success : function(){ location.reload(); },
    error : function() {alert("something went wrong.");},
  });
}
function add_to_cart(){

  jQuery('#modal_errors').html("");
  var size = jQuery('#size').val(); 
  var quantity = jQuery('#quantity').val();
  var available = parseInt(jQuery('#available').val());
  var error = '';
   var data = $("#add_product_form").serializeArray();
  if(size == ''|| quantity == '' || quantity == 0) {
    error += '<p class="text-danger text-center"> You must choose a size and quantity.</p>';
    jQuery('#modal_errors').html(error);
    return;
  }else if(quantity>available){
    error += '<p class="alert alert-danger text-danger text-center"> There are only '+available+' availabe.</p>';
    jQuery('#modal_errors').html(error);
    return;
  }else{
  	jQuery.ajax({
  		url : '/svdjm/admin/parsers/add_cart.php',
  		method : 'post',
  		data : data,
  		success : function(){
  		  location.reload();
  		},
  		error : function(){alert("something went wrong");}
  	});
  }
} 
</script>

<footer class="text-center" id="footer"> <a href="contact.php">  Contact Us </a> </footer>



</body>
</html>