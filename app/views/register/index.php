<?php require_once 'app/views/templates/headerPublic.php' ?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Create Account</h2>

            <?php if (isset($_SESSION['registration_error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($_SESSION['registration_error']); ?>
                </div>
                <?php unset($_SESSION['registration_error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['registration_success'])): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($_SESSION['registration_success']); ?>
                </div>
                <?php unset($_SESSION['registration_success']); ?>
            <?php endif; ?>

            <form action="/register/create" method="post">
                <fieldset>
                    <div class="form-group mb-3">
                        <label for="username">Username</label>
                        <input required type="text" class="form-control" name="username" minlength="3" maxlength="50">
                        <small class="form-text text-muted">Must be 3-50 characters long</small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input required type="password" class="form-control" name="password" minlength="8">
                        <small class="form-text text-muted">Must be at least 8 characters with uppercase, lowercase, and number</small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="confirm_password">Confirm Password</label>
                        <input required type="password" class="form-control" name="confirm_password" minlength="8">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-success">Create Account</button>
                    <a href="/login" class="btn btn-link">Already have an account? Login</a>
                </fieldset>
            </form> 
        </div>
    </div>
</main>

<?php require_once 'app/views/templates/footer.php' ?>