<?php
require_once "../register_and_login/dbconnection.php";
function getTotal($conn, $table)
{
  $tableName = $table;
  $data = [];
  $sql = "SELECT count(*) as totalRows from " . $tableName;
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    // return $result;
    $data = mysqli_fetch_assoc($result);
    return $data["totalRows"];
  }else{
    return 0;
}}
function totalVoteCasted($conn)
{
  $data = [];
  $sql = "SELECT COUNT(*) AS totalVotes 
FROM voters 
WHERE votingStatus = 'voted'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    // return $result;
    $data = mysqli_fetch_assoc($result);
    return $data["totalVotes"];
  }
}function electionTime($conn)
{
  $data = [];
  $sql = "SELECT COUNT(*) AS totalVotes 
FROM voters 
WHERE votingStatus = 'voted'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    // return $result;
    $data = mysqli_fetch_assoc($result);
    return $data["totalVotes"];
  }
}
// $totalVoters=getTotal($conn,"voters");

// $totalDistrict=getTotal($conn,"district");
// echo $totalVoters."<br>".$totalDistrict;
?>