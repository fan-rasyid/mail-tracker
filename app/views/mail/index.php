<div class="main-wrapper main-wrapper-1">
    <?php

    require_once __DIR__ . "/../layouts/header.php";
    require_once __DIR__ . "/../layouts/sidebar.php";

    ?>

    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Incoming Mail</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="<?= BASEURL ?>dashboard">Dashboard</a></div>
                    <div class="breadcrumb-item">Incoming Mail</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Incoming Mail List</h2>
                <p class="section-lead">
                    Below is a list of all incoming mail data. You can view, edit, or delete each record.
                </p>

                <?php Flasher::flash(); ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Incoming Mail Table</h4>
                                <a href="<?= BASEURL ?>MailsController/create" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Add New Mail
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="table-incoming">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Sender</th>
                                                <th>Subject</th>
                                                <th>Date Received</th>
                                                <th>Attachment</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['mails'] as $index => $mail): ?>
                                                <tr>
                                                    <td class="text-center"><?= $index + 1 ?></td>
                                                    <td><?= htmlspecialchars($mail['sender']) ?></td>
                                                    <td><?= htmlspecialchars($mail['subject']) ?></td>
                                                    <td><?= htmlspecialchars($mail['date']) ?></td>
                                                    <td>
                                                        <?php if (!empty($mail['file'])): ?>
                                                            <a href="<?= BASEURL . 'uploads/' . htmlspecialchars($mail['file']) ?>"
                                                                target="_blank">View Attachment</a>
                                                        <?php else: ?>
                                                            No Attachment
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?= BASEURL ?>MailsController/edit/<?= $mail['id_mail'] ?>/in"
                                                            class="btn btn-warning btn-sm">Edit</a>
                                                        <a href="<?= BASEURL ?>MailsController/delete/<?= $mail['id_mail'] ?>"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this mail?');">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php require_once __DIR__ . "/../layouts/footer.php"; ?>

</div>