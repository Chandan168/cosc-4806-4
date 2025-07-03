
<?php require_once 'app/views/templates/header.php' ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Reminders</h2>
    <a href="/notes/create" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add New Reminder
    </a>
</div>

<?php if (isset($_SESSION['note_success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_SESSION['note_success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['note_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['note_error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_SESSION['note_error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['note_error']); ?>
<?php endif; ?>

<?php if (empty($data['notes'])): ?>
    <div class="text-center py-5">
        <div class="mb-3">
            <i class="bi bi-journal-text" style="font-size: 3rem; color: #6c757d;"></i>
        </div>
        <h4 class="text-muted">No reminders yet</h4>
        <p class="text-muted">Create your first reminder to get started!</p>
        <a href="/notes/create" class="btn btn-primary">Create Reminder</a>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($data['notes'] as $note): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 <?php echo $note['completed'] ? 'border-success' : ''; ?>">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 <?php echo $note['completed'] ? 'text-decoration-line-through text-muted' : ''; ?>">
                            <?php echo htmlspecialchars($note['subject']); ?>
                        </h6>
                        <?php if ($note['completed']): ?>
                            <span class="badge bg-success">Completed</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($note['content'])): ?>
                            <p class="card-text <?php echo $note['completed'] ? 'text-muted' : ''; ?>">
                                <?php echo nl2br(htmlspecialchars($note['content'])); ?>
                            </p>
                        <?php endif; ?>
                        <small class="text-muted">
                            Created: <?php echo date('M j, Y g:i A', strtotime($note['created_at'])); ?>
                        </small>
                    </div>
                    <div class="card-footer">
                        <div class="btn-group w-100" role="group">
                            <form method="post" action="/notes/toggle" class="d-inline">
                                <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                                <button type="submit" class="btn btn-sm <?php echo $note['completed'] ? 'btn-warning' : 'btn-success'; ?>">
                                    <?php echo $note['completed'] ? 'Mark Incomplete' : 'Mark Complete'; ?>
                                </button>
                            </form>
                            <a href="/notes/edit?id=<?php echo $note['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form method="post" action="/notes/delete" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this reminder?');">
                                <input type="hidden" name="id" value="<?php echo $note['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<?php require_once 'app/views/templates/footer.php' ?>
<?php require_once 'app/views/templates/header.php' ?>
<div class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-8">
                <h1>My Notes</h1>
                <p class="lead">Manage your notes and tasks</p>
            </div>
            <div class="col-lg-4 text-end">
                <a href="/notes/create" class="btn btn-primary">Create New Note</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php if (empty($notes)): ?>
                <div class="alert alert-info">
                    <h4>No notes yet!</h4>
                    <p>Get started by <a href="/notes/create">creating your first note</a>.</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($notes as $note): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 <?= $note['completed'] ? 'bg-light' : '' ?>">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span class="badge <?= $note['completed'] ? 'bg-success' : 'bg-warning text-dark' ?>">
                                        <?= $note['completed'] ? 'Completed' : 'Pending' ?>
                                    </span>
                                    <small class="text-muted"><?= date('M j, Y', strtotime($note['created_at'])) ?></small>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title <?= $note['completed'] ? 'text-decoration-line-through text-muted' : '' ?>">
                                        <?= htmlspecialchars($note['subject']) ?>
                                    </h5>
                                    <?php if (!empty($note['content'])): ?>
                                        <p class="card-text <?= $note['completed'] ? 'text-muted' : '' ?>">
                                            <?= nl2br(htmlspecialchars(substr($note['content'], 0, 100))) ?>
                                            <?= strlen($note['content']) > 100 ? '...' : '' ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group w-100" role="group">
                                        <a href="/notes/toggle/<?= $note['id'] ?>" 
                                           class="btn btn-sm <?= $note['completed'] ? 'btn-warning' : 'btn-success' ?>">
                                            <?= $note['completed'] ? 'Reopen' : 'Complete' ?>
                                        </a>
                                        <a href="/notes/edit/<?= $note['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="/notes/delete/<?= $note['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this note?')">Delete</a>
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
