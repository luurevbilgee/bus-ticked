<?php
include ('db_connect.php');
if (isset($_GET['id']) && !empty($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM users where id = " . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $val) {
		$meta[$k] = $val;
	}
}
?>
<div class="container-fluid">
	<form id="manage_user">
		<div class="col-md-12">
			<div class="form-group mb-2">
				<label for="name" class="control-label">Нэр</label>
				<input type="hidden" class="form-control" id="id" name="id"
					value='<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>' required="">
				<input type="text" class="form-control" id="name" name="name" required=""
					value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>">
			</div>
			<div class="form-group mb-2">
				<label for="grant" class="control-label">Эрхийн түвшин олгох</label><br>
				<!-- delete -->
				<input type="checkbox" id="delete" name="delete" value="delete" <?php echo isset($meta['delete']) && $meta['delete'] ? 'checked' : '' ?>>
				<label for="delete">Delete</label>
				<!-- update -->
				<input type="checkbox" id="update" name="update" value="update" <?php echo isset($meta['update']) && $meta['update'] ? 'checked' : '' ?>>
				<label for="update">Update</label>
				<!-- insert -->
				<input type="checkbox" id="insert" name="insert" value="insert" <?php echo isset($meta['insert']) && $meta['insert'] ? 'checked' : '' ?>>
				<label for="insert">Insert</label>
				<!-- select -->
				<input type="checkbox" id="select" name="select" value="select" <?php echo isset($meta['select']) && $meta['select'] ? 'checked' : '' ?>>
				<label for="select">Select</label>
			</div>
			<div class="form-group mb-2">
				<label for="username" class="control-label">User Name</label>
				<input type="text" class="form-control" id="username" name="username" required
					value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>">
			</div>
			<div class="form-group mb-2">
				<label for="password" class="control-label">Password</label>
				<input type="password" class="form-control" id="password" name="password" oninput="updateDisplay()"
					required value="<?php echo isset($meta['password']) ? $meta['password'] : '' ?>">
				<span id="password-error" class=" text-danger"></span>
				<div id="display"></div>
			</div>
		</div>
	</form>
</div>
<script>
	$('#manage_user').submit(function (e) {
		e.preventDefault()
		var username = $('#username').val().trim();
		var password = $('#password').val().trim();
		var name = $("#name").val().trim();
		var insert = $("#insert").prop('checked') ? 'insert' : '';
		var deleted = $("#delete").prop('checked') ? 'delete' : '';
		var select = $("#select").prop('checked') ? 'select' : '';
		var update = $("#update").prop('checked') ? 'update' : '';
		// alert(insert);
		// Check if username or password is empty
		if (name == '' || username === '' || password === '') {
			// Display an error message
			alert('Нэр, нэвтрэх нэр болон нууц үгээ оруулна уу.');
			return; // Prevent form submission
		}
		if (select == '' && insert == '' && update == '' && deleted == '') {
			// Display an error message
			alert('Эрхийн түвшинг тодорхойлно уу.');
			return; // Prevent form submission
		}
		if (password.length < 8) {
			$('#password-error').text('Password хамгийн багадаа 8 тэмдэгтээс их байна');
			$('#password-error').show();
			return;
		}
		else {
			$('#password-error').hide();
		}
		var letterRegex = /[a-zA-Z]/;
		var numberRegex = /[0-9]/;
		var symbolRegex = /[!@#$%&]/;

		if (!symbolRegex.test(password) || !letterRegex.test(password) || !numberRegex.test(password)) {

			$('#password-error').text('Үсэг, тоо , тэмдэгт оруулна  оролцуулна хийнэ үү.');
			$('#password-error').show();
			return;
		} else {
			$('#password-error').hide();
		}

		start_load()
		$.ajax({
			url: './save_user.php',
			method: 'POST',
			data: $(this).serialize(),
			error: err => {
				console.log(err)
				end_load()
				alert_toast('An error occured', 'danger');
			},
			success: function (resp) {
				if (resp == 1) {
					end_load()
					$('.modal').modal('hide')
					alert_toast('Data successfully saved', 'success');
					load_user()
				}
			}
		})
	})


</script>