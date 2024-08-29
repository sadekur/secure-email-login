jQuery(document).ready(function ($) {
	// Handle the email form submission
	$("#loginform").submit(function (e) {
		e.preventDefault();
		var email = $("#user_login").val();
		console.log(email);

		console.log("Email submission URL:", EMAILLOGIN.emailresturl);
		console.log("OTP verification URL:", EMAILLOGIN.otpresturl);

		$.ajax({
			url: EMAILLOGIN.emailresturl,
			type: "POST",
			data: { email: email },
			success: function (data) {
				if (data.userExists) {
					console.log("User exists, check email for login link.");
					window.location.href = "/login";
				} else {
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
			// error: function (status) {
			// 	console.error("Failed to process request." + status);
			// },
		});
	});

	// Handle the OTP form submission
	// $("#otpForm").submit(function (e) {
	// 	e.preventDefault();
	// 	var email = $("#otpEmail").val();
	// 	var name = $("#otpName").val();
	// 	var otp = $("#otp").val();

	// 	$.ajax({
	// 		url: EMAILLOGIN.otpresturl,
	// 		type: "POST",
	// 		contentType: "application/json",
	// 		data: JSON.stringify({ email: email, name: name, otp: otp }),
	// 		success: function (data) {
	// 			if (data.success) {
	// 				console.log("Registration and login successful.");
	// 			} else {
	// 				console.log("OTP verification failed.");
	// 			}
	// 		},
	// 		error: function () {
	// 			console.error("Failed to verify OTP.");
	// 		},
	// 	});
	// });
});
