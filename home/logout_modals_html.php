<link rel="stylesheet" href="../styles/logout_modal.css">

<script src="../js/logout_modal.js"></script>
<?php
function logoutModalPhp($userType ) 
{ ?>
  <div id="logoutModal" class="all-modals">
    <div>
      <p>Confirm Logout?</p>
      <?php if ($userType == 'admin') { ?>
        <button onclick="confirmLogout('admin')" id="yes-btn">Yes</button>
      <?php } else if ($userType == 'voter') { ?>
        <button onclick="confirmLogout('voter')" id="yes-btn">Yes</button>
      <?php } ?>
      <button onclick="closeLogoutModal()" id="no-btn">No</button>
    </div>
  </div>
<?php } ?>