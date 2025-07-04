<?php require_once 'app/views/templates/header.php'; ?>

<div class="container mt-4">
    <h1 class="display-4">My Notes & Reminders</h1>
    <a href="/reminders/create" class="btn btn-success btn-lg">Add New Note</a>

    <?php if (empty($data['reminders'])): ?>
        <div class="alert alert-info">No notes found.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($data['reminders'] as $reminder): ?>
                <div class="col-md-6">
                    <h5><?php echo htmlspecialchars($reminder['subject']); ?></h5>
                    <a href="/reminders/edit/<?php echo $reminder['id']; ?>" class="btn btn-warning">Edit</a>
                    <a href="/reminders/delete/<?php echo $reminder['id']; ?>" class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to delete this note?')">Delete</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>