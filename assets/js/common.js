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
					window.location.href = "/wp-admin"; // Or any other target location
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
