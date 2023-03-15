<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark"  data-bs-theme="dark">
    
    <div class="container-fluid">
        <li class="nav-item d-flex me-5">
            <h2 class="logo"><a href="index.php">Quin</a></h2>
        </li>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="dashboard.php"><?php echo lang('HOME'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="items.php"><?php echo lang('ITEMS'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="members.php"><?php echo lang('MEMBERS'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo lang('STATISTICS'); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo lang('LOGS'); ?></a>
                </li>
                
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        admin
                      </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><?php echo lang('PROFILE'); ?></a></li>
                        <li><a class="dropdown-item" href="members.php?do=edit&id=<?php echo $_SESSION['id']; ?>"><?php echo lang('SETTINGS'); ?></a></li>
                        <li><a class="dropdown-item" href="logout.php"><?php echo lang('LOGOUT'); ?></a></li>
                    </ul>
                </li>

            </ul>

        </div>
    </div>

</nav>

