<?php
    session_start();
	ini_set('memory_limit', '128M');
    include($_SERVER[DOCUMENT_ROOT] . '/templates/v4/pre_head.inc');

	require($_SERVER[DOCUMENT_ROOT] . "/lib/dbconnect.inc.php");
	require($_SERVER[DOCUMENT_ROOT] . "/lib/seo.inc.php");
    require($_SERVER[DOCUMENT_ROOT] . "/lib/normalize.inc.php");
    require($_SERVER[DOCUMENT_ROOT] . "/lib/navigation.inc.php");
    require($_SERVER[DOCUMENT_ROOT] . "/lib/data.inc.php");
    require($_SERVER[DOCUMENT_ROOT] . "/lib/products.inc.php");

	// Caching using Pear Cache_Lite
	require_once("Cache/Lite.php");
	$objCache = new Cache_Lite();
	$cachetitle = 'product';
	

	if(is_numeric($_GET['ID'])) {
        	$productid = $_GET['ID'];
	}
	$f = "data/product_package_map.dat";

	$data_cache = '';
	$name_data_cache = '';
	if ($_SERVER['SERVER_NAME'] != 'staging.netcomlearning.com') { // <- for easy testing on stage
        	$id_cache = $cachetitle . '_' . $_GET['ID'];
        	$data_cache = $objCache->get($id_cache);
	}

	if (!empty($data_cache)) {
        	$cache_hit = '<!-- cache hit -->';
	} else {
        	$data_cache = product_body($f, $productid);
        	$objCache->save($data_cache, $id_cache);
        	$cache_hit = '';
	}
	$content = $data_cache . $cache_hit;

	//SET META TAGS
	$Title = Page_Title($f,$productid);

	$Prod = GetDataFile($f);
	$CProd = $Prod[(int)$productid];
	$fdn = nlize($CProd[ProductName]);
	$canonical = canonical("http://www.netcomlearning.com/products/$productid/$fdn");

	$ProductName = $CProd['ProductName'];
?>
<head>
	<title><?php echo seo_title($f,$productid,'Product',$Title); ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<meta name="keywords" content="<?php echo seo_keywords($f,$productid,'Product'); ?>">
	<meta name="description" content="<?php echo seo_description($f,$productid,'Product'); ?>">
	<META NAME="Robots" content="ALL, index, follow">
	<META NAME="resource-type" CONTENT="document">
	<META NAME="distribution" CONTENT="Global">
<?php
	if($canonical) {
		echo $canonical;
	}
    include($_SERVER[DOCUMENT_ROOT] . '/templates/v4/head.inc');
	include($_SERVER[DOCUMENT_ROOT] . '/templates/livechat.inc');
?>
	<script language='javascript' src='/lib/js/ajax.js'></script>
</head>
<body>
    	<!-- START WRAP -->
        <div id="wrap">
	<?php
		include($_SERVER[DOCUMENT_ROOT] . '/templates/v4/body_header_courses.inc');
	?>

	<!-- START MAIN CONTAINER -->
	<div class="container clearfix">

		<a name=top></a>

                    <!-- START PAGE TITLE -->
                    <div class="page_title clearfix" id ="recorded">
                      <div class="ten columns alpha">
                        <h1><?php echo $ProductName; ?> Training Courses</h1>
                      </div>

                    </div>
                    <!-- END PAGE TITLE -->
                    <div class="clear"></div>


        	<div class="sixteen columns">
                    <!-- START BREADCRUMBS -->
                    <div>
                        <ul class="breadcrumbs row color">
                            <li><a href="/">&#160 Home</a> <span>&raquo;</span> </li>
                            <li><a href="/products/">Products</a> <span>&raquo;</span> </li>
                            <li><?php echo $ProductName; ?></li>
                        </ul>
                    </div>
                    <!-- END BREADCRUMBS -->
		<div class="clear"></div>

		<div class="twelve columns alpha">
		<!-- START CENTRAL CONTENT -->
		<?php
			echo $content;
		?>
		<!-- END CENTRAL CONTENT -->
		</div>

                <!-- START R Nav -->
                <div class="four columns alpha">
                <nav class="rightnav">
                        <?php
                            include($_SERVER[DOCUMENT_ROOT] . '/templates/v4/rightnav_main_top.inc');
                        ?>
                </nav>
                </div>
                <!-- END R Nav -->

        	</div>
	</div>
	<!-- END MAIN CONTAINER -->

	<?php
		include($_SERVER[DOCUMENT_ROOT] . '/templates/v4/body_searchbar.inc');
		include($_SERVER[DOCUMENT_ROOT] . '/templates/v4/body_top_footer.inc');
		include($_SERVER[DOCUMENT_ROOT] . '/templates/v4/body_bottom_footer.inc');
		include($_SERVER[DOCUMENT_ROOT] . '/templates/v4/body_javascript.inc');
	?>
	</div>
	<!-- END WRAP -->
</body>
</html>
