
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<form class="form-signin" enctype="application/json" id="myForm">
  <h2 class="form-signin-heading">Please sign in</h2>
  <button class="btn btn-lg btn-primary btn-block" id="send">Sign in</button>
  <div id="result">

  </div>
  <script>
	$("send").click(function(){
	  $.post("https://bugs.podflow.de/staff/get-all",{

  },
  function(data){
    alert(data );
  });
});



	

  </script>
</form>