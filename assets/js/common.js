jQuery(document).ready(function ($) {
	$("#loginform").submit(function (e) {
		e.preventDefault();
		var email = $("#user_login").val();
		var loader = $("#formLoader");
		loader.show();

		$.ajax({
			url: EMAILLOGIN.emailresturl,
			type: "POST",
			data: {
				email: email,
				nonce: EMAILLOGIN.nonce,
			},
			success: function (data) {
				loader.hide();
				if (data.userExists) {
					console.log("User exists, redirecting...");
					window.location.href = EMAILLOGIN.adminUrl;
				} else {
					console.log("No user found, showing OTP form...");
					$("#otpForm").show();
					$(".login-logo").show();
					$("#otpEmail").val(email);
					$(".secure-email-login-form").hide();
				}
			},
			error: function (xhr, status, error) {
				loader.hide();
				console.error(
					"Failed to process request:",
					status,
					xhr.responseText,
					error
				);
			},
		});
	});

	// Handle the OTP form submission
	$("#otpForm").submit(function (e) {
		e.preventDefault();
		var email = $("#otpEmail").val();
		var name = $("#otpName").val();
		var otp = $("#otp").val();

		$.ajax({
			url: EMAILLOGIN.otpresturl,
			type: "POST",
			data: {
				email: email,
				name: name,
				otp: otp,
				nonce: EMAILLOGIN.nonce,
			},
			success: function (data) {
				if (data.success) {
					console.log(data.success);
					window.location.href = EMAILLOGIN.adminUrl;
				} else {
					console.log(data.message);
				}
			},
			error: function (error) {
				console.error(error);
			},
		});
	});
});
