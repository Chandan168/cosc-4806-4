<?php require_once 'app/views/templates/header.php' ?>
<div class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-8">
                <h1>Create New Reminder</h1>
                <p class="lead">Add a new task or reminder</p>
            </div>
            <div class="col-lg-4 text-end">
                <a href="/reminders" class="btn btn-secondary">Back to Reminders</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject *</label>
                            <input type="text" class="form-control" id="subject" name="subject" 
                                   value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Details</label>
                            <textarea class="form-control" id="content" name="content" rows="5" 
                                      placeholder="Add additional details about your reminder..."><?= htmlspecialchars($_POST['content'] ?? '') ?></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/reminders" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-success">Create Reminder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/templates/footer.php' ?>