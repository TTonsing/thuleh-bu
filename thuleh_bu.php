<?php
/*
*Plugin Name: Thuleh Bu
*Description: English-Paite Dictionary 
*Version: 1.0.0 Version updated on 20191017
*Author: TTonsing @Mungboi Media
*Author URI: http://mungboimedia.byethost7.com
*Description: This is a Bilingual Dictionary. In Paite dialect (tongue) 'Dictionary' is referred to as Thulehna Bu / Thuleh Bu etc. Interpreting the meaning of a word is known as 'Thulet, Thuleh, or Thukhia, or Khia.
*Licence: GPL v2.0 or Later
*Version Updates: No version update yet. Will be updated in future for features like Update/Delete functionalities incorporated in this version, confirmation before alterig records, deletion etc added. UI tweaks, change the Plugins Icons.
*/

// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	} 
/**
 * $thulehbu_example_db_version - holds current database version
 * and used on plugin update to sync database tables
 */
global $thulehbu_test_db_version;
$thulehbu_test_db_version = '1.0.0'; // version may changed higher values 1.0.1 to 1.0.2 etc

 // function to create the DB / Options / Defaults	

function thulehbu_table_install() {

    global $wpdb;
	global $charset_collate;

    $table_name = $wpdb->prefix ."thuleh_bu";
    $charset_collate = $wpdb->get_charset_collate();
	/*if($wpdb ->get_var( "show tables like $table_name'" )!= $table_name ){ */
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` int(9) NOT NULL AUTO_INCREMENT,
			`word` varchar(25) NOT NULL,
			`wordtype` varchar(16) NOT NULL,
			`definition` text NOT NULL,
			`Paite` text NOT NULL,
			PRIMARY KEY (`id`)
          )$charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	// we are calling dbDelta which cant migrate database
    dbDelta($sql);
	// save current database version for later use (on upgrade)
	add_option('thulehbu_test_db_version','$thulehbu_test_db_version');
	 /**
     * [OPTIONAL] Example of updating to 1.1 version
     *
     * If you develop new version of plugin
     * just increment $cltd_example_db_version variable
     * and add following block of code
     *
     * must be repeated for each new version
     * in version 1.1 we change email field
     * to contain 200 chars rather 100 in version 1.0
     * and again we are not executing sql
     * we are using dbDelta to migrate table changes
     */
	 $installed_ver = get_option('thulehbu_test_db_version');
    if ($installed_ver != $thulehbu_test_db_version) {
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` int(9) NOT NULL AUTO_INCREMENT,
			`word` varchar(25) NOT NULL,
			`wordtype` varchar(16) NOT NULL,
			`definition` text NOT NULL,
			`Paite` text NOT NULL,
			PRIMARY KEY (`id`)
          )$charset_collate;";
		  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // notice that we are updating option, rather than adding it
        update_option('thulehbu_test_db_version', $thulehbu_test_db_version);
	}

}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'thulehbu_table_install');

/**
 * register_activation_hook implementation
 *
 * [OPTIONAL]
 * additional implementation of register_activation_hook
 * to insert some dummy data
 */
function thulehbu_table_install_data()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'thuleh_bu'; // do not forget about tables prefix

    $wpdb->insert($table_name, array(
        'word' => 'A',
        'wordtype' => '',
        'definition' => 'The first letter of the English and of many other alphabets. The\n   capital A of the alphabets of Middle and Western Europe, as also the\n   small letter (a), besides the forms in Italic, black letter, etc., are\n   all descended from the old Latin A, which was borrowed from the Greek\n   Alpha, of the same form; and this was made from the first letter (/) of\n   the Phoenician alphabet, the equivalent of the Hebrew Aleph, and itself\n   from the Egyptian origin. The Aleph was a consonant letter, with a\n   guttural breath sound that was not an element of Greek articulation;\n   and the Greeks took it to represent their vowel Alpha with the a sound,\n   the Phoenician alphabet having no vowel symbols.',
		'Paite' => 'A - Mang laimal masapen'
    ));
    $wpdb->insert($table_name, array(
        'word' => 'A',
        'wordtype' => '',
        'definition' => 'The name of the sixth tone in the model major scale (that in C),\n   or the first tone of the minor scale, which is named after it the scale\n   in A minor. The second string of the violin is tuned to the A in the\n   treble staff. -- A sharp (A/) is the name of a musical tone\n   intermediate between A and B. -- A flat (A/) is the name of a tone\n   intermediate between A and G.',
		'Paite' => 'A - Ginglawm Sangki Bi'
    ));
	$wpdb->insert($table_name, array(
        'word' => 'A',
        'wordtype' => '',
        'definition' => 'An adjective, commonly called the indefinite article, and\n   signifying one or any, but less emphatically.',
		'Paite' => 'A - Adjective ahia, indefinite article leng a kichi, khat kia genna leng hithei tampi genna leng hithei'
    ));
	$wpdb->insert($table_name, array(
        'word' => 'A',
        'wordtype' => '',
        'definition' => 'In each; to or for each; as, "twenty leagues a day", "a hundred\n   pounds a year", "a dollar a yard", etc.',
		'Paite' => ''
    ));
	$wpdb->insert($table_name, array(
        'word' => 'A',
        'wordtype' => 'prep.',
        'definition' => 'In; on; at; by.',
		'Paite' => 'Noun, Pronoun ma a kikoih'
    ));
	
}
 register_activation_hook(__FILE__, 'thulehbu_table_install_data');
 
 /**
 * Trick to update plugin database, see docs
 */
function thulehbu_table_update_db_check()
{
    global $thulehbu_test_db_version;
    if (get_site_option('thulehbu_test_db_version') != $thulehbu_test_db_version) {
        thulehbu_table_install();
    }
}

add_action('plugins_loaded', 'thulehbu_table_update_db_check');

//add admin menu
add_action('admin_menu','thuleh_bu_menu');
 
 function thuleh_bu_menu(){
	 add_menu_page('thuleh_bu', // page title
		'Thuleh Bu', // menu title
		'read', //capabilities 'manage_options' 'edit_posts'
		'thuleh_bu', //menu slug
		'thuleh_bu', // function apek dang khat pen
		'dashicons-groups', // adding icons
		null );
	
 }
add_action('admin_menu','thuleh_bu_submenu');

function thuleh_bu_submenu(){   //this will add admin panel submenu
	 add_submenu_page(
		'thuleh_bu', //parent slug *
		'thulehbu_gelhlut', //page title
		'Thuleh Bu Gelhlut', //menu title
		'manage_options', //capabilities
		'thulehbu_gelhlut', // menu slug
		'thulehbu_gelhlut', //function
		'dashicons-admin-page',  // add icon
		null
		);
		add_submenu_page(
		'thuleh_bu', //parent slug *
		'thulehbu_update', //page title
		'Thulehbu Update', //menu title
		'read', //capabilities 'manage_options' 'edit_posts'
		'thulehbu_update', // menu slug
		'thulehbu_update' //function
		);
 }
define('THULEH_BU_DIR', plugin_dir_path(__FILE__)); 
require_once(THULEH_BU_DIR . 'thulehbu_update.php');
//require_once(THULEH_BU_DIR . 'thulehbu_update.php');
//require_once(THULEH_BU_DIR . 'lawm_vual_gelhlut.php');


function thuleh_bu(){
?>

	<script type="text/javascript" src="<?php echo plugin_dir_url();?>thuleh-bu/files/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="<?php echo plugin_dir_url();?>thuleh-bu/files/isMobile.min.js"></script>
	<script src="<?php echo plugin_dir_url();?>thuleh-bu/files/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="<?php echo plugin_dir_url();?>thuleh-bu/files/bootstrap-3.3.7-dist/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="<?php echo plugin_dir_url();?>thuleh-bu/files/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css"/>
	<link href="<?php echo plugin_dir_url();?>thuleh-bu/files/jumbotron-narrow.css" rel="stylesheet" />
	<link type="text/css" rel="stylesheet" href="<?php echo plugin_dir_url();?>thuleh-bu/files/dictionary.css" />
	<div class="container">
		<div class="jumbotron">
			<h1>Mungboi Media: Thuleh Bu</h1>
			<p class="lead"> [Mangpau - Eipau]
				
			</p>
			<form  method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']);?>" role="form"  id="searchform" class="form-horizontal">
				<div class="input-group input-group-lg">
					<input type="text" id="lookup" name="lookup" class="form-control" placeholder="Search word" maxlength="26">
					<span class="input-group-btn">
						<button class="btn btn-success" id="submit" name="submit" type="submit">Khaile!</button>
					</span>
                    
				</div>
                <!--div>
                    <span class="input-group btn-xs red">
				<b>If you would Like to Translate untranslated Words Press Blue Button:</b><br>
				<button type="submit" name="lehlouh" id="lehlouh" class="btn-xs btn-primary">Lehlouhte Let ding</button></span>
                </div-->
				</form><!-- added by me-->
		</div>

		<div id="results">
		
		<?php
		
	  if(isset($_POST['submit'])){ 
	  if(preg_match("/^[  a-zA-Z]+/", $_POST['lookup'])){ 
	  $name=$_POST['lookup']; 
	  
	  global $wpdb, $table_name_dic;
                $table_name_dic = $wpdb->prefix . 'thuleh_bu';
				$wpdb->show_errors();
				$query =$wpdb->prepare("SELECT * FROM {$table_name_dic} WHERE word LIKE '".$name."%'");
				//var_dump( $query );
				$results = $wpdb->get_results( $query ); 
				$rowcount= $wpdb->num_rows; 
				echo "<span class='list-group-item definition-header'>Thumal $rowcount muzou hang ei.</span><br>";;
	 
				if($rowcount <1){ echo "<span class='search-term label label-danger'> <span class='glyphicon glyphicon-info-sign'></span> Khaih ! '<b>' ".$name." '</b>' &#9 thumal zong mulah hang ei!</span>";}
				
				foreach( $results AS $row ) {
        // Don't use ARRAY_A - just access as an object
		//-display the result of the array
		echo "<div class='list-group'>";
		
		echo "<ul class='list-group'>";
		echo "<span class='search-term'>" . $row->id ."&#9" . $row->word .  " &nbsp;<span class='badge badge-success'>&#9;(". $row->wordtype .")<span class='glyphicon glyphicon-info-sign'></span></span></span>";
		
		 echo "<li class='list-group-item list-group-item-success definition'>" . "<a  href=\"index.php?page=thuleh_bu&id=$row->id\"> ". $row->definition ."<br></a></li>\n";
		if (!empty($row->Paite)){
		echo "<li class='list-group-item list-group-item-info definition'>" . "<a  href=\"index.php?page=thuleh_bu&id=$row->id\"><span class='search-term'><span class='label label-warning'> Eipau in:<br></span></span>". $row->Paite . "</a></li>\n"; 
		}else{ echo "<li class='list-group-item list-group-item-info definition'>" . "<a  href=\"index.php?page=thuleh_bu&id=$row->id\"><span class='btn-md'><span class='label label-warning'> Eipau in:<br></span></span>Mulou Poi lua !</a></li>\n";
		
		}
		echo "</ul>"; 
		echo "</div >";		
        
    }
				
	} 
  else{ 
	  echo  "<span class='label label-warning search-term'>Please enter a search query!</span>"; 
	  } 
	  } 
//end of our letter search script 
//begin of lehlouh script 
/*
	if(isset($_POST['lehlouh'])){ 
	//$thumal_id=$_GET['id'];
		$name=$_POST['lookup'];
	echo "<span class='list-group-item definition-header'>Lehlouh hiaite ahi uh, Let in:-</span>";
	global $wpdb, $table_name_dic;
                $table_name_dic = $wpdb->prefix . 'thuleh_bu';
				$wpdb->show_errors();
	$query =$wpdb->prepare("SELECT * FROM {$table_name_dic} WHERE word LIKE '".$name."%' AND Paite=''");
	$results = $wpdb->get_results( $query );
	//$results = $wpdb->get_results( $sql );
	foreach( $results AS $row ) {
		echo "<div class='list-group'>";
		
		echo "<ul class='list-group'>";
		/*echo "<span class='search-term'>" . $row->id ."&#9" . $row->word .  " &nbsp;<span class='badge badge-success'>&#9;(". $row->wordtype .")<span class='glyphicon glyphicon-info-sign'></span></span></span>"; *//*
		echo "<span class='search-term definition-header'>" . $row->word .  " &nbsp;<span class='badge badge-success'>&#9;(". $row->wordtype .")<span class='glyphicon glyphicon-info-sign'></span></span></span>";
		
		echo "<li class='list-group-item list-group-item-success definition'>" . "<!--a  href=\"index.php?id='$ID'\"--> ". $row->definition ."<br></a></li>\n"; 
		if (!empty($row->Paite)){
		echo "<li class='list-group-item list-group-item-info definition'>" . "<!--a  href=\"index.php?id='$ID'\"--><span class='search-term'><span class='label label-warning'> Eipau in:<br></span></span>". $row->Paite . "</a></li>\n";
		
		}else{ echo "<li class='list-group-item list-group-item-info definition'>" . "<!--a  href=\"index.php?id='$ID'\"--><span class='btn-md'><span class='label label-warning'> Eipau in:<br></span></span>Mulou Poi lua !</a></li>\n";
				
		echo "<span class='label label-pill label-warning'><a href=\"admin.php?page=thulehbu_update&id=$row->id\">Update</a></span>";
		}
		echo "</ul>"; 
		echo "</div >";	
	}
// end of lehlouh script
*/
	if(isset($_GET['id'])){ 
	$thumal_id=$_GET['id']; 
	//echo $thumal_id;
	global $wpdb, $table_name_dic;
                $table_name_dic = $wpdb->prefix . 'thuleh_bu';
				$wpdb->show_errors();
	$query =$wpdb->prepare("SELECT * FROM {$table_name_dic} WHERE ID=". $thumal_id);
	$results = $wpdb->get_results( $query );
	//$results = $wpdb->get_results( $sql );
	foreach( $results AS $row ) {
		echo "<div class='list-group'>";
		
		echo "<ul class='list-group'>";
		/*echo "<span class='search-term'>" . $row->id ."&#9" . $row->word .  " &nbsp;<span class='badge badge-success'>&#9;(". $row->wordtype .")<span class='glyphicon glyphicon-info-sign'></span></span></span>"; */
		echo "<span class='search-term definition-header'>" . $row->word .  " &nbsp;<span class='badge badge-success'>&#9;(". $row->wordtype .")<span class='glyphicon glyphicon-info-sign'></span></span></span>";
		
		echo "<li class='list-group-item list-group-item-success definition'>" . "<!--a  href=\"index.php?id='$ID'\"--> ". $row->definition ."<br></a></li>\n"; 
		if (!empty($row->Paite)){
		echo "<li class='list-group-item list-group-item-info definition'>" . "<!--a  href=\"index.php?id='$ID'\"--><span class='search-term'><span class='label label-warning'> Eipau in:<br></span></span>". $row->Paite . "</a></li>\n";
		
		}else{ echo "<li class='list-group-item list-group-item-info definition'>" . "<!--a  href=\"index.php?id='$ID'\"--><span class='btn-md'><span class='label label-warning'> Eipau in:<br></span></span>Mulou Poi lua !</a></li>\n";
				
		echo "<span class='label label-pill label-warning'><a href=\"admin.php?page=thulehbu_update&id=$row->id\">Update</a></span>";
		}
		echo "</ul>"; 
		echo "</div >";	
	}
	$record_id= $row->id; $next_record =$record_id+1; $previous_record= $record_id-1;
	
	?> 
		
		</div><!--results ends-->
		<div class="pager">
		<ul class="pager">
		<li><a href="<?php echo htmlspecialchars($_SERVER['REQUEST_SELF']);?>index.php?page=thuleh_bu&id=<?php  echo $previous_record;?>">Previous</a>
		<li><a href="<?php echo htmlspecialchars($_SERVER['REQUEST_SELF']);?>index.php?page=thuleh_bu&id=<?php  echo $next_record;?>">Next</a>
		</ul>
		</div>

		<footer class="footer">
			<p>&copy;
				<a href="http://mungboimedia.byethost7.com"> Mungboi Media</a>
			</p>
		</footer>
	</div>
	<?php
 }
}
/*}*/
add_shortcode('thuleh_bu','thuleh_bu');
