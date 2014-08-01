<?php
	
$user="ecology_rolview";
$password="roluser";
$database="ecology_rootoflife";
mysql_connect(localhost, $user, $password);
@mysql_select_db($database) or die("unable to select database");
	
$id = mysql_real_escape_string($_POST['name']);
$id_type = mysql_real_escape_string($_POST['selected']);
$page = mysql_real_escape_string($_POST['page']);

//if the search box is blank
if( $id== "" ) {
	if( $id_type == 'protein_name' ) {
		echo "<div class='alert alert-error'>please enter a protein name</div>";
	} elseif( $id_type == 'partial_protein_name' ) {
		echo "<div class='alert alert-error'>please enter a partial protein name</div>";
	} else {
		echo "<div class='alert alert-error'>please enter a Uniprot ID</div>";
	}
	exit();
}

//form a query depending on the id type
if( $id_type == 'protein_name' ) {
	$index = 50*$page;
	$query_all = "SELECT * FROM name_Uniprot_lookup_table INNER JOIN RoLDB_table ON name_Uniprot_lookup_table.id = RoLDB_table.id WHERE name_Uniprot_lookup_table.name = '$id'";
	$query = $query_all." ORDER BY LENGTH(c1) + LENGTH(c2) + LENGTH(c3) + LENGTH(c4) + LENGTH(c4) + LENGTH(c5) + LENGTH(c6) + LENGTH(c7) + LENGTH(c8) + LENGTH(c9) + LENGTH(c10) + LENGTH(c11) DESC LIMIT $index, 51";
	#"SELECT * FROM name_Uniprot_lookup_table INNER JOIN RoLDB_table ON name_Uniprot_lookup_table.id = RoLDB_table.id WHERE name_Uniprot_lookup_table.name = '$id' ORDER BY LENGTH(c1) + LENGTH(c2) + LENGTH(c3) + LENGTH(c4) + LENGTH(c4) + LENGTH(c5) + LENGTH(c6) + LENGTH(c7) + LENGTH(c8) + LENGTH(c9) + LENGTH(c10) + LENGTH(c11) DESC LIMIT $index, 51"
} elseif( $id_type == 'partial_protein_name' ) {
	$index = 50*$page;
	$query_all = "SELECT * FROM name_Uniprot_lookup_table INNER JOIN RoLDB_table ON name_Uniprot_lookup_table.id = RoLDB_table.id WHERE name_Uniprot_lookup_table.name LIKE '%$id%'";
	$query = $query_all." ORDER BY LENGTH(c1) + LENGTH(c2) + LENGTH(c3) + LENGTH(c4) + LENGTH(c4) + LENGTH(c5) + LENGTH(c6) + LENGTH(c7) + LENGTH(c8) + LENGTH(c9) + LENGTH(c10) + LENGTH(c11) DESC LIMIT $index, 51";
	#"SELECT * FROM name_Uniprot_lookup_table INNER JOIN RoLDB_table ON name_Uniprot_lookup_table.id = RoLDB_table.id WHERE name_Uniprot_lookup_table.name LIKE '%$id%' ORDER BY LENGTH(c1) + LENGTH(c2) + LENGTH(c3) + LENGTH(c4) + LENGTH(c4) + LENGTH(c5) + LENGTH(c6) + LENGTH(c7) + LENGTH(c8) + LENGTH(c9) + LENGTH(c10) + LENGTH(c11) DESC LIMIT $index, 51";
} else {
	$query_all = "SELECT * FROM RoLDB_table WHERE RoLDB_table.id = '$id'";
	$query = $query_all;
}

//query the database
$num_rows_all = mysql_num_rows(mysql_query($query_all));
$result = mysql_query($query);

if(!$result) {
	echo "<div class='alert alert-error'>too many results, please be more specific</div>";
	exit();
}
	
$num_rows = mysql_num_rows($result);

if( $num_rows == 0 ) {
	 echo "<div class='alert alert-error'>no results, sorry!</div>";
	 exit();
}

if( $num_rows == 51 && $page == 0 ) {
	echo "<ul class='pager'><li class='next'><a onClick='next_page();'>Next &rarr;</a></li></ul>";
	echo "<div class='alert alert-success'>".$num_rows_all." records found. Displaying first 50. </div>";
} elseif( $num_rows == 51 ) {
	echo "<ul class='pager'> <li class='previous'> <a onClick='prev_page();'>&larr; Previous</a></li><li class='next'><a onClick='next_page();'>Next &rarr;</a></li></ul>";
	$from = 50*$page + 1;
	$to = $from + 49;
	echo "<div class='alert alert-success'>".$num_rows_all." records found. Displaying records $from to $to</div>";
} elseif( $num_rows != 51 && $page != 0 ) {
	$from = 50*$page + 1;
	$to = $from + $num_rows - 1;
	echo "<ul class='pager'> <li class='previous'> <a onClick='prev_page();'>&larr; Previous</a></li></ul>";
	echo "<div class='alert alert-success'>".$num_rows_all." records found. Displaying records $from to $to (out of ".$num_rows_all.")</div>";
} else {
	echo "<div class='alert alert-success'>$num_rows records found</div>";
}
			
echo "<table class='table table-condensed table-bordered' >";
echo "<thead>";
echo "<tr>";

if( $id_type != 'uniprot_id' ) echo "<th>protein name</th>"; 
echo "<th>Uniprot ID<sup>1</sup></th>";
echo "<th>Harris et al., 2003<sup>2</sup> (COG ID<sup>3</sup>)</th>";
echo "<th>Mirkin et al., 2003<sup>4</sup> (COG ID<sup>3</sup>)</th>";
echo "<th>Delaye et al., 2005<sup>5</sup> (Pfam ID<sup>6</sup>)</th>";
echo "<th>Yang et al., 2005<sup>7</sup> (SCOP superfamily ID<sup>8</sup>)</th>";
echo "<th>Wang et al., 2007<sup>9</sup> (SCOP fold ID<sup>8</sup>)</th>";
echo "<th>Srinivasan and Morowitz, 2009<sup>10</sup> (Enzyme commission code<sup>11</sup>)</th>";
echo "<th>Ribozyme function (Enzyme commission code<sup>11</sup>)</th>";
echo "<th>Nucleotide cofactor usage</th>";
echo "<th>Amino acid cofactor usage</th>";
echo "<th>Iron sulfur cofactor usage</th>";
echo "<th>Zinc cofactor usage</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
  		
$i = 0;
while($row = mysql_fetch_array($result)) {
	if($i++ == 50) break;		
    if( $id_type != 'uniprot_id' ) $cur_name = $row['name'];
	$cur_id = $row['id'];
	$c1 = $row['c1'];
	$c2 = $row['c2'];
	$c3 = $row['c3'];
	$c4 = $row['c4'];
	$c5 = $row['c5'];
	$c6 = $row['c6'];
	$c7 = $row['c7'];
	$c8 = $row['c8'];
	$c9 = $row['c9'];
	$c10 = $row['c10'];
	$c11 = $row['c11'];
      		
	echo "<tr>";
	if( $id_type != 'uniprot_id' ) echo "<td>$cur_name</td>";
	echo "<td>$cur_id</td>";
	echo "<td>$c1</td>";
	echo "<td>$c2</td>";
	echo "<td>$c3</td>";
	echo "<td>$c4</td>";
	echo "<td>$c5</td>";
	echo "<td>$c6</td>";
	echo "<td>$c7</td>";
	echo "<td>$c8</td>";
	echo "<td>$c9</td>";
	echo "<td>$c10</td>";
	echo "<td>$c11</td>";
	echo "</tr>";
}
  		
echo "</tbody>";
echo "</table>";

echo "<sup>1</sup> http://www.uniprot.org/ <br>";
echo "<sup>2</sup> Harris <i>et al.</i> (2003) Genome Research 13:407	http://www.ncbi.nlm.nih.gov/pubmed/12618371 <br>";
echo "<sup>3</sup> http://www.ncbi.nlm.nih.gov/COG/ <br>";
echo "<sup>4</sup> Mirkin <i>et al.</i> (2003) BMC Evolutionary Biology 3:2,	www.ncbi.nlm.nih.gov/pubmed/12515582 <br>";
echo "<sup>5</sup> Delaye <i>et al.</i> (2005) Origins of Life and Evolution of Biospheres 35:537-554	http://www.ncbi.nlm.nih.gov/pubmed/16254691 <br>";
echo "<sup>6</sup> http://pfam.sanger.ac.uk/ <br>";
echo "<sup>7</sup> Yang <i>et al.</i> (2005) Proceedings of the National Academy of Sciences USA 102:373-378	http://www.ncbi.nlm.nih.gov/pubmed/15630082 <br>";
echo "<sup>8</sup> http://scop.mrc-lmb.cam.ac.uk/scop/ <br>";
echo "<sup>9</sup> Wang <i>et al.</i> (2007) Genome Research 17:1572-1585	http://www.ncbi.nlm.nih.gov/pubmed/17908824 <br>";
echo "<sup>10</sup> Srinvasan and Morowitz (2009) Biological Bulletin 216:126-130	http://www.ncbi.nlm.nih.gov/pubmed/19366923 <br>";
echo "<sup>11</sup> http://www.chem.qmul.ac.uk/iubmb/enzyme/ <br>";
?>