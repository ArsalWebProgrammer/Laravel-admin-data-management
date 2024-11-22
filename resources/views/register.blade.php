<!DOCTYPE html>
<html lang="en">
<body class="bg-gradient-login">
@include('common.sidebar')  
  <!-- Register Content -->
 
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-12">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Register</h1>
                  </div>
                   <!-- Validation Errors Display -->
                   @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                  <form id="registerForm" method="POST">

                    @csrf
                    <!-- Full Name -->
                    <div class="form-group">
                      <label>Full Name</label>
                      <input type="text" class="form-control" id="exampleInputFirstName" name="fullname" required placeholder="Enter Full Name">
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                      <label>Phone</label>
                      <input type="text" class="form-control" id="exampleInputLastName" name="phone" required placeholder="Enter Phone Number">
                    </div>

                   <!-- Email -->
                <div class="form-group" id="emailField">
                  <label id="emailLabel" for="exampleInputEmail">Email</label>
                  <input type="email" class="form-control" id="exampleInputEmail" name="email" required aria-describedby="emailHelp" placeholder="Enter Email Address">
                  @error('email')
                    <span class="text-danger">{{ $message }}</span>
                  @enderror
                </div>

                    <!-- User Type -->
                    <div class="form-group">
                      <label>User Type</label>
                      <select class="form-control" id="userType" required name="usertype">
                        <option disabled selected value="">Select</option>
                        <option value="Admin">Admin</option>
                        <option value="Driver">Driver</option>
                        <option value="Client">Client</option> <!-- Added Client option -->
                      </select>
                    </div>

                    <!-- Iqama (Initially Hidden) -->
                    <div class="form-group" id="iqamaField" style="display: none;">
                      <label>Iqama</label>
                      <input type="text" class="form-control" id="iqamaInput" name="iqama" placeholder="Enter Iqama Number" minlength="10" maxlength="10" pattern="\d{10}">
                      <small class="text-danger" id="iqamaError" style="display: none;">Iqama number must be exactly 10 digits.</small>
                    </div>

                    <!-- Company Name (Initially Hidden) -->
                    <div class="form-group" id="companyField" style="display: none;">
                      <label>Company Name</label>
                      <input type="text" class="form-control" id="companyInput" name="company_name" placeholder="Enter Company Name">
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                      <label>Password</label>
                      <input type="password" class="form-control" id="exampleInputPassword" name="pass" required placeholder="Password" minlength="8">
                      @error('pass')
                        <span class="text-danger">{{ $message }}</span>
                      @enderror
                    </div>

                    <!-- Repeat Password -->
                    <div class="form-group">
                      <label>Repeat Password</label>
                      <input type="password" class="form-control" id="exampleInputPasswordRepeat" name="pass_confirmation" required placeholder="Repeat Password">
                      <small id="passwordError" class="text-danger" style="display: none;">Passwords do not match.</small>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                      <button type="submit" id="submitButton" class="btn btn-primary btn-block" disabled>Register</button>
                    </div>
                  </form>
                  <div class="text-center">
                    <a class="font-weight-bold small" href="{{route('login')}}">Already have an account?</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@include('common.footerjqr')  
  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const userTypeSelect = document.getElementById('userType');
    const iqamaField = document.getElementById('iqamaField');
    const companyField = document.getElementById('companyField');
    const emailField = document.getElementById('emailField'); // Email field container
    const emailLabel = document.getElementById('emailLabel'); // Email field label
    const iqamaInput = document.getElementById('iqamaInput');
    const iqamaError = document.getElementById('iqamaError');
    const password = document.getElementById('exampleInputPassword');
    const passwordConfirmation = document.getElementById('exampleInputPasswordRepeat');
    const submitButton = document.getElementById('submitButton');
    const passwordError = document.getElementById('passwordError');
    const emailInput = document.getElementById('exampleInputEmail'); // Email input element

    // Show/hide fields based on User Type
    userTypeSelect.addEventListener('change', function() {
        if (userTypeSelect.value === 'Driver') {
            // Driver selected
            iqamaField.style.display = 'block';
            iqamaInput.required = true;
            companyField.style.display = 'none'; // Hide company field for drivers
            emailField.style.display = 'none'; // Hide email field for drivers
            emailLabel.style.display = 'none'; // Hide email label for drivers
            emailInput.removeAttribute('required'); // Remove required attribute
        } else if (userTypeSelect.value === 'Client') {
            // Client selected
            companyField.style.display = 'block'; // Show company field for clients
            iqamaField.style.display = 'none'; // Hide iqama field for clients
            iqamaInput.required = false; // Iqama is not required for clients
            iqamaInput.value = ''; // Clear the Iqama field
            iqamaError.style.display = 'none'; // Hide error if Iqama is not required
            emailField.style.display = 'none'; // Hide email field for clients
            emailLabel.style.display = 'none'; // Hide email label for clients
            emailInput.removeAttribute('required'); // Remove required attribute
        } else {
            // Admin selected (or any other value)
            iqamaField.style.display = 'none';
            companyField.style.display = 'none'; // Hide company field for admins
            emailField.style.display = 'block'; // Show email field for admins
            emailLabel.style.display = 'block'; // Show email label for admins
            emailInput.setAttribute('required', 'true'); // Add required attribute back
        }
        checkPasswords(); // Re-check password validity whenever user type changes
    });

    // Validate Iqama for 10-digit numeric input
    iqamaInput.addEventListener('input', function() {
        if (userTypeSelect.value === 'Driver' && !/^\d{10}$/.test(iqamaInput.value)) {
            iqamaError.style.display = 'block';
            submitButton.disabled = true; // Disable the button if Iqama is invalid
        } else {
            iqamaError.style.display = 'none';
            checkPasswords(); // Re-check password validity if Iqama is valid
        }
    });

    // Function to check if passwords match and enable/disable button
    function checkPasswords() {
        const passwordsMatch = password.value === passwordConfirmation.value;
        const passwordsNotEmpty = password.value.length > 0 && passwordConfirmation.value.length > 0;

        // Enable submit button only if both passwords match and required fields are valid
        submitButton.disabled = !(passwordsMatch && passwordsNotEmpty);

        if (!passwordsMatch) {
            passwordError.style.display = "block"; // Show error message
            passwordConfirmation.setCustomValidity("Passwords do not match.");
        } else {
            passwordError.style.display = "none"; // Hide error message
            passwordConfirmation.setCustomValidity("");
        }
    }

    // Event listeners for password match validation
    password.addEventListener('input', checkPasswords);
    passwordConfirmation.addEventListener('input', checkPasswords);

    // AJAX form submission
    $('#registerForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // If the email field is hidden and not required, set it to an empty string
        if (emailField.style.display === 'none') {
            emailInput.value = '';
        }

        $.ajax({
            url: '{{ route("registeru") }}', // Your route to handle the registration
            type: 'POST',
            data: $(this).serialize(), // Serialize the form data
            success: function(response) {
                alert(response.success); // Show the success message
                $('#registerForm')[0].reset(); // Reset the form fields
                submitButton.disabled = true; // Disable button again after reset
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    alert(xhr.responseJSON.error); // Show error message if any
                } else {
                    alert('An unexpected error occurred.'); // Generic error message
                }
            }
        });
    });
});

  </script>
</body>
</html>
