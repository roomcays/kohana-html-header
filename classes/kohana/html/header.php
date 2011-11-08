<?php defined('SYSPATH') or die('No direct script access.');
/**
 * This module deals with stuff that goes into the HTML header
 * section, like stylesheets, javascripts, favicons, meta-data, etc.
 * @author Maciej Kwiatkowski <maciek@unicorn.net.pl>
 * @package Super Kohana
 * @category HTML Header
 */

class Kohana_HTML_Header {
	
	protected
		$config,
		$title = NULL,
		$meta = array(),
		$stylesheets = array(),
		$javascripts = array(),
		$favicons = array();

	protected static $instance = NULL;
		
	public static function instance()
	{
		if (Html_Header::$instance === NULL)
		{
			Html_Header::$instance = new Html_Header();
		}
		
		return Html_Header::$instance;
	}
	
	public static function get_instance()
	{
		return Html_Header::$instance;
	}
	
	public function __construct()
	{
		$this->config = Kohana::$config->load('html_header');
	}
	
	public function __toString()
	{
		try
		{
			return $this->render()->render();
		}
		catch (Exception $e)
		{
			// Display the exception message
			Kohana::exception_handler($e);
			return NULL;
		}
	}
	
	/**
	 * Adds an item to the header object.
	 * 
	 * @param mixed $item The item to be added
	 * @param bool $reset Reset (empty) the array of the item type before adding
	 */
	public function add($item, $reset = FALSE)
	{
		switch ($item)
		{
			case is_array($item):
				foreach ($item as $_item)
				{
					$this->add($_item, $reset);
				}
				break;
			case $item instanceof Html_Header_Stylesheet:
				return ($reset) ? $this->stylesheets = array($item) : array_push($this->stylesheets, $item);
				break;
			case $item instanceof Html_Header_Javascript:
				return ($reset) ? $this->javascripts = array($item) : array_push($this->javascripts, $item);
				break;
			case $item instanceof Html_Header_Meta:
				return ($reset) ? $this->meta = array($item) : array_push($this->meta, $item);
				break;
			case $item instanceof Html_Header_Favicon:
				return ($reset) ? $this->favicons = array($item) : array_push($this->favicons, $item);
				break;
			default:
				$this->resolve_unknown_type($item, $reset);
				break;
		}
	}

	public function set($item)
	{
		return $this->add($item, TRUE);
	}
	
	/**
	 * Renders the HTML code using specified view.
	 * If no view is specified, loads the default one specified in module configuration.
	 * @param string $view View location
	 */
	public function render($view = NULL)
	{
		try
		{
			$view = View::factory(empty($view) ? $this->config->get('view') : $view);
			$view
				->set('title', $this->title)
				->set('meta', $this->meta)
				->set('favicons', $this->favicons)
				->set('stylesheets', $this->stylesheets)
				->set('javascripts', $this->javascripts);
			
			return $view;
		}
		catch (Exception $e)
		{
			// Display the exception message
			Kohana::exception_handler($e);
			return NULL;
		}
	}
	
	/**
	 * Resolves the file type (css, js) regarding to it's
	 * source file extension.
	 * @uses File::get_extension()
	 * @param string $item
	 * @param bool $reset Reset (empty) the array of the item type before adding
	 */
	protected function resolve_unknown_type($item, $reset = FALSE)
	{
		$ext = File::get_extension($item);
		switch (strtolower($ext))
		{
			case 'css':
				return $this->add(new HTML_Header_Stylesheet($item), $reset);
				break;
			case 'js':
				return $this->add(new HTML_Header_Javascript($item), $reset);
				break;
			case 'ico':
				return $this->add(new HTML_Header_Favicon($item), $reset);
				break;
		}
	}
	
	/**
	 * Sets the page title overwrites any existing one
	 * @param string $title
	 */
	public function set_title($title)
	{
		$this->title = (string) $title;
	}
	
	/**
	 * Adds additional title to an existing one (e.g. for subpages)
	 * Note the default separator
	 * @param string $title
	 * @param string $separator
	 */
	public function add_title($title, $separator = " - ")
	{
		$this->title = ( ! empty($this->title) ? $this->title.$separator : "").$title;
	}
	
	/**
	 * Sets the page language via the meta tag
	 * @param string $language_id Examples: en-us, pl
	 */
	public function set_language($language)
	{
		return array_push($this->meta, new HTML_Header_Meta(array('http-equiv' => 'Content-Language',  'content' => (string) $language)));
	}
	
	/**
	 * Sets the page character set
	 * @param string $language_id Examples: UTF-8
	 */
	public function set_charset($charset)
	{
		return array_push($this->meta, new HTML_Header_Meta(array('http-equiv' => 'Content-Type',  'content' => 'text/html; charset='.(string) $charset)));
	}
	
}
