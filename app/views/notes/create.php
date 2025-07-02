
<?php require_once 'app/views/templates/header.php' ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Create New Reminder</h2>
            <a href="/notes" class="btn btn-secondary">Back to Reminders</a>
        </div>

        <?php if (isset($_SESSION['note_error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($_SESSION['note_error']); ?>
            </div>
            <?php unset($_SESSION['note_error']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="post" action="/notes/create">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject *</label>
                        <input type="text" class="form-control" id="subject" name="subject" required maxlength="255" 
                               placeholder="Enter reminder subject" value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
                        <div class="form-text">Brief description of your reminder</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Details</label>
                        <textarea class="form-control" id="content" name="content" rows="4" 
                                  placeholder="Add any additional details or notes..."><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
                        <div class="form-text">Optional: Add more details about this reminder</div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="/notes" class="btn btn-outline-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Create Reminder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/templates/footer.php' ?>
