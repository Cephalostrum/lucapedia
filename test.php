This is a test! <br />
<?
  $user="ecology_rolview";
  $password="roluser";
  $database="ecology_rootoflife";
  mysql_connect(localhost, $user, $password);
  @mysql_select_db($database) or die("unable to select database");
  $query="SELECT * FROM gene_name";
  $result=mysql_query($query);
  
  while($row = mysql_fetch_array($result))
  {
  echo $row['name'] . " " . $row['id'];
  echo "<br />";
  }

  mysql_close();
?>