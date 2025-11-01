    <div class="main-wrapper main-wrapper-1">

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Dashboard</h1>
          </div>
          <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                  <i class="far fa-user"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Admin</h4>
                  </div>
                  <div class="card-body">
                    <?= $data['total_admin']; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="far fa-envelope"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Incoming Mail</h4>
                  </div>
                  <div class="card-body">
                    <?= $data['total_incoming_mails']; ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="far fa-envelope"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Outgoing Mail</h4>
                  </div>
                  <div class="card-body">
                    <?= $data['total_outgoing_mails']; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>


    </div>