<?php
/*
 * Third party plugins that hijack the theme will call wp_head() to get the header template.
 * We use this to start our output buffer and render into the view/page-plugin.twig template in footer.php
 */
$GLOBALS['timberContext'] = Timber::get_context();
ob_start();
?>
<!doctype html>
<!--[if lt IE 7]><html class="no-js ie ie6 lt-ie9 lt-ie8 lt-ie7" {{site.language_attributes}}> <![endif]-->
<!--[if IE 7]><html class="no-js ie ie7 lt-ie9 lt-ie8" {{site.language_attributes}}> <![endif]-->
<!--[if IE 8]><html class="no-js ie ie8 lt-ie9" {{site.language_attributes}}> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" {{site.language_attributes}}> <!--<![endif]-->
<head>
   <meta charset="UTF-8">
        <title>    
	   		<?php wp_title('|', true, 'right'); ?>
	        <?php bloginfo('name');?>
	    </title>
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    </head>
    <body class="{{body_class}}" data-template="base.twig">
		<div class="container">
			<header class="header" >
				<nav class="navbar navbar-default">
			<div class="navbar-header">
	            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		              <span class="sr-only">Toggle navigation</span>
		              <span class="icon-bar"></span>
		              <span class="icon-bar"></span>
		              <span class="icon-bar"></span>
		            </button>
	          <?php the_custom_logo(); ?>
	          </div>
					 <div id="navbar" class="navbar-collapse collapse">
						<ul class="nav navbar-nav">
							 <?php 
					              $defaults = array(
					                'container' => false,
					                'theme_location' => 'primary-menu',
					                'items_wrap'=>'%3$s',
					              );
					            
					             wp_nav_menu( $defaults );
					          ?>
						</ul>
						
					</div><!-- #nav -->
			</nav>
		</header>
<section id="content" role="main" class="content-wrapper">