<?php
session_start();
session_unset();
  session_destroy();
  echo"<script>window.location.href='voter_login.html';</script>";
?>