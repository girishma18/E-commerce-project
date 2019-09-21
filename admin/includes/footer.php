<hr>

<footer class="text-center" id="footer"> <a href="#"> Contact Us </a> </footer>


<script>
function updatesizes(){
	var sizestring = '';
    for(var i=1;i<=12;i++){
    	if(jQuery('#size'+i).val()!= ''){
    		sizestring += jQuery('#size'+i).val()+':'+jQuery('#eprice'+i).val()+':'+jQuery('#qty'+i).val()+',';
    	}
    }
  jQuery('#sizes').val(sizestring);
}

function get_child_options(selected){
  if(typeof selected === 'undefined'){
    var selected ='';
  }
	var parentid = jQuery('#parent').val();
	jQuery.ajax({
      url: '/svdjm/admin/parsers/child_categories.php',
      type: 'POST',
      data: {parentid : parentid, selected: selected},
      success: function(data){
      	jQuery('#child').html(data);
      },
      error: function(){alert("something went wrong with child options.")},
	});
}
jQuery('select[name= "parent"]').change(function(){
  get_child_options();
});

</script>


</body>
</html>