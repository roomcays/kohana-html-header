<?php defined('SYSPATH') OR die('No direct access allowed.');
if ( ! empty($title))
{
	echo "<title>".$title."</title>\n";
}
if ( ! empty($meta))
{
	foreach ($meta as $meta_item) echo $meta_item."\n";
}
if ( ! empty($favicons))
{
	foreach ($favicons as $favicon) echo $favicon."\n";
}
if ( ! empty($stylesheets))
{
	foreach ($stylesheets as $stylesheet) echo $stylesheet."\n";
}

if ( ! empty($javascripts))
{
	foreach ($javascripts as $javascript) echo $javascript."\n";
}
