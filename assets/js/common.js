jQuery(document).ready(function ($) {
	// Handle the email form submission
	$("#loginform").submit(function (e) {
		e.preventDefault();
		var email = $("#user_login").val();

		$.ajax({
			url: EMAILLOGIN.emailresturl,
			type: "POST",
			data: { email: email },
			success: function (data) {
				if (data.userExists) {
					console.log("User exists, redirecting...");
					window.location.href = "/wp-admin";
				} else {
					console.log("No user found, showing OTP form...");
					$("#otpForm").show();
					$("#otpEmail").val(email);
				}
			},
			error: function (xhr, status, error) {
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
			contentType: "application/json",
			data: JSON.stringify({ email: email, name: name, otp: otp }),
			success: function (data) {
				if (data.success) {
					console.log("Registration and login successful.");
					window.location.href = "/wp-admin";
				} else {
					console.log("OTP verification failed.");
				}
			},
			error: function () {
				console.error("Failed to verify OTP.");
			},
		});
	});
});
