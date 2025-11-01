<div class="main-wrapper main-wrapper-1">
    <?php
    require_once __DIR__ . "/../layouts/header.php";
    require_once __DIR__ . "/../layouts/sidebar.php";
    ?>

    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Mail</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">
                        <a href="<?= BASEURL ?>dashboard">Dashboard</a>
                    </div>
                    <div class="breadcrumb-item">Mail</div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Edit Mail Details</h2>
                <p class="section-lead">
                    Update the details of the mail record below.
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Edit Mail Form</h4>
                            </div>

                            <form action="<?= BASEURL ?>MailsController/update" method="POST"
                                enctype="multipart/form-data">
                                <input type="hidden" name="id_mail" value="<?= $data['mails']['id_mail'] ?>">
                                <input type="hidden" name="type" value="<?= $data['mails']['type'] ?>">
                                
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="mail_date">Mail Date</label>
                                            <input type="date" class="form-control" id="mail_date" name="date"
                                                value="<?= $data['mails']['date'] ?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="mail_type">Mail Type</label>
                                            <input type="text" class="form-control" id="mail_type" name="type_mail"
                                                value="<?= $data['mails']['type_mail'] ?>" placeholder="Enter mail type" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <?php if ($data['mails']['type'] === 'in'): ?>
                                                <label for="sender">Sender</label>
                                            <?php else: ?>
                                                <label for="sender">Sender (From)</label>
                                            <?php endif; ?>
                                            <input type="text" class="form-control" id="sender" name="sender"
                                                value="<?= htmlspecialchars($data['mails']['sender']) ?>" placeholder="Enter sender name or institution" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <?php if ($data['mails']['type'] === 'in'): ?>
                                                <label for="receiver">Receiver</label>
                                            <?php else: ?>
                                                <label for="receiver">Recipient</label>
                                            <?php endif; ?>
                                            <input type="text" class="form-control" id="receiver" name="recepient"
                                                value="<?= htmlspecialchars($data['mails']['recepient']) ?>" placeholder="Enter receiver/recipient name" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        <input type="text" class="form-control" id="subject" name="subject"
                                            value="<?= htmlspecialchars($data['mails']['subject']) ?>" placeholder="Enter mail subject" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="attachment">Current Attachment</label>
                                        <?php if (!empty($data['mails']['file'])): ?>
                                            <div class="mb-2">
                                                <a href="<?= BASEURL . 'uploads/' . $data['mails']['file'] ?>" target="_blank">
                                                    View Current Attachment
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-muted">No attachment currently</p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="form-group">
                                        <label for="attachment">Upload New Attachment (PDF / Image)</label>
                                        <input type="file" class="form-control" id="attachment" name="file"
                                            accept=".pdf, .jpg, .jpeg, .png">
                                        <small class="form-text text-muted">
                                            Allowed formats: PDF, JPG, JPEG, PNG. Leave empty to keep current attachment.
                                        </small>
                                    </div>
                                </div>

                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Mail
                                    </button>
                                    <?php if ($data['mails']['type'] === 'out'): ?>
                                        <a href="<?= BASEURL ?>MailsController/outgoingMail" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Back to Outgoing Mail
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= BASEURL ?>MailsController/incomingMail" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Back to Incoming Mail
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php require_once __DIR__ . "/../layouts/footer.php"; ?>
</div>