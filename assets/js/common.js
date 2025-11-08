document.addEventListener("DOMContentLoaded", function() {
	var loginDiv = document.getElementById('login');
	if (loginDiv) {
		loginDiv.classList.add('password-less-login-form');
	}
	var passwordField = document.getElementById('user_pass');
	if (passwordField) {
		passwordField.parentElement.style.display = 'none';
	}
});
jQuery(document).ready(function ($) {
	$("#loginform").submit(function (e) {
		e.preventDefault();
		var email = $("#user_login").val();
		var loader = $("#formLoader");
		loader.show();

		$.ajax({
			url: PASSWORDLESSLOGIN.pastemailresturl,
			type: "POST",
			data: {
				email: email,
				nonce: PASSWORDLESSLOGIN.nonce,
			},
			success: function (data) {
				loader.hide();
				if (data.userExists) {
					$("#otpName").hide();
				} else {
					$("#otpName").show();
				}
				$("#otpForm").show();
				$(".login-logo").show();
				$("#otpEmail").val(email);
				$(".password-less-login-form").hide();

				if (data.userExists) {
					$("#otpForm").find("input[name='otp']").attr("placeholder", "Enter OTP to sign in");
				} else {
					$("#otpForm").find("input[name='otp']").attr("placeholder", "Enter OTP to create account");
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
			url: PASSWORDLESSLOGIN.otpresturl,
			type: "POST",
			data: {
				email: email,
				name: name,
				otp: otp,
				nonce: PASSWORDLESSLOGIN.nonce,
			},
			success: function (data) {
				if (data.success) {
					window.location.href = PASSWORDLESSLOGIN.adminUrl;
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
