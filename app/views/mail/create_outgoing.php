<div class="main-wrapper main-wrapper-1">

    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Outgoing Mail</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">
                        <a href="<?= BASEURL ?>dashboard">Dashboard</a>
                    </div>
                    <div class="breadcrumb-item">Outgoing Mail</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Create Outgoing Mail</h2>
                <p class="section-lead">
                    Please fill in the details below to add a new outgoing mail record.
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Outgoing Mail Form</h4>
                            </div>

                            <form action="<?= BASEURL ?>MailsController/storeOutgoing" method="POST"
                                enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="mail_date">Mail Date</label>
                                            <input type="date" class="form-control" id="mail_date" name="date"
                                                required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="mail_type">Mail Type</label>
                                            <input type="text" class="form-control" id="mail_type" name="type_mail"
                                                placeholder="Enter mail type" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="sender">Sender (From)</label>
                                            <input type="text" class="form-control" id="sender" name="sender"
                                                placeholder="Enter sender name or institution" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="receiver">Recipient</label>
                                            <input type="text" class="form-control" id="receiver" name="recepient"
                                                placeholder="Enter recipient name or institution" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="subject">Subject</label>
                                        <input type="text" class="form-control" id="subject" name="subject"
                                            placeholder="Enter mail subject" required>
                                    </div>


                                    <div class="form-group">
                                        <label for="attachment">Attachment (PDF / Image) <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control" id="attachment" name="file"
                                            accept=".pdf, .jpg, .jpeg, .png" required>
                                        <small class="form-text text-muted">
                                            Allowed formats: PDF, JPG, JPEG, PNG
                                        </small>
                                    </div>
                                </div>

                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Mail
                                    </button>
                                    <a href="<?= BASEURL ?>MailsController/outgoingMail" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </a>
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