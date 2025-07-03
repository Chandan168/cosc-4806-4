<?php require_once 'app/views/templates/header.php' ?>
<div class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-8">
                <h1>My Reminders</h1>
                <p class="lead">Manage your tasks and reminders</p>
            </div>
            <div class="col-lg-4 text-end">
                <a href="/reminders/create" class="btn btn-success">Add New Reminder</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php if (empty($reminders)): ?>
                <div class="alert alert-info">
                    <h4>No reminders yet!</h4>
                    <p>Get started by <a href="/reminders/create">creating your first reminder</a>.</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($reminders as $reminder): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card <?= $reminder['completed'] ? 'border-success' : 'border-warning' ?>">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span class="badge <?= $reminder['completed'] ? 'bg-success' : 'bg-warning' ?>">
                                        <?= $reminder['completed'] ? 'Completed' : 'Pending' ?>
                                    </span>
                                    <small class="text-muted"><?= date('M j, Y', strtotime($reminder['created_at'])) ?></small>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title <?= $reminder['completed'] ? 'text-decoration-line-through text-muted' : '' ?>">
                                        <?= htmlspecialchars($reminder['subject']) ?>
                                    </h5>
                                    <?php if (!empty($reminder['content'])): ?>
                                        <p class="card-text <?= $reminder['completed'] ? 'text-muted' : '' ?>">
                                            <?= nl2br(htmlspecialchars(substr($reminder['content'], 0, 100))) ?>
                                            <?= strlen($reminder['content']) > 100 ? '...' : '' ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group w-100" role="group">
                                        <a href="/reminders/toggle/<?= $reminder['id'] ?>" 
                                           class="btn btn-sm <?= $reminder['completed'] ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                                            <?= $reminder['completed'] ? 'Mark Pending' : 'Mark Complete' ?>
                                        </a>
                                        <a href="/reminders/edit/<?= $reminder['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <a href="/reminders/delete/<?= $reminder['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to delete this reminder?')">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'app/views/templates/footer.php' ?>