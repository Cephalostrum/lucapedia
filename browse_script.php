<?php
$user="ecology_rolview";
$password="roluser";
$database="ecology_rootoflife";
mysql_connect(localhost, $user, $password);
@mysql_select_db($database) or die("unable to select database");

$query = mysql_real_escape_string($_POST['let']);
$page = mysql_real_escape_string($_POST['page']);
$max_dash = mysql_real_escape_string($_POST['max_dash']);

if( $query == "" ) exit();

$from = 100*$page;

$search_query = "";

for($index = 0; $index < strlen($query); $index++) {
	if($query[$index] != '#') {
		$search_query .= $query[$index];
	} else {
		$search_query .= "[^a-z]";
	}
}
	
$query_all = "SELECT DISTINCT name FROM name_Uniprot_lookup_table INNER JOIN RoLDB_table ON name_Uniprot_lookup_table.id = RoLDB_table.id WHERE name_Uniprot_lookup_table.name REGEXP '^".$search_query."' AND ( (c1 <> '-') + (c2 <> '-') + (c3 <> '-') + (c4 <> '-') + (c5 <> '-') + (c6 <> '-') + (c7 <> '-') + (c8 <> '-') + (c9 <> '-') + (c10 <> '-') + (c11 <> '-') >= $max_dash)";
$query = $query_all." LIMIT $from, 100;";	

$result = mysql_query($query);
$num_rows = mysql_num_rows($result);
$num_rows_all = mysql_num_rows(mysql_query($query_all));

if( $num_rows == 100 && $page == 0 ) {
	echo "<ul class='pager'><li class='next'><a onClick='next_page();'>Next &rarr;</a></li></ul>";
	echo "<div class='alert alert-success'>".$num_rows_all." records found. Displaying first $num_rows records</div>";
} elseif ( $num_rows == 100 ) {
	echo "<ul class='pager'> <li class='previous'> <a onClick='prev_page();'>&larr; Previous</a></li><li class='next'><a onClick='next_page();'>Next &rarr;</a></li></ul>";
	$from = 100*$page + 1;
	$to = $from + 99;
	echo "<div class='alert alert-success'>".$num_rows_all." records found. Displaying records $from to $to</div>";
} elseif ( $num_rows != 100 && $page != 0 ) {
	$from = 100*$page + 1;
	$to = $from + $num_rows - 1;
	echo "<ul class='pager'> <li class='previous'> <a onClick='prev_page();'>&larr; Previous</a></li></ul>";
	echo "<div class='alert alert-success'>".$num_rows_all." records found. Displaying records $from to $to</div>";
} else {
	echo "<div class='alert alert-success'>$num_rows records found</div>";
}
 	
if( $num_rows == 0 ) {
	echo "<div class='alert alert-error'>no results, sorry!</div>";
	exit();
}

echo "<table class=\"table table-bordered\">";
echo "<thead>";
echo "<tr>";
echo "<td> protein name </td>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

while($row = mysql_fetch_array($result)) {
	$cur_name = $row['name'];
	echo "<tr>";
	echo "<td><a onClick=\"window.page_num = 0; window.prot_name = '".addslashes($cur_name)."'; query_db()\">$cur_name</a></td>";
	echo "</tr>";
}

echo "</tbody>";
echo "</table>";
?>