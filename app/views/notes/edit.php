
<?php require_once 'app/views/templates/header.php' ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Edit Reminder</h2>
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
                <form method="post" action="/notes/edit?id=<?php echo $data['note']['id']; ?>">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject *</label>
                        <input type="text" class="form-control" id="subject" name="subject" required maxlength="255" 
                               placeholder="Enter reminder subject" value="<?php echo htmlspecialchars($data['note']['subject']); ?>">
                        <div class="form-text">Brief description of your reminder</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Details</label>
                        <textarea class="form-control" id="content" name="content" rows="4" 
                                  placeholder="Add any additional details or notes..."><?php echo htmlspecialchars($data['note']['content']); ?></textarea>
                        <div class="form-text">Optional: Add more details about this reminder</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="completed" name="completed" 
                                   <?php echo $data['note']['completed'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="completed">
                                Mark as completed
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">
                            Created: <?php echo date('M j, Y g:i A', strtotime($data['note']['created_at'])); ?>
                        </small>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="/notes" class="btn btn-outline-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Reminder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/templates/footer.php' ?>
<?php require_once 'app/views/templates/header.php' ?>
<div class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-8">
                <h1>Edit Note</h1>
                <p class="lead">Update your note or task</p>
            </div>
            <div class="col-lg-4 text-end">
                <a href="/notes" class="btn btn-secondary">Back to Notes</a>
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
                                   value="<?= htmlspecialchars($note['subject']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control" id="content" name="content" rows="5"><?= htmlspecialchars($note['content']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="completed" name="completed" 
                                       <?= $note['completed'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="completed">
                                    Mark as completed
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/notes" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Note</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'app/views/templates/footer.php' ?>
