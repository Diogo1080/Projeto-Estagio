<?php require('conn.php'); ?>
<html>
<?php include('head.php'); ?>
<body>
	<div class="container">
	      <?php include('navbar_dashboard.php'); ?>

	      <div class="col-sm-12">
	        <div class="card"style="margin-top: 30px">
	          <div class="card-header"> 
	            <h3 class="panel-title">Add a Product</h3>
	          </div>
	          <div class="card-body">
	            <form>
            	<div class="form-group">
            	  <label for="exampleFormControlFile1">Insert Product Picture</label>
            	  <input type="file" class="form-control-file" id="exampleFormControlFile1">
            	</div>
	              <div class="form-group">
	                <label for="exampleFormControlInput1">Product's Name</label>
	                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Ex: Selmer Recital">
	              </div>
	              <div class="form-group">
	                <label for="exampleFormControlSelect1">Category</label>
	                <select class="form-control" id="exampleFormControlSelect1">
	                	<!-- PHP FETCH CATEGORIES -->
	                  <option>1</option>
	                  <option>2</option>
	                  <option>3</option>
	                  <option>4</option>
	                  <option>5</option>
	                </select>
	              </div>
	              <div class="form-group">
	                <label for="exampleFormControlInput1">Price</label>
	                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="€€€">
	              </div>
	              <div class="form-group">
	                <label for="exampleFormControlTextarea1">Product Info</label>
	                <textarea class="form-control" id="exampleFormControlTextarea1" rows="5"></textarea>
	              </div>
	              <button type="submit" class="btn btn-default btn-lg">Call to Action &raquo;</button>
	            </form>
	          </div>
	      </div>
	</div>

</body>
</html>